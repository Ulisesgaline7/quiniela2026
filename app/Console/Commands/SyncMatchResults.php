<?php

namespace App\Console\Commands;

use App\Models\MatchPrediction;
use App\Models\WorldMatch;
use App\Services\FootballApiService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncMatchResults extends Command
{
    protected $signature   = 'matches:sync {--force : Force re-score already scored matches}';
    protected $description = 'Sync match results from WC2026 API and auto-score predictions';

    public function __construct(private FootballApiService $api)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('🔄 Syncing from WC2026 API...');

        $apiMatches = $this->api->getAllMatches();

        if (empty($apiMatches)) {
            $this->warn('⚠ No data from API (rate limit or network error)');
            return 1;
        }

        $this->info('  Found ' . count($apiMatches) . ' matches from API');

        $updated = 0;
        $scored  = 0;

        foreach ($apiMatches as $apiMatch) {
            // Only process finished matches with scores
            if ($apiMatch['home_score'] === null || $apiMatch['away_score'] === null) {
                continue;
            }
            if (($apiMatch['status'] ?? '') !== 'finished' && ($apiMatch['status'] ?? '') !== 'completed') {
                // Also accept if scores are set even without "finished" status
                if ($apiMatch['home_score'] === null) continue;
            }

            // Find our local match by team codes
            $homeCode = $apiMatch['home_team_code'] ?? null;
            $awayCode = $apiMatch['away_team_code'] ?? null;

            if (!$homeCode || !$awayCode) continue;

            $localMatch = WorldMatch::whereHas('homeTeam', fn($q) => $q->where('short_name', $homeCode))
                ->whereHas('awayTeam', fn($q) => $q->where('short_name', $awayCode))
                ->first();

            if (!$localMatch) continue;

            // Skip if already has same score (no change)
            if (!$this->option('force') &&
                $localMatch->home_score == $apiMatch['home_score'] &&
                $localMatch->away_score == $apiMatch['away_score'] &&
                $localMatch->status === 'finished') {
                continue;
            }

            DB::transaction(function () use ($localMatch, $apiMatch, &$updated, &$scored) {
                $localMatch->update([
                    'home_score'     => $apiMatch['home_score'],
                    'away_score'     => $apiMatch['away_score'],
                    'had_penalties'  => ($apiMatch['home_pen'] !== null || $apiMatch['away_pen'] !== null),
                    'is_open'        => false,
                    'status'         => 'finished',
                ]);
                $updated++;

                // Score predictions that haven't been scored yet
                $predictions = MatchPrediction::where('match_id', $localMatch->id)
                    ->where('scored', false)
                    ->get();

                foreach ($predictions as $pred) {
                    $this->scorePrediction($pred, $localMatch);
                    $scored++;
                }
            });

            $this->line("  ✓ {$homeCode} {$apiMatch['home_score']}–{$apiMatch['away_score']} {$awayCode}");
        }

        $this->info("✅ Updated: {$updated} matches, Scored: {$scored} predictions");

        // Also update live match status
        foreach ($apiMatches as $apiMatch) {
            if (($apiMatch['status'] ?? '') === 'live') {
                $homeCode = $apiMatch['home_team_code'] ?? null;
                $awayCode = $apiMatch['away_team_code'] ?? null;
                if (!$homeCode || !$awayCode) continue;

                WorldMatch::whereHas('homeTeam', fn($q) => $q->where('short_name', $homeCode))
                    ->whereHas('awayTeam', fn($q) => $q->where('short_name', $awayCode))
                    ->update([
                        'status'     => 'live',
                        'home_score' => $apiMatch['home_score'],
                        'away_score' => $apiMatch['away_score'],
                    ]);
            }
        }

        return 0;
    }

    private function scorePrediction(MatchPrediction $pred, WorldMatch $match): void
    {
        $pts      = 0;
        $realRes  = $this->calcResult($match->home_score, $match->away_score);
        $realDiff = abs($match->home_score - $match->away_score);
        $predDiff = abs($pred->home_score - $pred->away_score);
        $predRes  = $this->calcResult($pred->home_score, $pred->away_score);
        $bothScored = $match->home_score > 0 && $match->away_score > 0;
        $over3      = ($match->home_score + $match->away_score) > 3;

        // Marcador exacto → 12 pts
        if ($pred->home_score == $match->home_score && $pred->away_score == $match->away_score) {
            $pred->pts_exact = 12; $pts += 12;
        } else {
            // Diferencia exacta → 7 pts
            if ($predDiff === $realDiff && $predRes === $realRes) {
                $pred->pts_diff = 7; $pts += 7;
            }
            // Ganador correcto → 4 pts
            if ($predRes === $realRes) {
                $pred->pts_result = 4; $pts += 4;
            }
        }

        // Primer goleador → +4 pts
        if ($match->first_scorer_team_id && $pred->first_scorer_team_id == $match->first_scorer_team_id) {
            $pred->pts_first_scorer = 4; $pts += 4;
        }
        // Ambos anotan → +2 pts
        if ($pred->predict_both_score && $bothScored) { $pts += 2; }
        // Más de 3 goles → +2 pts
        if ($pred->predict_over3 && $over3) { $pts += 2; }
        // Tarjeta roja → +2 pts
        if ($pred->predict_red_card && $match->had_red_card) { $pred->pts_red_card = 2; $pts += 2; }
        // Penal en partido → +2 pts
        if ($pred->predict_penalty_in_game && $match->had_penalty_in_game) { $pts += 2; }
        // Gol en agregado → +3 pts
        if ($pred->predict_stoppage_goal && $match->had_stoppage_goal) { $pts += 3; }
        // Prórroga → +5 pts
        if ($pred->predict_extra_time && $match->had_extra_time) { $pred->pts_extra_time = 5; $pts += 5; }
        // Penales → +4 pts
        if ($pred->predict_penalties && $match->had_penalties) { $pred->pts_penalties = 4; $pts += 4; }

        $pred->total_points = $pts;
        $pred->scored       = true;
        $pred->save();
    }

    private function calcResult(int $home, int $away): string
    {
        if ($home > $away) return 'home';
        if ($home < $away) return 'away';
        return 'draw';
    }
}

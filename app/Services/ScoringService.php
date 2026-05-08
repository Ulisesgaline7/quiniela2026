<?php

namespace App\Services;

use App\Models\MatchPrediction;
use App\Models\Quiniela;
use App\Models\QuinielaPhasePickModel;
use App\Models\WorldMatch;

class ScoringService
{
    // ── Puntos por partido ──────────────────────────────────────────────
    const PTS_EXACT        = 10;
    const PTS_RESULT       = 4;
    const PTS_DIFF         = 6;
    const PTS_FIRST_SCORER = 3;
    const PTS_RED_CARD     = 2;
    const PTS_EXTRA_TIME   = 4;
    const PTS_PENALTIES    = 3;

    // ── Puntos quiniela maestra ─────────────────────────────────────────
    const PTS_CHAMPION     = 20;
    const PTS_RUNNER_UP    = 15;
    const PTS_THIRD        = 10;
    const PTS_GOLDEN_BALL  = 15;
    const PTS_GOLDEN_BOOT  = 15;
    const PTS_GOLDEN_GLOVE = 10;
    const PTS_BEST_YOUNG   = 10;
    const PTS_SURPRISE     = 10;
    const PTS_TOP_SCORER   = 10;
    const PTS_BEST_DEFENSE = 10;
    const PTS_TOTAL_GOALS  = 15;

    // ── Puntos por fases ────────────────────────────────────────────────
    const PTS_ROUND16      = 1;
    const PTS_QUARTERS     = 2;
    const PTS_SEMIS        = 5;
    const PTS_FINAL        = 14;

    /**
     * Grade a single match prediction against the real result.
     */
    public function gradeMatchPrediction(MatchPrediction $pred, WorldMatch $match): void
    {
        if (!$match->isFinished()) return;

        $pts = [];

        $realHome = $match->home_score;
        $realAway = $match->away_score;
        $predHome = $pred->home_score;
        $predAway = $pred->away_score;

        // Exact score
        $pts['pts_exact'] = ($predHome === $realHome && $predAway === $realAway)
            ? self::PTS_EXACT : 0;

        // If not exact, check result and diff
        if ($pts['pts_exact'] === 0) {
            $realResult = $this->calcResult($realHome, $realAway);
            $predResult = $pred->result ?? $this->calcResult($predHome, $predAway);
            $pts['pts_result'] = ($predResult === $realResult) ? self::PTS_RESULT : 0;

            $realDiff = $realHome - $realAway;
            $predDiff = ($predHome !== null && $predAway !== null) ? ($predHome - $predAway) : null;
            $pts['pts_diff'] = ($predDiff !== null && $predDiff === $realDiff) ? self::PTS_DIFF : 0;
        } else {
            $pts['pts_result'] = 0;
            $pts['pts_diff']   = 0;
        }

        // First scorer team
        $pts['pts_first_scorer'] = ($pred->first_scorer_team_id &&
            $pred->first_scorer_team_id === $match->first_scorer_team_id)
            ? self::PTS_FIRST_SCORER : 0;

        // Red card
        $pts['pts_red_card'] = ($pred->predict_red_card && $match->had_red_card)
            ? self::PTS_RED_CARD : 0;

        // Extra time
        $pts['pts_extra_time'] = ($pred->predict_extra_time && $match->had_extra_time)
            ? self::PTS_EXTRA_TIME : 0;

        // Penalties
        $pts['pts_penalties'] = ($pred->predict_penalties && $match->had_penalties)
            ? self::PTS_PENALTIES : 0;

        $pts['total_points'] = array_sum($pts);
        $pts['scored'] = true;

        $pred->update($pts);
    }

    /**
     * Grade all unscored predictions for a finished match.
     */
    public function gradeMatch(WorldMatch $match): void
    {
        if (!$match->isFinished()) return;

        $match->predictions()->where('scored', false)->each(
            fn($pred) => $this->gradeMatchPrediction($pred, $match)
        );
    }

    /**
     * Grade quiniela maestra against final tournament results.
     * Call this progressively as results come in.
     */
    public function gradeMaestra(Quiniela $q, array $results): void
    {
        $pts = [
            'points_podio'  => 0,
            'points_awards' => 0,
            'points_stats'  => 0,
            'points_phases' => 0,
        ];

        // Podio
        if (isset($results['champion_id']) && $q->champion_id === $results['champion_id'])
            $pts['points_podio'] += self::PTS_CHAMPION;
        if (isset($results['runner_up_id']) && $q->runner_up_id === $results['runner_up_id'])
            $pts['points_podio'] += self::PTS_RUNNER_UP;
        if (isset($results['third_place_id']) && $q->third_place_id === $results['third_place_id'])
            $pts['points_podio'] += self::PTS_THIRD;

        // Awards
        if (isset($results['golden_ball']) && $q->golden_ball === $results['golden_ball'])
            $pts['points_awards'] += self::PTS_GOLDEN_BALL;
        if (isset($results['golden_boot']) && $q->golden_boot === $results['golden_boot'])
            $pts['points_awards'] += self::PTS_GOLDEN_BOOT;
        if (isset($results['golden_glove']) && $q->golden_glove === $results['golden_glove'])
            $pts['points_awards'] += self::PTS_GOLDEN_GLOVE;
        if (isset($results['best_young']) && $q->best_young === $results['best_young'])
            $pts['points_awards'] += self::PTS_BEST_YOUNG;
        if (isset($results['surprise_team_id']) && $q->surprise_team_id === $results['surprise_team_id'])
            $pts['points_awards'] += self::PTS_SURPRISE;

        // Stats
        if (isset($results['top_scorer_team_id']) && $q->top_scorer_team_id === $results['top_scorer_team_id'])
            $pts['points_stats'] += self::PTS_TOP_SCORER;
        if (isset($results['best_defense_id']) && $q->best_defense_id === $results['best_defense_id'])
            $pts['points_stats'] += self::PTS_BEST_DEFENSE;
        if (isset($results['total_goals']) && $q->total_goals_guess !== null) {
            $diff = abs($q->total_goals_guess - $results['total_goals']);
            if ($diff === 0) $pts['points_stats'] += self::PTS_TOTAL_GOALS;
            elseif ($diff <= 3) $pts['points_stats'] += (int)(self::PTS_TOTAL_GOALS * 0.6);
            elseif ($diff <= 8) $pts['points_stats'] += (int)(self::PTS_TOTAL_GOALS * 0.3);
        }

        // Phase picks
        $phasePoints = [
            'round_of_16' => self::PTS_ROUND16,
            'quarters'    => self::PTS_QUARTERS,
            'semis'       => self::PTS_SEMIS,
            'final'       => self::PTS_FINAL,
        ];

        foreach ($phasePoints as $phase => $ptsEach) {
            if (!isset($results['phase_teams'][$phase])) continue;
            $realTeams = $results['phase_teams'][$phase];

            $q->phasePicks()->where('phase', $phase)->each(function ($pick) use ($realTeams, $ptsEach, &$pts) {
                if (in_array($pick->team_id, $realTeams)) {
                    $pick->update(['points_earned' => $ptsEach]);
                    $pts['points_phases'] += $ptsEach;
                }
            });
        }

        $pts['total_points'] = $pts['points_podio'] + $pts['points_awards']
            + $pts['points_stats'] + $pts['points_phases'];

        $q->update($pts);
    }

    private function calcResult(int $home, int $away): string
    {
        if ($home > $away) return 'home';
        if ($home < $away) return 'away';
        return 'draw';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MatchPrediction;
use App\Models\Quiniela;
use App\Models\QuinielaGroupPick;
use App\Models\QuinielaPhasePickModel;
use App\Models\Team;
use App\Models\User;
use App\Models\WcGroup;
use App\Models\WorldMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $matches = WorldMatch::with(['homeTeam','awayTeam'])
            ->orderBy('kickoff_at')->get();
        $users   = User::orderBy('name')->get();
        $groups  = WcGroup::with('teams')->orderBy('name')->get();

        return view('admin.index', compact('matches','users','groups'));
    }

    // ── User management ──────────────────────────────────────────────
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
        ]);

        User::create([
            'name'     => $validated['name'],
            'username' => strtolower($validated['username']),
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return back()->with('success', "Usuario '{$validated['username']}' creado. Ya puede iniciar sesión.");
    }

    public function deleteUser(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'No puedes eliminar un administrador.');
        }
        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }

    // ── Match results ─────────────────────────────────────────────────
    public function scoreMatch(Request $request, WorldMatch $match)
    {
        $validated = $request->validate([
            'home_score'           => 'required|integer|min:0|max:30',
            'away_score'           => 'required|integer|min:0|max:30',
            'had_extra_time'       => 'boolean',
            'had_penalties'        => 'boolean',
            'had_red_card'         => 'boolean',
            'first_scorer_team_id' => 'nullable|exists:teams,id',
        ]);

        DB::transaction(function () use ($match, $validated, $request) {
            $match->update([
                'home_score'           => $validated['home_score'],
                'away_score'           => $validated['away_score'],
                'had_extra_time'       => $request->boolean('had_extra_time'),
                'had_penalties'        => $request->boolean('had_penalties'),
                'had_red_card'         => $request->boolean('had_red_card'),
                'first_scorer_team_id' => $validated['first_scorer_team_id'] ?? null,
                'is_open'              => false,
                'status'               => 'finished',
            ]);

            $this->scorePredictions($match);
        });

        return back()->with('success', "Resultado guardado y pronósticos puntuados ✓");
    }

    private function scorePredictions(WorldMatch $match): void
    {
        $realResult = $this->calcResult($match->home_score, $match->away_score);
        $realDiff   = abs($match->home_score - $match->away_score);

        MatchPrediction::where('match_id', $match->id)
            ->where('scored', false)
            ->get()
            ->each(function ($pred) use ($match, $realResult, $realDiff) {
                $pts      = 0;
                $predDiff = abs($pred->home_score - $pred->away_score);
                $predRes  = $this->calcResult($pred->home_score, $pred->away_score);

                if ($pred->home_score == $match->home_score && $pred->away_score == $match->away_score) {
                    $pred->pts_exact = 10; $pts += 10;
                } else {
                    if ($predRes === $realResult)  { $pred->pts_result = 4; $pts += 4; }
                    if ($predDiff === $realDiff)   { $pred->pts_diff   = 6; $pts += 6; }
                }
                if ($match->first_scorer_team_id && $pred->first_scorer_team_id == $match->first_scorer_team_id) {
                    $pred->pts_first_scorer = 3; $pts += 3;
                }
                if ($pred->predict_red_card   && $match->had_red_card)   { $pred->pts_red_card   = 2; $pts += 2; }
                if ($pred->predict_extra_time && $match->had_extra_time) { $pred->pts_extra_time = 4; $pts += 4; }
                if ($pred->predict_penalties  && $match->had_penalties)  { $pred->pts_penalties  = 3; $pts += 3; }

                $pred->total_points = $pts;
                $pred->scored       = true;
                $pred->save();
            });
    }

    // ── Score Quiniela Maestra ────────────────────────────────────────
    public function scoreMaestra(Request $request)
    {
        $validated = $request->validate([
            'champion_id'        => 'required|exists:teams,id',
            'runner_up_id'       => 'required|exists:teams,id',
            'third_place_id'     => 'required|exists:teams,id',
            'golden_ball'        => 'required|string',
            'golden_boot'        => 'required|string',
            'golden_glove'       => 'required|string',
            'best_young'         => 'required|string',
            'surprise_team_id'   => 'nullable|exists:teams,id',
            'top_scorer_team_id' => 'required|exists:teams,id',
            'best_defense_id'    => 'required|exists:teams,id',
            'total_goals_real'   => 'required|integer',
            'round_of_32_teams'  => 'required|array',
            'semis_teams'        => 'required|array',
            'final_real_teams'   => 'required|array',
        ]);

        Quiniela::with(['phasePicks','groupPicks'])->where('submitted', true)->get()
            ->each(function ($q) use ($validated) {
                $pts = 0;

                // Podio
                $ptsPodio = 0;
                if ($q->champion_id   == $validated['champion_id'])   $ptsPodio += 20;
                if ($q->runner_up_id  == $validated['runner_up_id'])  $ptsPodio += 15;
                if ($q->third_place_id == $validated['third_place_id']) $ptsPodio += 10;
                $q->points_podio = $ptsPodio; $pts += $ptsPodio;

                // Awards
                $ptsAwards = 0;
                if ($q->golden_ball  === $validated['golden_ball'])  $ptsAwards += 15;
                if ($q->golden_boot  === $validated['golden_boot'])  $ptsAwards += 15;
                if ($q->golden_glove === $validated['golden_glove']) $ptsAwards += 10;
                if ($q->best_young   === $validated['best_young'])   $ptsAwards += 10;
                if ($validated['surprise_team_id'] && $q->surprise_team_id == $validated['surprise_team_id']) $ptsAwards += 10;
                $q->points_awards = $ptsAwards; $pts += $ptsAwards;

                // Stats
                $ptsStats = 0;
                if ($q->top_scorer_team_id == $validated['top_scorer_team_id']) $ptsStats += 10;
                if ($q->best_defense_id    == $validated['best_defense_id'])    $ptsStats += 10;
                $diff = abs($q->total_goals_guess - $validated['total_goals_real']);
                $ptsStats += match(true) {
                    $diff === 0  => 15, $diff <= 3  => 12,
                    $diff <= 7   => 8,  $diff <= 15 => 4, default => 0,
                };
                $q->points_stats = $ptsStats; $pts += $ptsStats;

                // Phase picks
                $ptsPhases = 0;
                $phasePoints = ['round_of_32' => 1, 'semis' => 5, 'final' => 14];
                $phaseKeys   = ['round_of_32' => 'round_of_32_teams', 'semis' => 'semis_teams', 'final' => 'final_real_teams'];
                foreach ($q->phasePicks as $pick) {
                    $key = $phaseKeys[$pick->phase] ?? null;
                    if ($key && in_array($pick->team_id, $validated[$key])) {
                        $pick->points_earned = $phasePoints[$pick->phase] ?? 0;
                        $pick->save();
                        $ptsPhases += $pick->points_earned;
                    }
                }
                $q->points_phases = $ptsPhases; $pts += $ptsPhases;

                $q->total_points = $pts;
                $q->save();
            });

        return back()->with('success', 'Quinielas Maestras puntuadas ✓');
    }

    private function calcResult(int $home, int $away): string
    {
        if ($home > $away) return 'home';
        if ($home < $away) return 'away';
        return 'draw';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Quiniela;
use App\Models\QuinielaPhasePickModel;
use App\Models\Team;
use App\Models\WcGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuinielaMaestraController extends Controller
{
    // Tournament starts June 11 2026 — maestra closes 1 day before
    const MAESTRA_CLOSES = '2026-06-10 23:59:59';

    public function index()
    {
        $isClosed = now()->gte(self::MAESTRA_CLOSES);
        $groups   = WcGroup::with(['teams' => fn($q) => $q->orderBy('fifa_ranking')])->orderBy('name')->get();
        $teams    = Team::orderBy('name')->get();
        $quiniela = Quiniela::with([
            'champion','runnerUp','thirdPlace',
            'surpriseTeam','topScorerTeam','bestDefense',
            'phasePicks.team',
        ])->where('user_id', Auth::id())->first();

        // Group picks keyed by group_id => [pos1_team_id, pos2_team_id]
        $groupPicks = [];
        if ($quiniela) {
            $picks = \App\Models\QuinielaGroupPick::where('quiniela_id', $quiniela->id)
                ->orderBy('position')->get();
            foreach ($picks as $p) {
                $groupPicks[$p->wc_group_id][$p->position] = $p->team_id;
            }
        }

        $players = $this->players();

        return view('quiniela.maestra', compact(
            'groups','teams','quiniela','groupPicks','players','isClosed'
        ));
    }

    public function store(Request $request)
    {
        if (now()->gte(self::MAESTRA_CLOSES)) {
            return back()->with('error', 'La Quiniela Maestra cerró el 10 de junio. Ya no se puede modificar.');
        }

        $user     = Auth::user();
        $existing = Quiniela::where('user_id', $user->id)->first();

        if ($existing && $existing->submitted) {
            return back()->with('error', 'Ya enviaste tu Quiniela Maestra. No se puede modificar.');
        }

        $validated = $request->validate([
            'champion_id'         => 'required|exists:teams,id',
            'runner_up_id'        => 'required|exists:teams,id',
            'third_place_id'      => 'required|exists:teams,id',
            'golden_ball'         => 'required|string|max:100',
            'golden_boot'         => 'required|string|max:100',
            'golden_glove'        => 'required|string|max:100',
            'best_young'          => 'required|string|max:100',
            'surprise_team_id'    => 'required|exists:teams,id',
            'top_scorer_team_id'  => 'required|exists:teams,id',
            'best_defense_id'     => 'required|exists:teams,id',
            'total_goals_guess'   => 'required|integer|min:50|max:400',
            // Group picks: group_picks[group_id][1] = team_id, [2] = team_id
            'group_picks'         => 'required|array',
            'group_picks.*'       => 'array|size:2',
            'group_picks.*.*'     => 'exists:teams,id',
            // Phase picks derived from group picks
            'semis'               => 'required|array|size:4',
            'semis.*'             => 'exists:teams,id',
            'final_teams'         => 'required|array|size:2',
            'final_teams.*'       => 'exists:teams,id',
        ]);

        DB::transaction(function () use ($user, $validated, $existing) {
            $quiniela = $existing ?? new Quiniela(['user_id' => $user->id]);
            $quiniela->fill([
                'champion_id'        => $validated['champion_id'],
                'runner_up_id'       => $validated['runner_up_id'],
                'third_place_id'     => $validated['third_place_id'],
                'golden_ball'        => $validated['golden_ball'],
                'golden_boot'        => $validated['golden_boot'],
                'golden_glove'       => $validated['golden_glove'],
                'best_young'         => $validated['best_young'],
                'surprise_team_id'   => $validated['surprise_team_id'],
                'top_scorer_team_id' => $validated['top_scorer_team_id'],
                'best_defense_id'    => $validated['best_defense_id'],
                'total_goals_guess'  => $validated['total_goals_guess'],
                'submitted'          => true,
                'submitted_at'       => now(),
            ]);
            $quiniela->save();

            // Save group picks
            \App\Models\QuinielaGroupPick::where('quiniela_id', $quiniela->id)->delete();
            foreach ($validated['group_picks'] as $groupId => $positions) {
                foreach ($positions as $pos => $teamId) {
                    \App\Models\QuinielaGroupPick::create([
                        'quiniela_id'  => $quiniela->id,
                        'wc_group_id'  => $groupId,
                        'team_id'      => $teamId,
                        'position'     => $pos,
                    ]);
                }
            }

            // Save phase picks (round_of_32 derived from group picks, semis & final manual)
            QuinielaPhasePickModel::where('quiniela_id', $quiniela->id)->delete();

            // Round of 32: all 24 group picks (2 per group × 12 groups)
            foreach ($validated['group_picks'] as $groupId => $positions) {
                foreach ($positions as $teamId) {
                    QuinielaPhasePickModel::create([
                        'quiniela_id' => $quiniela->id,
                        'phase'       => 'round_of_32',
                        'team_id'     => $teamId,
                    ]);
                }
            }

            // Semis
            foreach ($validated['semis'] as $teamId) {
                QuinielaPhasePickModel::create([
                    'quiniela_id' => $quiniela->id,
                    'phase'       => 'semis',
                    'team_id'     => $teamId,
                ]);
            }

            // Final
            foreach ($validated['final_teams'] as $teamId) {
                QuinielaPhasePickModel::create([
                    'quiniela_id' => $quiniela->id,
                    'phase'       => 'final',
                    'team_id'     => $teamId,
                ]);
            }
        });

        return redirect()->route('quiniela.maestra')
            ->with('success', '¡Quiniela Maestra enviada! Buena suerte 🏆');
    }

    private function players(): array
    {
        return [
            'golden_ball' => [
                'Lionel Messi (ARG)','Kylian Mbappé (FRA)','Erling Haaland (NOR)',
                'Vinicius Jr. (BRA)','Jude Bellingham (ENG)','Lamine Yamal (ESP)',
                'Pedri (ESP)','Florian Wirtz (ALE)','Rodri (ESP)',
                'Federico Valverde (URU)','Phil Foden (ENG)','Cristiano Ronaldo (POR)',
            ],
            'golden_boot' => [
                'Kylian Mbappé (FRA)','Erling Haaland (NOR)','Vinicius Jr. (BRA)',
                'Lionel Messi (ARG)','Harry Kane (ENG)','Lautaro Martínez (ARG)',
                'Cristiano Ronaldo (POR)','Álvaro Morata (ESP)','Richarlison (BRA)',
                'Romelu Lukaku (BEL)','Memphis Depay (NED)','Darwin Núñez (URU)',
            ],
            'golden_glove' => [
                'Emiliano Martínez (ARG)','Thibaut Courtois (BEL)','Mike Maignan (FRA)',
                'Alisson (BRA)','Diogo Costa (POR)','Jordan Pickford (ENG)',
                'Manuel Neuer (ALE)','Unai Simón (ESP)','Yann Sommer (SUI)',
            ],
            'best_young' => [
                'Lamine Yamal (ESP)','Florian Wirtz (ALE)','Warren Zaïre-Emery (FRA)',
                'Endrick (BRA)','Alejandro Garnacho (ARG)','Mathys Tel (FRA)',
                'Rasmus Højlund (DIN)','Pedri (ESP)','Gavi (ESP)',
            ],
        ];
    }
}

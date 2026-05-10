<?php

namespace App\Http\Controllers;

use App\Models\MatchPrediction;
use App\Models\Quiniela;
use App\Models\SpecialEventPick;
use App\Models\User;
use App\Models\WorldMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultsController extends Controller
{
    // ── Tabla pública (sin login) ─────────────────────────────────────
    public function public()
    {
        $standings = $this->buildStandings();
        $lastMatch = WorldMatch::where('status','finished')
            ->with(['homeTeam','awayTeam'])
            ->orderByDesc('kickoff_at')->first();

        $stats = $this->buildStats();

        return view('results.public', compact('standings','lastMatch','stats'));
    }

    // ── Perfil de un jugador ──────────────────────────────────────────
    public function player(User $user)
    {
        $predictions = MatchPrediction::where('user_id', $user->id)
            ->with(['match.homeTeam','match.awayTeam'])
            ->orderByDesc('created_at')
            ->get();

        $quiniela = Quiniela::with([
            'champion','runnerUp','thirdPlace',
            'surpriseTeam','topScorerTeam','bestDefense',
        ])->where('user_id', $user->id)->first();

        $streaks  = $this->calcStreaks($predictions);
        $badges   = $this->calcBadges($predictions, $streaks);
        $byMatch  = $predictions->filter(fn($p) => $p->scored);

        // Points per matchday
        $byDay = $byMatch->groupBy(fn($p) => $p->match->matchday ?? 'KO')
            ->map(fn($g) => $g->sum('total_points'));

        $standings = $this->buildStandings();
        $rank = $standings->search(fn($r) => $r->id === $user->id);

        return view('results.player', compact(
            'user','predictions','quiniela','streaks','badges','byDay','standings','rank'
        ));
    }

    // ── Jornada completa ─────────────────────────────────────────────
    public function matchday(Request $request)
    {
        $day = $request->get('day', 1);

        $matches = WorldMatch::where('matchday', $day)
            ->where('phase','groups')
            ->with(['homeTeam','awayTeam','predictions.user'])
            ->orderBy('kickoff_at')
            ->get();

        $users = User::orderBy('name')->get();

        // Build grid: user → match → prediction
        $grid = [];
        foreach ($users as $u) {
            $grid[$u->id] = [
                'user'  => $u,
                'preds' => $matches->mapWithKeys(fn($m) => [
                    $m->id => $m->predictions->firstWhere('user_id', $u->id)
                ]),
                'day_pts' => $matches->sum(fn($m) =>
                    $m->predictions->firstWhere('user_id', $u->id)?->total_points ?? 0
                ),
            ];
        }

        // Sort by day points
        usort($grid, fn($a,$b) => $b['day_pts'] - $a['day_pts']);

        $days = WorldMatch::where('phase','groups')
            ->distinct()->orderBy('matchday')->pluck('matchday');

        return view('results.matchday', compact('matches','grid','day','days'));
    }

    // ── Helpers ───────────────────────────────────────────────────────
    private function buildStandings()
    {
        $standings = User::select('users.id','users.name','users.username')
            ->leftJoin('quinielas','quinielas.user_id','=','users.id')
            ->leftJoin(
                DB::raw('(SELECT user_id, SUM(total_points) as match_pts FROM match_predictions GROUP BY user_id) mp'),
                'mp.user_id','=','users.id'
            )
            ->leftJoin(
                DB::raw('(SELECT user_id, SUM(points_earned) as special_pts FROM special_event_picks GROUP BY user_id) sep'),
                'sep.user_id','=','users.id'
            )
            ->selectRaw('
                users.id, users.name, users.username,
                COALESCE(quinielas.total_points,0)  AS maestra_pts,
                COALESCE(mp.match_pts,0)             AS partido_pts,
                COALESCE(sep.special_pts,0)          AS special_pts,
                COALESCE(quinielas.total_points,0)+COALESCE(mp.match_pts,0)+COALESCE(sep.special_pts,0) AS grand_total
            ')
            ->orderByDesc('grand_total')
            ->get();

        $quinielas = Quiniela::with(['champion','runnerUp'])
            ->whereIn('user_id', $standings->pluck('id'))
            ->get()->keyBy('user_id');

        $standings->each(function ($row) use ($quinielas) {
            $row->quiniela = $quinielas->get($row->id);
        });

        return $standings;
    }

    private function calcStreaks($predictions): array
    {
        $scored = $predictions->filter(fn($p) => $p->scored)
            ->sortBy(fn($p) => $p->match->kickoff_at);

        $exactStreak = 0; $maxExactStreak = 0; $currentExact = 0;
        $winStreak   = 0; $maxWinStreak   = 0; $currentWin   = 0;
        $totalExact  = 0; $totalCorrect   = 0; $totalScored  = 0;
        $perfectDays = 0;

        foreach ($scored as $p) {
            $totalScored++;
            if ($p->pts_exact > 0) {
                $totalExact++;
                $currentExact++;
                $maxExactStreak = max($maxExactStreak, $currentExact);
            } else {
                $currentExact = 0;
            }
            if ($p->pts_result > 0 || $p->pts_exact > 0) {
                $totalCorrect++;
                $currentWin++;
                $maxWinStreak = max($maxWinStreak, $currentWin);
            } else {
                $currentWin = 0;
            }
        }

        // Perfect day: all predictions in a matchday correct
        $byDay = $scored->groupBy(fn($p) => $p->match->matchday ?? 'KO');
        foreach ($byDay as $day => $preds) {
            if ($preds->count() >= 2 && $preds->every(fn($p) => $p->pts_result > 0 || $p->pts_exact > 0)) {
                $perfectDays++;
            }
        }

        return [
            'current_exact'    => $currentExact,
            'max_exact'        => $maxExactStreak,
            'current_win'      => $currentWin,
            'max_win'          => $maxWinStreak,
            'total_exact'      => $totalExact,
            'total_correct'    => $totalCorrect,
            'total_scored'     => $totalScored,
            'accuracy'         => $totalScored > 0 ? round($totalCorrect / $totalScored * 100) : 0,
            'exact_rate'       => $totalScored > 0 ? round($totalExact / $totalScored * 100) : 0,
            'perfect_days'     => $perfectDays,
        ];
    }

    private function calcBadges($predictions, $streaks): array
    {
        $badges = [];
        if ($streaks['total_exact'] >= 1)       $badges[] = ['🎯','Francotirador','Primer marcador exacto'];
        if ($streaks['total_exact'] >= 5)       $badges[] = ['🔥','En Llamas','5 marcadores exactos'];
        if ($streaks['max_exact'] >= 3)         $badges[] = ['⚡','Racha Exacta','3 exactos seguidos'];
        if ($streaks['max_exact'] >= 5)         $badges[] = ['💎','Leyenda','5 exactos seguidos'];
        if ($streaks['accuracy'] >= 60)         $badges[] = ['📊','Analista','60%+ de aciertos'];
        if ($streaks['accuracy'] >= 80)         $badges[] = ['🧠','Oráculo','80%+ de aciertos'];
        if ($streaks['perfect_days'] >= 1)      $badges[] = ['⭐','Jornada Perfecta','Una jornada sin fallos'];
        if ($streaks['perfect_days'] >= 3)      $badges[] = ['🏆','Maestro','3 jornadas perfectas'];
        if ($predictions->sum('pts_exact') >= 50) $badges[] = ['💰','Millonario','50+ pts de exactos'];
        return $badges;
    }

    private function buildStats(): array
    {
        $users = User::all();
        $stats = [];

        foreach ($users as $u) {
            $preds = MatchPrediction::where('user_id',$u->id)->where('scored',true)->get();
            $streaks = $this->calcStreaks($preds);
            $stats[$u->id] = array_merge($streaks, [
                'name'     => $u->name,
                'username' => $u->username,
            ]);
        }

        return $stats;
    }
}

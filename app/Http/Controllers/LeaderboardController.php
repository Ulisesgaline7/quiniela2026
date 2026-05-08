<?php

namespace App\Http\Controllers;

use App\Models\MatchPrediction;
use App\Models\Quiniela;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Aggregate: quiniela maestra points + match prediction points
        $standings = User::select('users.id', 'users.name')
            ->leftJoin('quinielas', 'quinielas.user_id', '=', 'users.id')
            ->leftJoin(
                DB::raw('(SELECT user_id, SUM(total_points) as match_pts FROM match_predictions GROUP BY user_id) mp'),
                'mp.user_id', '=', 'users.id'
            )
            ->selectRaw('
                users.id,
                users.name,
                COALESCE(quinielas.total_points, 0)  AS maestra_pts,
                COALESCE(mp.match_pts, 0)             AS partido_pts,
                COALESCE(quinielas.total_points, 0) + COALESCE(mp.match_pts, 0) AS grand_total
            ')
            ->orderByDesc('grand_total')
            ->get();

        // Enrich with quiniela details
        $quinielas = Quiniela::with(['champion', 'runnerUp', 'thirdPlace'])
            ->whereIn('user_id', $standings->pluck('id'))
            ->get()
            ->keyBy('user_id');

        $standings->each(function ($row) use ($quinielas) {
            $row->quiniela = $quinielas->get($row->id);
        });

        // Recent match predictions (last 10 scored)
        $recentPredictions = MatchPrediction::with(['user', 'match.homeTeam', 'match.awayTeam'])
            ->where('scored', true)
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return view('leaderboard.index', compact('standings', 'recentPredictions'));
    }
}

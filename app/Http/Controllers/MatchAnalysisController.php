<?php

namespace App\Http\Controllers;

use App\Models\WorldMatch;
use App\Services\FootballApiService;
use Illuminate\Http\JsonResponse;

class MatchAnalysisController extends Controller
{
    public function __construct(private FootballApiService $api) {}

    /**
     * Return head-to-head stats and team comparison for a match.
     */
    public function compare(WorldMatch $match): JsonResponse
    {
        $match->load(['homeTeam', 'awayTeam']);

        $homeStats = $this->api->getTeamStats($match->homeTeam->short_name);
        $awayStats = $this->api->getTeamStats($match->awayTeam->short_name);

        // Historical head-to-head from local DB
        $h2h = $this->headToHead($match->home_team_id, $match->away_team_id);

        // Recent form from local DB (last 5 finished matches each team)
        $homeForm = $this->recentForm($match->home_team_id);
        $awayForm = $this->recentForm($match->away_team_id);

        return response()->json([
            'match' => [
                'id'         => $match->id,
                'home'       => $match->homeTeam->flag . ' ' . $match->homeTeam->name,
                'away'       => $match->awayTeam->flag . ' ' . $match->awayTeam->name,
                'kickoff_at' => $match->kickoff_at->format('d/m/Y H:i'),
                'group'      => $match->group_name,
                'phase'      => $match->getPhaseLabel(),
            ],
            'home_stats'  => $homeStats,
            'away_stats'  => $awayStats,
            'head_to_head' => $h2h,
            'home_form'   => $homeForm,
            'away_form'   => $awayForm,
        ]);
    }

    private function headToHead(int $homeId, int $awayId): array
    {
        $matches = WorldMatch::where(function ($q) use ($homeId, $awayId) {
            $q->where('home_team_id', $homeId)->where('away_team_id', $awayId);
        })->orWhere(function ($q) use ($homeId, $awayId) {
            $q->where('home_team_id', $awayId)->where('away_team_id', $homeId);
        })->whereNotNull('home_score')->get();

        $homeWins = 0; $awayWins = 0; $draws = 0;

        foreach ($matches as $m) {
            if ($m->home_team_id === $homeId) {
                if ($m->home_score > $m->away_score) $homeWins++;
                elseif ($m->home_score < $m->away_score) $awayWins++;
                else $draws++;
            } else {
                if ($m->away_score > $m->home_score) $homeWins++;
                elseif ($m->away_score < $m->home_score) $awayWins++;
                else $draws++;
            }
        }

        return [
            'total'     => $matches->count(),
            'home_wins' => $homeWins,
            'away_wins' => $awayWins,
            'draws'     => $draws,
        ];
    }

    private function recentForm(int $teamId): array
    {
        $matches = WorldMatch::where(function ($q) use ($teamId) {
            $q->where('home_team_id', $teamId)->orWhere('away_team_id', $teamId);
        })->whereNotNull('home_score')
          ->orderByDesc('kickoff_at')
          ->limit(5)
          ->get();

        return $matches->map(function ($m) use ($teamId) {
            $isHome = $m->home_team_id === $teamId;
            $scored   = $isHome ? $m->home_score : $m->away_score;
            $conceded = $isHome ? $m->away_score : $m->home_score;
            $result   = $scored > $conceded ? 'W' : ($scored < $conceded ? 'L' : 'D');
            return compact('result', 'scored', 'conceded');
        })->toArray();
    }
}

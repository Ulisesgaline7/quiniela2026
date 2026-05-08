<?php

namespace App\Http\Controllers;

use App\Models\MatchPrediction;
use App\Models\WorldMatch;
use App\Services\FootballApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchPredictionController extends Controller
{
    public function __construct(private FootballApiService $api) {}

    public function index(Request $request)
    {
        $phase = $request->get('phase', 'groups');
        $group = $request->get('group');

        $query = WorldMatch::with(['homeTeam', 'awayTeam'])
            ->where('phase', $phase)
            ->orderBy('kickoff_at');

        if ($group && $phase === 'groups') {
            $query->where('group_name', $group);
        }

        $matches = $query->get();

        // Attach user prediction + close status
        $userPredictions = MatchPrediction::where('user_id', Auth::id())
            ->whereIn('match_id', $matches->pluck('id'))
            ->get()->keyBy('match_id');

        $now = now();
        $matches->each(function ($match) use ($userPredictions, $now) {
            $match->userPrediction = $userPredictions->get($match->id);
            $match->is_closed = $match->closes_at && $now->gte($match->closes_at);
        });

        // Live standings from API
        $standings = [];
        if ($phase === 'groups') {
            try {
                $standings = $this->api->getStandings();
            } catch (\Exception $e) {}
        }

        $phases = [
            'groups'      => 'Fase de Grupos',
            'round_of_32' => 'Ronda de 32',
            'round_of_16' => 'Octavos',
            'quarters'    => 'Cuartos',
            'semis'       => 'Semis',
            'third_place' => 'Tercer Lugar',
            'final'       => 'Final',
        ];

        $groups = range('A', 'L');

        return view('quiniela.partidos', compact('matches', 'phase', 'phases', 'groups', 'group', 'standings'));
    }

    public function show(WorldMatch $match)
    {
        $match->load(['homeTeam', 'awayTeam']);
        $now = now();
        $match->is_closed = $match->closes_at && $now->gte($match->closes_at);

        $pred = MatchPrediction::where('user_id', Auth::id())
            ->where('match_id', $match->id)->first();

        // Head-to-head comparison
        $comparison = $this->api->getTeamComparison(
            $match->homeTeam->short_name,
            $match->awayTeam->short_name
        );

        // Live match data from API
        $liveData = null;
        if ($match->api_match_id) {
            try {
                $liveData = $this->api->getMatch($match->api_match_id);
            } catch (\Exception $e) {}
        }

        return view('quiniela.partido-detalle', compact('match', 'pred', 'comparison', 'liveData'));
    }

    public function store(Request $request, WorldMatch $match)
    {
        $now = now();
        $isClosed = $match->closes_at && $now->gte($match->closes_at);

        if ($isClosed || !$match->is_open) {
            return back()->with('error', 'Este partido ya cerró para pronósticos (1 hora antes del pitazo).');
        }

        $validated = $request->validate([
            'home_score'           => 'required|integer|min:0|max:30',
            'away_score'           => 'required|integer|min:0|max:30',
            'first_scorer_team_id' => 'nullable|exists:teams,id',
            'predict_red_card'     => 'boolean',
            'predict_extra_time'   => 'boolean',
            'predict_penalties'    => 'boolean',
        ]);

        // Auto-calculate result from scores
        $h = (int) $validated['home_score'];
        $a = (int) $validated['away_score'];
        $validated['result'] = $h > $a ? 'home' : ($h < $a ? 'away' : 'draw');
        $validated['predict_red_card']   = $request->boolean('predict_red_card');
        $validated['predict_extra_time'] = $request->boolean('predict_extra_time');
        $validated['predict_penalties']  = $request->boolean('predict_penalties');

        MatchPrediction::updateOrCreate(
            ['user_id' => Auth::id(), 'match_id' => $match->id],
            $validated
        );

        return back()->with('success', '¡Pronóstico guardado! ⚽');
    }
}

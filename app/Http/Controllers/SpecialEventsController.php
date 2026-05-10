<?php

namespace App\Http\Controllers;

use App\Models\SpecialEvent;
use App\Models\SpecialEventPick;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialEventsController extends Controller
{
    // Maestra closes 1h before inaugural match
    const CLOSES_AT = '2026-06-11 14:00:00';

    public function index()
    {
        $isClosed   = now()->gte(self::CLOSES_AT);
        $definitions = SpecialEvent::allDefinitions();
        $teams       = Team::orderBy('name')->get();

        // User's existing picks
        $myPicks = SpecialEventPick::where('user_id', Auth::id())
            ->get()->keyBy('event_type');

        // Resolved events (admin marked)
        $resolved = SpecialEvent::where('resolved', true)->get()->keyBy('type');

        return view('quiniela.especiales', compact(
            'definitions','teams','myPicks','resolved','isClosed'
        ));
    }

    public function store(Request $request)
    {
        if (now()->gte(self::CLOSES_AT)) {
            return back()->with('error', 'Los eventos especiales ya cerraron.');
        }

        $definitions = collect(SpecialEvent::allDefinitions())->keyBy('type');

        foreach ($request->picks ?? [] as $type => $data) {
            if (!isset($definitions[$type])) continue;

            $pick = SpecialEventPick::firstOrNew([
                'user_id'    => Auth::id(),
                'event_type' => $type,
            ]);

            $pick->team_id    = $data['team_id'] ?? null;
            $pick->player_name = $data['player_name'] ?? null;
            $pick->save();
        }

        return back()->with('success', '¡Eventos especiales guardados! 🎯');
    }
}

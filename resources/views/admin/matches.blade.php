@extends('layouts.app')
@section('title', 'Admin · Partidos')

@section('content')
<div class="container">

    <div class="section-hero">
        <p class="eyebrow">Panel de Administración</p>
        <h1>GESTIÓN DE<br><span>PARTIDOS</span></h1>
        <p class="subtitle">Ingresa los resultados reales para que el sistema califique automáticamente todos los pronósticos.</p>
    </div>

    @foreach($matches as $phase => $phaseMatches)
    <div class="card">
        <div class="card-header">
            <div class="card-icon green">⚽</div>
            <div><div class="card-title">{{ strtoupper($phaseMatches->first()->getPhaseLabel()) }}</div></div>
            <div class="card-pts">{{ $phaseMatches->count() }} <span>partidos</span></div>
        </div>
        <div class="card-body">
            @foreach($phaseMatches as $match)
            <form method="POST" action="{{ route('admin.matches.update', $match) }}" style="margin-bottom:0">
                @csrf @method('PATCH')
                <div class="admin-match-row">
                    <div class="admin-match-teams">
                        {{ $match->homeTeam->flag }} {{ $match->homeTeam->name }}
                        <span style="color:var(--muted); margin:0 8px">vs</span>
                        {{ $match->awayTeam->name }} {{ $match->awayTeam->flag }}
                        <div style="font-size:11px; color:var(--muted); margin-top:2px; font-family:'Oswald',sans-serif">
                            {{ $match->kickoff_at->format('d/m/Y H:i') }}
                            @if($match->group_name) · Grupo {{ $match->group_name }} @endif
                        </div>
                    </div>

                    {{-- Score --}}
                    <div class="admin-score-input">
                        <input type="number" name="home_score" min="0" max="30"
                            value="{{ $match->home_score }}" placeholder="–">
                        <span style="font-family:'Bebas Neue',sans-serif; font-size:20px; color:var(--border)">–</span>
                        <input type="number" name="away_score" min="0" max="30"
                            value="{{ $match->away_score }}" placeholder="–">
                    </div>

                    {{-- Extras --}}
                    <div style="display:flex; flex-direction:column; gap:6px; font-size:12px; font-family:'Oswald',sans-serif; color:var(--muted)">
                        <label style="display:flex; align-items:center; gap:6px; cursor:pointer; flex-direction:row; margin:0">
                            <input type="checkbox" name="had_red_card" value="1" {{ $match->had_red_card ? 'checked' : '' }} style="accent-color:var(--red)">
                            🟥 Tarjeta roja
                        </label>
                        <label style="display:flex; align-items:center; gap:6px; cursor:pointer; flex-direction:row; margin:0">
                            <input type="checkbox" name="had_extra_time" value="1" {{ $match->had_extra_time ? 'checked' : '' }} style="accent-color:var(--gold)">
                            ⏱ Prórroga
                        </label>
                        <label style="display:flex; align-items:center; gap:6px; cursor:pointer; flex-direction:row; margin:0">
                            <input type="checkbox" name="had_penalties" value="1" {{ $match->had_penalties ? 'checked' : '' }} style="accent-color:var(--accent)">
                            🎯 Penales
                        </label>
                    </div>

                    {{-- First scorer --}}
                    <div class="select-wrap" style="min-width:140px">
                        <select name="first_scorer_team_id">
                            <option value="">Primer gol</option>
                            <option value="{{ $match->homeTeam->id }}" {{ $match->first_scorer_team_id == $match->homeTeam->id ? 'selected' : '' }}>
                                {{ $match->homeTeam->flag }} {{ $match->homeTeam->short_name }}
                            </option>
                            <option value="{{ $match->awayTeam->id }}" {{ $match->first_scorer_team_id == $match->awayTeam->id ? 'selected' : '' }}>
                                {{ $match->awayTeam->flag }} {{ $match->awayTeam->short_name }}
                            </option>
                        </select>
                    </div>

                    {{-- Open/Close toggle --}}
                    <div style="display:flex; flex-direction:column; gap:6px; align-items:center">
                        <span style="font-size:10px; color:var(--muted); font-family:'Oswald',sans-serif; letter-spacing:1px">
                            {{ $match->is_open ? '🟢 ABIERTO' : '🔴 CERRADO' }}
                        </span>
                        <button type="button" onclick="toggleMatch({{ $match->id }})"
                            style="background:var(--card2); border:1px solid var(--border); border-radius:6px; color:var(--muted); font-family:'Oswald',sans-serif; font-size:11px; padding:4px 10px; cursor:pointer; letter-spacing:1px">
                            {{ $match->is_open ? 'CERRAR' : 'ABRIR' }}
                        </button>
                    </div>

                    <button type="submit" class="submit-btn" style="padding:10px 20px; font-size:14px; letter-spacing:2px">
                        GUARDAR
                    </button>
                </div>
            </form>
            @endforeach
        </div>
    </div>
    @endforeach

</div>

<form id="toggle-form" method="POST" style="display:none">@csrf</form>
@endsection

@push('scripts')
<script>
function toggleMatch(id) {
    const form = document.getElementById('toggle-form');
    form.action = '/admin/partidos/' + id + '/toggle';
    form.submit();
}
</script>
@endpush

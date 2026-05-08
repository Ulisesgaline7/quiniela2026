@extends('layouts.app')
@section('title', 'Quiniela por Partido')

@section('content')
<div class="container">

    <div class="section-hero">
        <p class="eyebrow">Antes de cada partido · Cierra al pitazo inicial</p>
        <h1>QUINIELA<br><span>POR PARTIDO</span></h1>
        <p class="subtitle">Pronostica cada encuentro con el máximo detalle. Marcador exacto, primer goleador, tarjetas, prórroga. Cada partido es una oportunidad de remontar.</p>
    </div>

    {{-- SELECTOR DE PARTIDOS --}}
    <div style="margin-bottom:28px">
        <div style="font-size:11px; letter-spacing:2px; color:var(--muted); font-family:'Oswald',sans-serif; margin-bottom:12px">
            PARTIDOS DISPONIBLES
        </div>
        <div class="match-selector">
            @forelse($openMatches as $m)
            <a href="{{ route('quiniela.partido', ['match' => $m->id]) }}"
               class="match-pill {{ $selected && $selected->id === $m->id ? 'active' : '' }}">
                {{ $m->homeTeam->flag }} {{ $m->homeTeam->short_name }}
                vs
                {{ $m->awayTeam->short_name }} {{ $m->awayTeam->flag }}
            </a>
            @empty
            <span style="font-size:13px; color:var(--muted); font-family:'Oswald',sans-serif">
                No hay partidos abiertos en este momento.
            </span>
            @endforelse
        </div>

        {{-- Partidos cerrados --}}
        @php
            $closedMatches = $matches->flatten()->where('is_open', false);
        @endphp
        @if($closedMatches->count())
        <div style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap">
            @foreach($closedMatches as $m)
            <a href="{{ route('quiniela.partido', ['match' => $m->id]) }}"
               class="match-pill locked {{ $selected && $selected->id === $m->id ? 'active' : '' }}"
               title="Cerrado">
                {{ $m->homeTeam->flag }} {{ $m->homeTeam->short_name }}
                vs
                {{ $m->awayTeam->short_name }} {{ $m->awayTeam->flag }} 🔒
            </a>
            @endforeach
        </div>
        @endif
    </div>

    @if($selected)
    {{-- PARTIDO CARD --}}
    <div class="card">
        <div class="card-body">

            {{-- Teams --}}
            <div class="match-hero">
                <div class="match-team">
                    <span class="match-flag">{{ $selected->homeTeam->flag }}</span>
                    <div class="match-team-name">{{ $selected->homeTeam->name }}</div>
                </div>
                <div>
                    <div class="match-vs">VS</div>
                    <div style="font-size:10px; color:var(--muted); text-align:center; font-family:'Oswald',sans-serif; letter-spacing:1px; margin-top:4px">
                        {{ $selected->getPhaseLabel() }}
                        @if($selected->group_name) · GRUPO {{ $selected->group_name }} @endif
                        @if($selected->matchday) · JOR {{ $selected->matchday }} @endif
                    </div>
                    <div style="font-size:11px; color:var(--muted); text-align:center; font-family:'Oswald',sans-serif; margin-top:4px">
                        {{ $selected->kickoff_at->format('d/m/Y H:i') }}
                    </div>
                </div>
                <div class="match-team">
                    <span class="match-flag">{{ $selected->awayTeam->flag }}</span>
                    <div class="match-team-name">{{ $selected->awayTeam->name }}</div>
                </div>
            </div>

            @if(!$selected->is_open)
            <div class="flash flash-error" style="border-radius:8px; margin-bottom:20px; padding:12px 16px">
                🔒 Este partido ya cerró para pronósticos.
                @if($selected->isFinished())
                    Resultado final: {{ $selected->homeTeam->short_name }} {{ $selected->home_score }} – {{ $selected->away_score }} {{ $selected->awayTeam->short_name }}
                @endif
            </div>
            @endif

            @if($prediction && $prediction->scored)
            <div class="flash flash-success" style="border-radius:8px; margin-bottom:20px; padding:12px 16px">
                ✅ Pronóstico calificado · Obtuviste <strong>{{ $prediction->total_points }} pts</strong>
                (Exacto: {{ $prediction->pts_exact }} · Resultado: {{ $prediction->pts_result }} · Diferencia: {{ $prediction->pts_diff }} · Bonos: {{ $prediction->pts_first_scorer + $prediction->pts_red_card + $prediction->pts_extra_time + $prediction->pts_penalties }})
            </div>
            @endif

            <form method="POST" action="{{ route('quiniela.partido.store') }}" id="partido-form">
            @csrf
            <input type="hidden" name="match_id" value="{{ $selected->id }}">

            {{-- MARCADOR EXACTO --}}
            <div style="margin-bottom:28px">
                <div style="font-family:'Oswald',sans-serif; font-size:11px; letter-spacing:2px; color:var(--gold); margin-bottom:6px; display:flex; align-items:center; justify-content:space-between">
                    <span>MARCADOR EXACTO</span>
                    <span class="pts-pill">10 pts</span>
                </div>
                <div class="score-big">
                    <div>
                        <div class="score-box">
                            <input type="number" name="home_score" min="0" max="30" placeholder="0"
                                id="score1" oninput="updateLivePoints()"
                                value="{{ old('home_score', $prediction?->home_score) }}"
                                {{ !$selected->is_open ? 'disabled' : '' }}>
                        </div>
                        <div class="pts-label">{{ $selected->homeTeam->short_name }}</div>
                    </div>
                    <div class="score-dash">–</div>
                    <div>
                        <div class="score-box">
                            <input type="number" name="away_score" min="0" max="30" placeholder="0"
                                id="score2" oninput="updateLivePoints()"
                                value="{{ old('away_score', $prediction?->away_score) }}"
                                {{ !$selected->is_open ? 'disabled' : '' }}>
                        </div>
                        <div class="pts-label">{{ $selected->awayTeam->short_name }}</div>
                    </div>
                </div>
                <div class="pts-summary">
                    <span class="pts-pill green-pill" id="live-pts-exact" style="display:none">✓ 10 pts si aciertas exacto</span>
                    <span class="pts-pill" id="live-pts-winner" style="display:none">→ 4 pts si aciertas ganador</span>
                    <span class="pts-pill" id="live-pts-diff" style="display:none">→ 6 pts si aciertas diferencia</span>
                </div>
            </div>

            {{-- RESULTADO --}}
            <div style="margin-bottom:24px">
                <div style="font-family:'Oswald',sans-serif; font-size:11px; letter-spacing:2px; color:var(--gold); margin-bottom:10px; display:flex; align-items:center; justify-content:space-between">
                    <span>RESULTADO (si no aciertas el marcador)</span>
                    <span class="pts-pill">4 pts</span>
                </div>
                <div class="result-btns" id="result-btns">
                    @foreach([
                        ['val'=>'home',  'label'=>'🏠 Gana ' . $selected->homeTeam->short_name],
                        ['val'=>'draw',  'label'=>'🤝 Empate'],
                        ['val'=>'away',  'label'=>'✈️ Gana ' . $selected->awayTeam->short_name],
                    ] as $btn)
                    <button type="button"
                        class="scorer-btn {{ old('result', $prediction?->result ?? 'home') === $btn['val'] ? 'selected' : '' }}"
                        onclick="selectResult(this, '{{ $btn['val'] }}')"
                        {{ !$selected->is_open ? 'disabled' : '' }}>
                        {{ $btn['label'] }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="result" id="result-input"
                    value="{{ old('result', $prediction?->result ?? 'home') }}">
                <p style="font-size:11px; color:var(--muted); margin-top:8px; font-family:'Oswald',sans-serif; letter-spacing:.5px">
                    Se otorga solo si no acertaste el marcador exacto
                </p>
            </div>

            {{-- DIFERENCIA --}}
            <div style="margin-bottom:24px">
                <div style="font-family:'Oswald',sans-serif; font-size:11px; letter-spacing:2px; color:var(--gold); margin-bottom:10px; display:flex; align-items:center; justify-content:space-between">
                    <span>DIFERENCIA DE GOLES EXACTA</span>
                    <span class="pts-pill">6 pts</span>
                </div>
                <p style="font-size:12px; color:var(--muted); font-family:'Oswald',sans-serif; letter-spacing:.5px">
                    Se calcula automáticamente de tu marcador. Si pronosticas 2-1 y el resultado es 3-2, aciertas la diferencia (+1 gol).
                </p>
                <div style="margin-top:10px; padding:10px 14px; background:var(--card2); border-radius:8px; border:1px solid var(--border); font-family:'Bebas Neue',sans-serif; font-size:18px; color:var(--accent); display:flex; align-items:center; gap:10px">
                    <span>TU DIFERENCIA:</span>
                    <span id="diff-display">–</span>
                    <span style="font-size:12px; color:var(--muted); font-family:'DM Sans',sans-serif; font-weight:400">(se actualiza al llenar el marcador)</span>
                </div>
            </div>

            {{-- BONOS --}}
            <div>
                <div style="font-family:'Oswald',sans-serif; font-size:11px; letter-spacing:2px; color:var(--accent); margin-bottom:12px">PUNTOS BONO</div>

                {{-- Primer goleador --}}
                <div class="bonus-row">
                    <label class="toggle">
                        <input type="checkbox" id="toggle-scorer"
                            {{ old('first_scorer_team_id', $prediction?->first_scorer_team_id) ? 'checked' : '' }}
                            {{ !$selected->is_open ? 'disabled' : '' }}>
                        <div class="toggle-track"></div>
                    </label>
                    <div class="bonus-label"><strong>⚽ ¿Quién anota primero?</strong></div>
                    <div class="select-wrap" style="min-width:160px">
                        <select name="first_scorer_team_id" {{ !$selected->is_open ? 'disabled' : '' }}>
                            <option value="">— País —</option>
                            <option value="{{ $selected->homeTeam->id }}"
                                {{ old('first_scorer_team_id', $prediction?->first_scorer_team_id) == $selected->homeTeam->id ? 'selected' : '' }}>
                                {{ $selected->homeTeam->flag }} {{ $selected->homeTeam->name }}
                            </option>
                            <option value="{{ $selected->awayTeam->id }}"
                                {{ old('first_scorer_team_id', $prediction?->first_scorer_team_id) == $selected->awayTeam->id ? 'selected' : '' }}>
                                {{ $selected->awayTeam->flag }} {{ $selected->awayTeam->name }}
                            </option>
                        </select>
                    </div>
                    <span class="bonus-pts">+3 pts</span>
                </div>

                {{-- Tarjeta roja --}}
                <div class="bonus-row">
                    <label class="toggle">
                        <input type="checkbox" name="predict_red_card" value="1"
                            {{ old('predict_red_card', $prediction?->predict_red_card) ? 'checked' : '' }}
                            {{ !$selected->is_open ? 'disabled' : '' }}>
                        <div class="toggle-track"></div>
                    </label>
                    <div class="bonus-label">
                        <strong>🟥 Tarjeta roja en el partido</strong>¿Habrá al menos una expulsión?
                    </div>
                    <span class="bonus-pts">+2 pts</span>
                </div>

                {{-- Prórroga --}}
                <div class="bonus-row">
                    <label class="toggle">
                        <input type="checkbox" name="predict_extra_time" value="1"
                            id="ot-toggle"
                            {{ old('predict_extra_time', $prediction?->predict_extra_time) ? 'checked' : '' }}
                            onchange="toggleOT()"
                            {{ !$selected->is_open ? 'disabled' : '' }}>
                        <div class="toggle-track"></div>
                    </label>
                    <div class="bonus-label">
                        <strong>⏱ ¿Habrá prórroga?</strong>
                        <span style="font-size:11px; color:var(--muted)">(solo en fase eliminatoria)</span>
                    </div>
                    <span class="bonus-pts">+4 pts</span>
                </div>

                {{-- Penales --}}
                <div class="bonus-row" id="penales-row"
                    style="display:{{ old('predict_extra_time', $prediction?->predict_extra_time) ? 'flex' : 'none' }}; padding-left:24px">
                    <label class="toggle">
                        <input type="checkbox" name="predict_penalties" value="1"
                            {{ old('predict_penalties', $prediction?->predict_penalties) ? 'checked' : '' }}
                            {{ !$selected->is_open ? 'disabled' : '' }}>
                        <div class="toggle-track"></div>
                    </label>
                    <div class="bonus-label"><strong>🎯 ¿Y además habrá penales?</strong></div>
                    <span class="bonus-pts">+3 pts</span>
                </div>
            </div>

            @if($selected->is_open)
            <div class="submit-area">
                <button type="submit" class="submit-btn green">CONFIRMAR PRONÓSTICO</button>
                <p class="submit-note">⏳ El formulario cierra al pitazo inicial · Podrás modificar hasta ese momento</p>
            </div>
            @endif

            </form>
        </div>
    </div>

    {{-- PUNTOS POTENCIALES --}}
    <div class="card" style="border-color: rgba(59,255,160,0.3)">
        <div class="card-body" style="padding:18px 24px">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap">
                <div>
                    <div style="font-size:11px; letter-spacing:3px; color:var(--muted); font-family:'Oswald',sans-serif">PUNTOS EN JUEGO · ESTE PARTIDO</div>
                    <div style="font-family:'Bebas Neue',sans-serif; font-size:44px; line-height:1; color:var(--accent)">hasta 22 <span style="font-size:18px; color:var(--muted)">pts</span></div>
                </div>
                <div style="font-size:12px; color:var(--muted); font-family:'Oswald',sans-serif; letter-spacing:.5px; max-width:260px; line-height:1.6">
                    Marcador exacto (10) + Ganador (4) + Diferencia (6) + Primer gol (3) + Tarjeta roja (2) + Prórroga (4) + Penales (3)
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="card">
        <div class="card-body" style="text-align:center; padding:48px 24px">
            <div style="font-size:48px; margin-bottom:16px">⚽</div>
            <div style="font-family:'Oswald',sans-serif; font-size:16px; color:var(--muted); letter-spacing:1px">
                No hay partidos disponibles en este momento.<br>
                Vuelve cuando se acerque el torneo.
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function updateLivePoints() {
    const s1 = document.getElementById('score1')?.value;
    const s2 = document.getElementById('score2')?.value;
    const diffEl = document.getElementById('diff-display');
    if (s1 !== '' && s2 !== '' && s1 !== undefined) {
        const v1 = parseInt(s1), v2 = parseInt(s2);
        const diff = v1 - v2;
        diffEl.textContent = diff === 0 ? 'Empate (0)' : (diff > 0 ? '+' : '') + diff + ' goles';
        document.getElementById('live-pts-exact').style.display = 'inline-flex';
        document.getElementById('live-pts-winner').style.display = 'inline-flex';
        document.getElementById('live-pts-diff').style.display = 'inline-flex';
    } else {
        if (diffEl) diffEl.textContent = '–';
        ['live-pts-exact','live-pts-winner','live-pts-diff'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
    }
}

function toggleOT() {
    const checked = document.getElementById('ot-toggle')?.checked;
    const row = document.getElementById('penales-row');
    if (row) row.style.display = checked ? 'flex' : 'none';
}

function selectResult(btn, val) {
    document.querySelectorAll('#result-btns .scorer-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('result-input').value = val;
}

document.addEventListener('DOMContentLoaded', () => {
    updateLivePoints();
});
</script>
@endpush

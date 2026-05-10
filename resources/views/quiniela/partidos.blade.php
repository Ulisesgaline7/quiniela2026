@extends('layouts.app')
@section('title','Partidos')
@section('content')

<div class="page-hero" style="padding:32px 0 28px;margin-bottom:28px">
  <div class="hero-eyebrow">Antes de cada partido · Cierra 1 hora antes del pitazo</div>
  <h1 class="hero-title" style="font-size:clamp(40px,8vw,72px)"><span class="outline">QUINIELA</span> <span class="fill">PARTIDOS</span></h1>
</div>

{{-- PHASE NAV --}}
<div class="phase-nav">
  @foreach($phases as $key => $label)
  <a href="{{ route('quiniela.partidos',['phase'=>$key]) }}" class="phase-pill {{ $phase===$key?'active':'' }}">{{ $label }}</a>
  @endforeach
</div>

{{-- GROUP FILTER --}}
@if($phase === 'groups')
<div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:20px">
  <a href="{{ route('quiniela.partidos',['phase'=>'groups']) }}" class="phase-pill {{ !$group?'active':'' }}" style="font-size:10px">Todos</a>
  @foreach($groups as $g)
  <a href="{{ route('quiniela.partidos',['phase'=>'groups','group'=>$g]) }}" class="phase-pill {{ $group===$g?'active':'' }}" style="font-size:10px">Grupo {{ $g }}</a>
  @endforeach
</div>
@endif

@forelse($matches as $match)
@php $pred = $match->userPrediction; $closed = $match->is_closed; @endphp
<div class="card">
  <div class="card-body">

    {{-- MATCH HEADER --}}
    <div class="match-header">
      <div class="match-team">
        <span class="match-flag">{{ $match->homeTeam->flag }}</span>
        <div class="match-team-name">{{ $match->homeTeam->name }}</div>
        <div style="font-size:10px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px">FIFA #{{ $match->homeTeam->fifa_ranking }}</div>
      </div>
      <div class="match-center">
        @if($match->isFinished())
          <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:36px;color:var(--gold)">
            {{ $match->home_score }} – {{ $match->away_score }}
          </div>
          <div class="match-meta">Resultado Final</div>
        @else
          <div class="match-vs">VS</div>
          <div class="match-meta">
            {{ $match->group_name ? 'GRP '.$match->group_name.' · J'.$match->matchday : $match->getPhaseLabel() }}
          </div>
        @endif
        <div class="match-meta" style="margin-top:4px">{{ $match->kickoff_at->format('d M · H:i') }}</div>
        @if($match->city)
        <div class="match-meta">📍 {{ $match->city }}</div>
        @endif
        @if($closed && !$match->isFinished())
        <div style="margin-top:6px"><span class="closed-badge">🔒 Cerrado</span></div>
        @endif
      </div>
      <div class="match-team">
        <span class="match-flag">{{ $match->awayTeam->flag }}</span>
        <div class="match-team-name">{{ $match->awayTeam->name }}</div>
        <div style="font-size:10px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px">FIFA #{{ $match->awayTeam->fifa_ranking }}</div>
      </div>
    </div>

    {{-- LINK TO DETAIL --}}
    <div style="text-align:center;margin-bottom:16px">
      <a href="{{ route('quiniela.partidos.show',$match) }}" class="btn btn-outline btn-sm">
        📊 Ver Análisis & Pronóstico Detallado
      </a>
    </div>

    @if(!$closed && $match->is_open && !$match->isFinished())
    {{-- QUICK PREDICTION FORM --}}
    <form method="POST" action="{{ route('quiniela.partidos.store',$match) }}" id="form-{{ $match->id }}">
    @csrf
    <div class="divider">Pronóstico Rápido</div>

    {{-- SCOREBOARD INPUT --}}
    <div class="scoreboard" style="margin:0 auto 16px">
      <div class="sb-team">
        <span style="font-size:22px">{{ $match->homeTeam->flag }}</span>
        {{ $match->homeTeam->short_name }}
      </div>
      <div class="sb-score">
        <input type="number" name="home_score" min="0" max="30" placeholder="0"
          value="{{ old('home_score',$pred?->home_score) }}"
          oninput="autoResult({{ $match->id }})" required>
        <span class="sb-sep">–</span>
        <input type="number" name="away_score" min="0" max="30" placeholder="0"
          value="{{ old('away_score',$pred?->away_score) }}"
          oninput="autoResult({{ $match->id }})" required>
      </div>
      <div class="sb-team right">
        {{ $match->awayTeam->short_name }}
        <span style="font-size:22px">{{ $match->awayTeam->flag }}</span>
      </div>
    </div>

    {{-- AUTO RESULT --}}
    <input type="hidden" name="result" id="result-{{ $match->id }}" value="{{ old('result',$pred?->result ?? 'home') }}">
    <div class="result-btns" id="rbtn-{{ $match->id }}" style="margin-bottom:16px">
      @php $savedResult = old('result',$pred?->result ?? 'home'); @endphp
      <button type="button" class="result-btn {{ $savedResult==='home'?'selected':'' }}" onclick="setResult({{ $match->id }},'home',this)">
        🏠 {{ $match->homeTeam->short_name }}
      </button>
      <button type="button" class="result-btn {{ $savedResult==='draw'?'selected':'' }}" onclick="setResult({{ $match->id }},'draw',this)">
        🤝 Empate
      </button>
      <button type="button" class="result-btn {{ $savedResult==='away'?'selected':'' }}" onclick="setResult({{ $match->id }},'away',this)">
        ✈️ {{ $match->awayTeam->short_name }}
      </button>
    </div>

    {{-- BONOS --}}
    <div style="font-family:'Barlow Condensed',sans-serif;font-size:10px;letter-spacing:2px;color:var(--teal);margin-bottom:8px;text-transform:uppercase">Puntos Bono</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:16px">
      @foreach([
        ['predict_red_card','🟥 Tarjeta Roja',2],
        ['predict_both_score','⚽⚽ Ambos Anotan',2],
        ['predict_over3','🔥 Más de 3 Goles',2],
        ['predict_penalty_in_game','🎯 Penal en el Partido',2],
        ['predict_stoppage_goal','⏱ Gol en Agregado',3],
      ] as [$field,$label,$pts])
      <div class="bonus-row" style="padding:8px;background:var(--card2);border-radius:4px;border:1px solid var(--border)">
        <label class="toggle"><input type="checkbox" name="{{ $field }}" value="1" {{ $pred?->$field?'checked':'' }}><div class="toggle-track"></div></label>
        <div class="bonus-label"><strong>{{ $label }}</strong></div>
        <span class="bonus-pts">+{{ $pts }}</span>
      </div>
      @endforeach
      @if(in_array($match->phase,['round_of_32','round_of_16','quarters','semis','third_place','final']))
      <div class="bonus-row" style="padding:8px;background:var(--card2);border-radius:4px;border:1px solid var(--border)">
        <label class="toggle"><input type="checkbox" name="predict_extra_time" value="1" {{ $pred?->predict_extra_time?'checked':'' }}><div class="toggle-track"></div></label>
        <div class="bonus-label"><strong>⏱ Prórroga</strong></div>
        <span class="bonus-pts">+5</span>
      </div>
      <div class="bonus-row" style="padding:8px;background:var(--card2);border-radius:4px;border:1px solid var(--border)">
        <label class="toggle"><input type="checkbox" name="predict_penalties" value="1" {{ $pred?->predict_penalties?'checked':'' }}><div class="toggle-track"></div></label>
        <div class="bonus-label"><strong>🎯 Penales</strong></div>
        <span class="bonus-pts">+4</span>
      </div>
      @endif
    </div>

    @if($pred)
    <div style="font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;margin-bottom:10px;text-align:center">
      ✏️ Última modificación: {{ $pred->updated_at->format('d/m H:i') }}
      @if($pred->scored) · <span style="color:var(--teal)">✓ {{ $pred->total_points }} pts</span> @endif
    </div>
    @endif

    <div style="text-align:center">
      <button type="submit" class="btn btn-teal btn-sm">{{ $pred ? '↻ Actualizar':'✓ Confirmar' }} Pronóstico</button>
    </div>
    </form>

    @elseif($match->isFinished() && $pred)
    {{-- RESULTADO PUNTUADO --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:8px">
      <div style="background:var(--card2);border:1px solid var(--border);border-radius:5px;padding:14px;text-align:center">
        <div style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;margin-bottom:6px;text-transform:uppercase">Tu Pronóstico</div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:34px;color:var(--white)">{{ $pred->home_score }} – {{ $pred->away_score }}</div>
      </div>
      <div style="background:var(--card2);border:1px solid {{ $pred->scored?'rgba(0,184,169,.3)':'var(--border)' }};border-radius:5px;padding:14px;text-align:center">
        <div style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;margin-bottom:6px;text-transform:uppercase">{{ $pred->scored?'Puntos':'Pendiente' }}</div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:34px;color:{{ $pred->scored?'var(--teal)':'var(--muted)' }}">
          {{ $pred->scored ? '+'.$pred->total_points : '–' }}
        </div>
      </div>
    </div>
    @if($pred->scored)
    <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:10px;justify-content:center">
      @if($pred->pts_exact)    <span class="pill pill-teal">Exacto +{{ $pred->pts_exact }}</span> @endif
      @if($pred->pts_result)   <span class="pill pill-gold">Ganador +{{ $pred->pts_result }}</span> @endif
      @if($pred->pts_diff)     <span class="pill pill-gold">Diferencia +{{ $pred->pts_diff }}</span> @endif
      @if($pred->pts_red_card) <span class="pill pill-coral">Tarjeta +{{ $pred->pts_red_card }}</span> @endif
      @if($pred->pts_extra_time) <span class="pill pill-lime">Prórroga +{{ $pred->pts_extra_time }}</span> @endif
      @if($pred->pts_penalties)  <span class="pill pill-lime">Penales +{{ $pred->pts_penalties }}</span> @endif
    </div>
    @endif

    @elseif($closed)
    <div style="text-align:center;padding:16px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;font-size:13px">
      {{ $pred ? '✓ Pronóstico enviado' : 'No enviaste pronóstico para este partido' }}
    </div>
    @endif

  </div>
</div>
@empty
<div class="card">
  <div class="card-body" style="text-align:center;padding:40px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px">
    No hay partidos disponibles en esta fase todavía.
  </div>
</div>
@endforelse

@endsection
@push('scripts')
<script>
function autoResult(id) {
  const h = parseInt(document.querySelector(`#form-${id} input[name="home_score"]`).value);
  const a = parseInt(document.querySelector(`#form-${id} input[name="away_score"]`).value);
  if (!isNaN(h) && !isNaN(a)) {
    const r = h > a ? 'home' : (h < a ? 'away' : 'draw');
    setResultSilent(id, r);
  }
}
function setResultSilent(id, val) {
  document.getElementById(`result-${id}`).value = val;
  document.querySelectorAll(`#rbtn-${id} .result-btn`).forEach(b => b.classList.remove('selected'));
  const map = { home: 0, draw: 1, away: 2 };
  const btns = document.querySelectorAll(`#rbtn-${id} .result-btn`);
  if (btns[map[val]]) btns[map[val]].classList.add('selected');
}
function setResult(id, val, btn) {
  document.getElementById(`result-${id}`).value = val;
  document.querySelectorAll(`#rbtn-${id} .result-btn`).forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
}
</script>
@endpush

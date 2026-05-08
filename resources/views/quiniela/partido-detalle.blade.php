@extends('layouts.app')
@section('title', $match->homeTeam->short_name . ' vs ' . $match->awayTeam->short_name)
@section('content')

@php
  $t1 = $comparison['team1'] ?? [];
  $t2 = $comparison['team2'] ?? [];
  $closed = $match->is_closed;
@endphp

{{-- POSTER-STYLE MATCH HEADER --}}
<div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#141414 0%,#1a1a1a 100%);border:1px solid var(--border);border-radius:8px;padding:32px 24px;margin-bottom:20px;text-align:center">
  {{-- Background "26" --}}
  <div style="position:absolute;font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:220px;line-height:1;color:rgba(201,168,76,.04);top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;letter-spacing:-8px">26</div>

  {{-- Stripe top --}}
  <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--coral),var(--gold),var(--teal))"></div>

  <div style="font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:10px;letter-spacing:4px;color:var(--teal);text-transform:uppercase;margin-bottom:16px">
    {{ $match->group_name ? 'GRUPO '.$match->group_name.' · JORNADA '.$match->matchday : $match->getPhaseLabel() }}
    · {{ $match->kickoff_at->format('d M Y · H:i') }}
    @if($match->venue) · {{ $match->venue }} @endif
  </div>

  <div style="display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:20px;max-width:600px;margin:0 auto">
    <div style="text-align:center">
      <div style="font-size:64px;margin-bottom:8px">{{ $match->homeTeam->flag }}</div>
      <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:22px;letter-spacing:2px;color:var(--white);text-transform:uppercase">{{ $match->homeTeam->name }}</div>
      <div style="font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px">FIFA #{{ $match->homeTeam->fifa_ranking }}</div>
    </div>
    <div style="text-align:center">
      @if($match->isFinished())
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:52px;color:var(--gold);line-height:1">{{ $match->home_score }}–{{ $match->away_score }}</div>
        <div style="font-size:10px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:2px;margin-top:4px;text-transform:uppercase">Final</div>
      @else
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:40px;color:rgba(201,168,76,.3);letter-spacing:6px">VS</div>
        @if($closed)
        <div style="margin-top:8px"><span class="closed-badge">🔒 Cerrado</span></div>
        @else
        <div style="font-size:11px;color:var(--teal);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;margin-top:6px">
          Cierra {{ $match->closes_at?->format('d M H:i') }}
        </div>
        @endif
      @endif
    </div>
    <div style="text-align:center">
      <div style="font-size:64px;margin-bottom:8px">{{ $match->awayTeam->flag }}</div>
      <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:22px;letter-spacing:2px;color:var(--white);text-transform:uppercase">{{ $match->awayTeam->name }}</div>
      <div style="font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px">FIFA #{{ $match->awayTeam->fifa_ranking }}</div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">

{{-- ── ANÁLISIS COMPARATIVO ── --}}
<div class="card" style="margin-bottom:0">
  <div class="card-header">
    <div class="card-icon ci-teal">📊</div>
    <div class="card-title">Análisis Comparativo</div>
  </div>
  <div class="card-body" style="padding:16px">

    @if(!empty($t1) && !empty($t2))
    {{-- Team headers --}}
    <div style="display:grid;grid-template-columns:1fr auto 1fr;gap:8px;margin-bottom:16px;text-align:center">
      <div>
        <div style="font-size:28px">{{ $match->homeTeam->flag }}</div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:12px;letter-spacing:1px;color:var(--gold)">{{ $match->homeTeam->short_name }}</div>
      </div>
      <div style="font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:10px;letter-spacing:2px;color:var(--muted);display:flex;align-items:center">VS</div>
      <div>
        <div style="font-size:28px">{{ $match->awayTeam->flag }}</div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:12px;letter-spacing:1px;color:var(--teal)">{{ $match->awayTeam->short_name }}</div>
      </div>
    </div>

    @php
    $stats = [
      ['label'=>'Mundiales', 'v1'=>$t1['world_cups']??0, 'v2'=>$t2['world_cups']??0, 'max'=>22],
      ['label'=>'Títulos', 'v1'=>$t1['titles']??0, 'v2'=>$t2['titles']??0, 'max'=>5],
      ['label'=>'% Victorias', 'v1'=>$t1['win_rate']??0, 'v2'=>$t2['win_rate']??0, 'max'=>100, 'suffix'=>'%'],
      ['label'=>'Goles/Partido', 'v1'=>$t1['avg_goals_scored']??0, 'v2'=>$t2['avg_goals_scored']??0, 'max'=>3],
      ['label'=>'Ranking FIFA', 'v1'=>$t1['fifa_rank']??50, 'v2'=>$t2['fifa_rank']??50, 'max'=>100, 'invert'=>true],
    ];
    @endphp

    @foreach($stats as $stat)
    @php
      $max = $stat['max'];
      $v1 = $stat['v1']; $v2 = $stat['v2'];
      $invert = $stat['invert'] ?? false;
      $w1 = $max > 0 ? min(100, round(($invert ? ($max-$v1+1) : $v1) / $max * 100)) : 0;
      $w2 = $max > 0 ? min(100, round(($invert ? ($max-$v2+1) : $v2) / $max * 100)) : 0;
      $suffix = $stat['suffix'] ?? '';
    @endphp
    <div style="margin-bottom:12px">
      <div style="display:grid;grid-template-columns:1fr auto 1fr;gap:8px;align-items:center;margin-bottom:4px">
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:18px;color:var(--gold)">{{ $v1 }}{{ $suffix }}</div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:9px;letter-spacing:2px;color:var(--muted);text-align:center;text-transform:uppercase">{{ $stat['label'] }}</div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:18px;color:var(--teal);text-align:right">{{ $v2 }}{{ $suffix }}</div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 4px 1fr;gap:4px">
        <div style="height:4px;background:var(--card2);border-radius:2px;overflow:hidden">
          <div style="height:100%;width:{{ $w1 }}%;background:var(--gold);border-radius:2px;float:right"></div>
        </div>
        <div></div>
        <div style="height:4px;background:var(--card2);border-radius:2px;overflow:hidden">
          <div style="height:100%;width:{{ $w2 }}%;background:var(--teal);border-radius:2px"></div>
        </div>
      </div>
    </div>
    @endforeach

    {{-- Star players --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
      <div style="background:rgba(201,168,76,.06);border:1px solid rgba(201,168,76,.15);border-radius:4px;padding:10px">
        <div style="font-size:9px;letter-spacing:2px;color:var(--gold);font-family:'Barlow Condensed',sans-serif;margin-bottom:4px;text-transform:uppercase">⭐ Estrella</div>
        <div style="font-size:13px;font-weight:600;color:var(--white)">{{ $t1['star_player'] ?? '—' }}</div>
      </div>
      <div style="background:rgba(0,184,169,.06);border:1px solid rgba(0,184,169,.15);border-radius:4px;padding:10px">
        <div style="font-size:9px;letter-spacing:2px;color:var(--teal);font-family:'Barlow Condensed',sans-serif;margin-bottom:4px;text-transform:uppercase">⭐ Estrella</div>
        <div style="font-size:13px;font-weight:600;color:var(--white)">{{ $t2['star_player'] ?? '—' }}</div>
      </div>
    </div>

    {{-- Form --}}
    @if(!empty($t1['form']) || !empty($t2['form']))
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:12px">
      @foreach([[$t1,'gold'],[$t2,'teal']] as [$team,$color])
      <div>
        <div style="font-size:9px;letter-spacing:2px;color:var(--{{ $color }});font-family:'Barlow Condensed',sans-serif;margin-bottom:6px;text-transform:uppercase">Forma Reciente</div>
        <div style="display:flex;gap:4px">
          @foreach(array_slice($team['form'] ?? [], 0, 5) as $r)
          <div style="width:22px;height:22px;border-radius:3px;display:flex;align-items:center;justify-content:center;font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:11px;
            background:{{ $r==='W'?'rgba(0,184,169,.2)':($r==='D'?'rgba(201,168,76,.2)':'rgba(255,77,61,.2)') }};
            color:{{ $r==='W'?'var(--teal)':($r==='D'?'var(--gold)':'var(--coral)') }}">
            {{ $r }}
          </div>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>
    @endif

    @else
    <div style="text-align:center;padding:20px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;font-size:13px">
      Datos de comparativa no disponibles
    </div>
    @endif
  </div>
</div>

{{-- ── PRONÓSTICO ── --}}
<div class="card" style="margin-bottom:0">
  <div class="card-header">
    <div class="card-icon ci-gold">⚽</div>
    <div class="card-title">Tu Pronóstico</div>
    <div class="card-pts">hasta 22 <small>pts</small></div>
  </div>
  <div class="card-body" style="padding:16px">

    @if(!$closed && $match->is_open && !$match->isFinished())
    <form method="POST" action="{{ route('quiniela.partidos.store',$match) }}" id="detail-form">
    @csrf

    {{-- SCOREBOARD --}}
    <div class="scoreboard" style="margin:0 auto 14px">
      <div class="sb-team">
        <span style="font-size:20px">{{ $match->homeTeam->flag }}</span>
        {{ $match->homeTeam->short_name }}
      </div>
      <div class="sb-score">
        <input type="number" name="home_score" min="0" max="30" placeholder="0"
          value="{{ old('home_score',$pred?->home_score) }}"
          oninput="autoResultDetail()" required>
        <span class="sb-sep">–</span>
        <input type="number" name="away_score" min="0" max="30" placeholder="0"
          value="{{ old('away_score',$pred?->away_score) }}"
          oninput="autoResultDetail()" required>
      </div>
      <div class="sb-team right">
        {{ $match->awayTeam->short_name }}
        <span style="font-size:20px">{{ $match->awayTeam->flag }}</span>
      </div>
    </div>

    <input type="hidden" name="result" id="detail-result" value="{{ old('result',$pred?->result ?? 'home') }}">
    <div class="result-btns" id="detail-rbtn" style="margin-bottom:14px">
      @php $sr = old('result',$pred?->result ?? 'home'); @endphp
      <button type="button" class="result-btn {{ $sr==='home'?'selected':'' }}" onclick="setDetailResult('home',this)">🏠 {{ $match->homeTeam->short_name }}</button>
      <button type="button" class="result-btn {{ $sr==='draw'?'selected':'' }}" onclick="setDetailResult('draw',this)">🤝 Empate</button>
      <button type="button" class="result-btn {{ $sr==='away'?'selected':'' }}" onclick="setDetailResult('away',this)">✈️ {{ $match->awayTeam->short_name }}</button>
    </div>

    {{-- BONOS --}}
    <div class="bonus-row">
      <label class="toggle"><input type="checkbox" name="predict_red_card" value="1" {{ $pred?->predict_red_card?'checked':'' }}><div class="toggle-track"></div></label>
      <div class="bonus-label"><strong>🟥 Tarjeta Roja</strong>¿Habrá expulsión?</div>
      <span class="bonus-pts">+2</span>
    </div>

    @if(in_array($match->phase,['round_of_32','round_of_16','quarters','semis','third_place','final']))
    <div class="bonus-row">
      <label class="toggle"><input type="checkbox" name="predict_extra_time" value="1" id="ot-chk" {{ $pred?->predict_extra_time?'checked':'' }} onchange="togglePen()"><div class="toggle-track"></div></label>
      <div class="bonus-label"><strong>⏱ Prórroga</strong></div>
      <span class="bonus-pts">+4</span>
    </div>
    <div class="bonus-row" id="pen-row" style="{{ $pred?->predict_extra_time?'':'display:none' }};padding-left:20px">
      <label class="toggle"><input type="checkbox" name="predict_penalties" value="1" {{ $pred?->predict_penalties?'checked':'' }}><div class="toggle-track"></div></label>
      <div class="bonus-label"><strong>🎯 Penales</strong></div>
      <span class="bonus-pts">+3</span>
    </div>
    @endif

    {{-- Primer goleador --}}
    <div class="bonus-row">
      <div class="bonus-label"><strong>⚽ Primer Goleador</strong>¿Qué equipo anota primero?</div>
      <div class="sel-wrap" style="min-width:140px">
        <select name="first_scorer_team_id">
          <option value="">— País —</option>
          <option value="{{ $match->homeTeam->id }}" {{ $pred?->first_scorer_team_id == $match->homeTeam->id?'selected':'' }}>{{ $match->homeTeam->flag }} {{ $match->homeTeam->name }}</option>
          <option value="{{ $match->awayTeam->id }}" {{ $pred?->first_scorer_team_id == $match->awayTeam->id?'selected':'' }}>{{ $match->awayTeam->flag }} {{ $match->awayTeam->name }}</option>
        </select>
      </div>
      <span class="bonus-pts">+3</span>
    </div>

    @if($pred)
    <div style="font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;margin:10px 0;text-align:center">
      ✏️ Última modificación: {{ $pred->updated_at->format('d/m H:i') }}
    </div>
    @endif

    <div style="text-align:center;margin-top:14px">
      <button type="submit" class="btn btn-teal">{{ $pred?'↻ Actualizar':'✓ Confirmar' }} Pronóstico</button>
      <p class="submit-note">Cierra {{ $match->closes_at?->format('d M H:i') }}</p>
    </div>
    </form>

    @elseif($match->isFinished() && $pred)
    <div style="text-align:center;padding:10px 0">
      <div style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;margin-bottom:6px;text-transform:uppercase">Tu Pronóstico</div>
      <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:44px;color:var(--white)">{{ $pred->home_score }} – {{ $pred->away_score }}</div>
      @if($pred->scored)
      <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:36px;color:var(--teal);margin-top:8px">+{{ $pred->total_points }} pts</div>
      <div style="display:flex;gap:6px;flex-wrap:wrap;justify-content:center;margin-top:10px">
        @if($pred->pts_exact)    <span class="pill pill-teal">Exacto +{{ $pred->pts_exact }}</span> @endif
        @if($pred->pts_result)   <span class="pill pill-gold">Ganador +{{ $pred->pts_result }}</span> @endif
        @if($pred->pts_diff)     <span class="pill pill-gold">Diferencia +{{ $pred->pts_diff }}</span> @endif
        @if($pred->pts_first_scorer) <span class="pill pill-lime">1er gol +{{ $pred->pts_first_scorer }}</span> @endif
        @if($pred->pts_red_card) <span class="pill pill-coral">Tarjeta +{{ $pred->pts_red_card }}</span> @endif
        @if($pred->pts_extra_time) <span class="pill pill-lime">Prórroga +{{ $pred->pts_extra_time }}</span> @endif
        @if($pred->pts_penalties)  <span class="pill pill-lime">Penales +{{ $pred->pts_penalties }}</span> @endif
      </div>
      @endif
    </div>
    @else
    <div style="text-align:center;padding:30px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;font-size:13px">
      {{ $closed ? '🔒 Pronósticos cerrados' : 'Partido no disponible' }}
    </div>
    @endif
  </div>
</div>

</div>{{-- end grid --}}

<div style="margin-top:12px">
  <a href="{{ route('quiniela.partidos',['phase'=>$match->phase,'group'=>$match->group_name]) }}" class="btn btn-outline btn-sm">← Volver a Partidos</a>
</div>

@endsection
@push('scripts')
<script>
function autoResultDetail() {
  const h = parseInt(document.querySelector('#detail-form input[name="home_score"]').value);
  const a = parseInt(document.querySelector('#detail-form input[name="away_score"]').value);
  if (!isNaN(h) && !isNaN(a)) {
    const r = h > a ? 'home' : (h < a ? 'away' : 'draw');
    document.getElementById('detail-result').value = r;
    document.querySelectorAll('#detail-rbtn .result-btn').forEach(b => b.classList.remove('selected'));
    const map = {home:0,draw:1,away:2};
    document.querySelectorAll('#detail-rbtn .result-btn')[map[r]]?.classList.add('selected');
  }
}
function setDetailResult(val, btn) {
  document.getElementById('detail-result').value = val;
  document.querySelectorAll('#detail-rbtn .result-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
}
function togglePen() {
  const row = document.getElementById('pen-row');
  if (row) row.style.display = document.getElementById('ot-chk').checked ? 'flex' : 'none';
}
</script>
@endpush

@extends('layouts.app')
@section('title','Quiniela Maestra')
@section('content')

<div class="page-hero">
  <div class="hero-mascots">🫎 🐆 🦅</div>
  <div class="hero-eyebrow">Formulario Oficial · Una sola vez · Cierra 10 Jun</div>
  <h1 class="hero-title"><span class="outline">QUINIELA</span><br><span class="fill">MAESTRA</span></h1>
  <p class="hero-sub">Completa tus predicciones antes del 10 de junio. Los puntos se revelan progresivamente — nadie puede relajarse hasta el pitazo final en MetLife.</p>
</div>

@if($isClosed && (!$quiniela || !$quiniela->submitted))
<div class="alert alert-error">🔒 La Quiniela Maestra cerró el 10 de junio. Ya no se pueden enviar predicciones.</div>
@endif

@if($quiniela && $quiniela->submitted)
{{-- ── MODO LECTURA ── --}}
<div class="alert alert-success">✓ Enviada el {{ $quiniela->submitted_at->format('d/m/Y H:i') }} · Buena suerte 🏆</div>

<div class="card">
  <div class="card-header">
    <div class="card-icon ci-gold">🏆</div>
    <div class="card-title">Tu Podio</div>
    <div class="card-pts">{{ $quiniela->points_podio }} <small>pts</small></div>
  </div>
  <div class="card-body">
    <div class="podium">
      @foreach([[$quiniela->champion,'🥇 Campeón','gold'],[$quiniela->runnerUp,'🥈 Subcampeón',''],[$quiniela->thirdPlace,'🥉 Tercer Lugar','']] as [$team,$label,$cls])
      <div class="podium-slot {{ $cls }}">
        <div class="podium-num {{ $cls === 'gold' ? 'g1':'' }}">{{ $loop->iteration }}</div>
        <div class="podium-label">{{ $label }}</div>
        <div style="font-size:28px;margin-bottom:4px">{{ $team?->flag }}</div>
        <div style="font-weight:600;color:var(--white);font-size:14px">{{ $team?->name }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div class="card-icon ci-coral">★</div>
    <div class="card-title">Premios Individuales</div>
    <div class="card-pts">{{ $quiniela->points_awards }} <small>pts</small></div>
  </div>
  <div class="card-body">
    @foreach([['🎖 Balón de Oro','golden_ball'],['👟 Bota de Oro','golden_boot'],['🧤 Guante de Oro','golden_glove'],['🌟 Mejor Joven','best_young']] as [$lbl,$field])
    <div class="input-row">
      <div class="input-label"><strong>{{ $lbl }}</strong></div>
      <span style="color:var(--white);font-size:14px">{{ $quiniela->$field }}</span>
    </div>
    @endforeach
    <div class="input-row">
      <div class="input-label"><strong>😱 País Sorpresa</strong></div>
      <span style="color:var(--white)">{{ $quiniela->surpriseTeam?->flag }} {{ $quiniela->surpriseTeam?->name }}</span>
    </div>
  </div>
</div>

<div class="card" style="border-color:rgba(201,168,76,.3)">
  <div class="card-body" style="padding:18px 20px">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
      <div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-size:10px;letter-spacing:3px;color:var(--muted);text-transform:uppercase;margin-bottom:4px">Tus Puntos Actuales</div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:56px;line-height:1;color:var(--gold)">{{ $quiniela->total_points }} <span style="font-size:18px;color:var(--muted)">pts</span></div>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <span class="pill pill-gold">Podio {{ $quiniela->points_podio }}</span>
        <span class="pill pill-coral">Premios {{ $quiniela->points_awards }}</span>
        <span class="pill pill-teal">Stats {{ $quiniela->points_stats }}</span>
        <span class="pill pill-lime">Fases {{ $quiniela->points_phases }}</span>
      </div>
    </div>
  </div>
</div>

@else
{{-- ── MODO FORMULARIO ── --}}
<div class="progress-wrap">
  <div class="progress-label">
    <span>Tu Progreso</span>
    <span id="prog-txt">0 completados</span>
  </div>
  <div class="progress-track">
    <div class="progress-fill" id="prog-fill" style="width:0%"></div>
  </div>
</div>

<form method="POST" action="{{ route('quiniela.maestra.store') }}" id="maestra-form">
@csrf

{{-- ── GRUPOS: CLASIFICADOS ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-teal">🗂</div>
    <div class="card-title">Clasificados por Grupo</div>
    <div class="card-pts">24 <small>picks</small></div>
  </div>
  <div class="card-body">
    <p style="font-size:12px;color:var(--muted);margin-bottom:16px;font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px">
      Selecciona los 2 equipos que avanzan de cada grupo. Estos picks alimentan automáticamente las fases siguientes.
      <span class="pill pill-gold" style="margin-left:6px">1 pt c/u = 24 pts</span>
    </p>
    <div class="group-grid">
      @foreach($groups as $group)
      <div class="group-card">
        <div class="group-card-title">
          <span>Grupo {{ $group->name }}</span>
          <span style="font-size:10px;color:var(--muted)">2 clasificados</span>
        </div>
        @foreach([1,2] as $pos)
        <div style="margin-bottom:8px">
          <div style="font-size:9px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;margin-bottom:4px;text-transform:uppercase">
            {{ $pos === 1 ? '🥇 1er lugar' : '🥈 2do lugar' }}
          </div>
          <div class="sel-wrap">
            <select name="group_picks[{{ $group->id }}][{{ $pos }}]" required onchange="updateProgress();syncGroupPicks()">
              <option value="">— Equipo —</option>
              @foreach($group->teams as $team)
              <option value="{{ $team->id }}"
                {{ ($groupPicks[$group->id][$pos] ?? null) == $team->id ? 'selected':'' }}
                data-flag="{{ $team->flag }}" data-name="{{ $team->name }}">
                {{ $team->flag }} {{ $team->name }} (FIFA #{{ $team->fifa_ranking }})
              </option>
              @endforeach
            </select>
          </div>
        </div>
        @endforeach
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ── SEMIFINALISTAS (de los clasificados) ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-purple">⚡</div>
    <div class="card-title">Semifinalistas</div>
    <div class="card-pts">20 <small>pts</small></div>
  </div>
  <div class="card-body">
    <p style="font-size:12px;color:var(--muted);margin-bottom:16px;font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px">
      4 equipos que llegan a semis. Solo puedes elegir entre los clasificados que seleccionaste arriba.
      <span class="pill pill-purple" style="margin-left:6px">5 pts c/u</span>
    </p>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px" id="semis-grid">
      @for($i=0;$i<4;$i++)
      <div style="background:var(--card2);border:1px solid rgba(107,63,160,.3);border-radius:4px;padding:8px">
        <div style="font-size:9px;letter-spacing:2px;color:#a07de0;font-family:'Barlow Condensed',sans-serif;margin-bottom:6px;text-transform:uppercase">Semi {{ $i+1 }}</div>
        <div class="sel-wrap" style="min-width:0">
          <select name="semis[]" required onchange="updateProgress()" class="semis-select">
            <option value="">— Equipo —</option>
            @foreach($teams as $team)
            <option value="{{ $team->id }}"
              {{ in_array($team->id, $quiniela?->picksByPhase('semis')->pluck('team_id')->toArray() ?? []) ? 'selected':'' }}>
              {{ $team->flag }} {{ $team->name }}
            </option>
            @endforeach
          </select>
        </div>
      </div>
      @endfor
    </div>
  </div>
</div>

{{-- ── FINALISTAS ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-gold">🏟</div>
    <div class="card-title">Finalistas</div>
    <div class="card-pts">28 <small>pts</small></div>
  </div>
  <div class="card-body">
    <p style="font-size:12px;color:var(--muted);margin-bottom:16px;font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px">
      Los 2 equipos que llegan a la Final en MetLife Stadium.
      <span class="pill pill-gold" style="margin-left:6px">14 pts c/u</span>
    </p>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;max-width:400px;margin:0 auto" id="final-grid">
      @foreach([1,2] as $i)
      <div style="background:var(--card2);border:1px solid rgba(201,168,76,.3);border-radius:4px;padding:12px;text-align:center">
        <div style="font-size:9px;letter-spacing:2px;color:var(--gold);font-family:'Barlow Condensed',sans-serif;margin-bottom:8px;text-transform:uppercase">Finalista {{ $i }}</div>
        <div class="sel-wrap" style="min-width:0">
          <select name="final_teams[]" required onchange="updateProgress()" class="final-select">
            <option value="">— Equipo —</option>
            @foreach($teams as $team)
            <option value="{{ $team->id }}"
              {{ in_array($team->id, $quiniela?->picksByPhase('final')->pluck('team_id')->toArray() ?? []) ? 'selected':'' }}>
              {{ $team->flag }} {{ $team->name }}
            </option>
            @endforeach
          </select>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ── PODIO ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-gold">🏆</div>
    <div class="card-title">Podio Final</div>
    <div class="card-pts">45 <small>pts</small></div>
  </div>
  <div class="card-body">
    <div class="podium">
      @foreach([['champion_id','🥇 Campeón','gold','g1',20],['runner_up_id','🥈 Subcampeón','','',15],['third_place_id','🥉 Tercer Lugar','','',10]] as [$field,$label,$cls,$numCls,$pts])
      <div class="podium-slot {{ $cls }}">
        <div class="podium-num {{ $numCls }}">{{ $loop->iteration }}</div>
        <div class="podium-label">{{ $label }}</div>
        <div class="sel-wrap" style="min-width:0">
          <select name="{{ $field }}" required onchange="updateProgress()">
            <option value="">— País —</option>
            @foreach($teams as $team)
            <option value="{{ $team->id }}" {{ old($field, $quiniela?->$field) == $team->id ? 'selected':'' }}>
              {{ $team->flag }} {{ $team->name }}
            </option>
            @endforeach
          </select>
        </div>
        <div style="margin-top:8px"><span class="pill pill-gold">{{ $pts }} pts</span></div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ── PREMIOS INDIVIDUALES ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-coral">★</div>
    <div class="card-title">Premios Individuales</div>
    <div class="card-pts">60 <small>pts</small></div>
  </div>
  <div class="card-body">
    @foreach([
      ['golden_ball','🎖 Balón de Oro','Mejor jugador del torneo','golden_ball',15],
      ['golden_boot','👟 Bota de Oro','Máximo goleador','golden_boot',15],
      ['golden_glove','🧤 Guante de Oro','Mejor portero','golden_glove',10],
      ['best_young','🌟 Mejor Joven','Sub-21 al inicio del torneo','best_young',10],
    ] as [$field,$label,$desc,$key,$pts])
    <div class="input-row">
      <div class="input-label"><strong>{{ $label }}</strong>{{ $desc }}</div>
      <div class="sel-wrap">
        <select name="{{ $field }}" required onchange="updateProgress()">
          <option value="">— Jugador —</option>
          @foreach($players[$key] as $player)
          <option value="{{ $player }}" {{ old($field,$quiniela?->$field) == $player ? 'selected':'' }}>{{ $player }}</option>
          @endforeach
        </select>
      </div>
      <span class="pill pill-gold">{{ $pts }} pts</span>
    </div>
    @endforeach
    <div class="input-row">
      <div class="input-label"><strong>😱 País Sorpresa</strong>Equipo inesperado que llega a semis o más</div>
      <div class="sel-wrap">
        <select name="surprise_team_id" required onchange="updateProgress()">
          <option value="">— País —</option>
          @foreach($teams->whereIn('confederation',['AFC','CAF','CONCACAF','OFC']) as $team)
          <option value="{{ $team->id }}" {{ old('surprise_team_id',$quiniela?->surprise_team_id) == $team->id ? 'selected':'' }}>
            {{ $team->flag }} {{ $team->name }}
          </option>
          @endforeach
        </select>
      </div>
      <span class="pill pill-gold">10 pts</span>
    </div>
  </div>
</div>

{{-- ── ESTADÍSTICAS ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-teal">📊</div>
    <div class="card-title">Estadísticas del Torneo</div>
    <div class="card-pts">35 <small>pts</small></div>
  </div>
  <div class="card-body">
    <div class="input-row">
      <div class="input-label"><strong>⚽ Más Goleadora</strong>País con más goles anotados</div>
      <div class="sel-wrap">
        <select name="top_scorer_team_id" required onchange="updateProgress()">
          <option value="">— País —</option>
          @foreach($teams as $team)
          <option value="{{ $team->id }}" {{ old('top_scorer_team_id',$quiniela?->top_scorer_team_id) == $team->id ? 'selected':'' }}>
            {{ $team->flag }} {{ $team->name }}
          </option>
          @endforeach
        </select>
      </div>
      <span class="pill pill-teal">10 pts</span>
    </div>
    <div class="input-row">
      <div class="input-label"><strong>🛡 Menos Goleada</strong>Menos goles recibidos (mínimo cuartos)</div>
      <div class="sel-wrap">
        <select name="best_defense_id" required onchange="updateProgress()">
          <option value="">— País —</option>
          @foreach($teams as $team)
          <option value="{{ $team->id }}" {{ old('best_defense_id',$quiniela?->best_defense_id) == $team->id ? 'selected':'' }}>
            {{ $team->flag }} {{ $team->name }}
          </option>
          @endforeach
        </select>
      </div>
      <span class="pill pill-teal">10 pts</span>
    </div>
    <div class="input-row" style="border-bottom:none">
      <div class="input-label"><strong>🔢 Total de Goles</strong>El más cercano gana (104 partidos)</div>
      <div style="display:flex;align-items:center;gap:10px">
        <input type="number" name="total_goals_guess" min="50" max="400" placeholder="—"
          value="{{ old('total_goals_guess',$quiniela?->total_goals_guess) }}"
          onchange="updateProgress()" style="width:80px">
        <span class="pill pill-lime">15 pts</span>
      </div>
    </div>
  </div>
</div>

{{-- ── RESUMEN ── --}}
<div class="card" style="border-color:rgba(201,168,76,.3)">
  <div class="card-body" style="padding:18px 20px">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
      <div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-size:10px;letter-spacing:3px;color:var(--muted);text-transform:uppercase;margin-bottom:4px">Puntos Totales en Juego</div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:56px;line-height:1;color:var(--gold)">220+ <span style="font-size:18px;color:var(--muted)">pts</span></div>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <span class="pill pill-gold">Grupos 24</span>
        <span class="pill pill-gold">Podio 45</span>
        <span class="pill pill-coral">Premios 60</span>
        <span class="pill pill-teal">Stats 35</span>
        <span class="pill pill-purple">Semis 20</span>
        <span class="pill pill-gold">Final 28</span>
      </div>
    </div>
  </div>
</div>

<div class="submit-area">
  @if($isClosed)
  <div class="closed-badge">🔒 Quiniela Maestra Cerrada</div>
  @else
  <button type="submit" class="btn btn-gold">🏆 Enviar Quiniela Maestra</button>
  <p class="submit-note">⚠️ Solo se puede enviar una vez · Cierra el 10 de junio a las 23:59</p>
  @endif
</div>

</form>
@endif
@endsection

@push('scripts')
<script>
function updateProgress() {
  const inputs = document.querySelectorAll('#maestra-form select, #maestra-form input[type="number"]');
  let filled = 0;
  inputs.forEach(i => { if (i.value && i.value !== '') filled++; });
  const pct = inputs.length > 0 ? Math.round((filled / inputs.length) * 100) : 0;
  document.getElementById('prog-fill').style.width = pct + '%';
  document.getElementById('prog-txt').textContent = filled + ' / ' + inputs.length + ' completados';
}

// When group picks change, update semis/final selects to only show picked teams
function syncGroupPicks() {
  const pickedTeams = [];
  document.querySelectorAll('[name^="group_picks"]').forEach(sel => {
    if (sel.value) {
      const opt = sel.options[sel.selectedIndex];
      pickedTeams.push({ id: sel.value, flag: opt.dataset.flag || '', name: opt.dataset.name || opt.text });
    }
  });

  // Update semis selects
  document.querySelectorAll('.semis-select, .final-select').forEach(sel => {
    const current = sel.value;
    sel.innerHTML = '<option value="">— Equipo —</option>';
    pickedTeams.forEach(t => {
      const opt = document.createElement('option');
      opt.value = t.id;
      opt.textContent = t.flag + ' ' + t.name;
      if (t.id === current) opt.selected = true;
      sel.appendChild(opt);
    });
  });
}

updateProgress();
</script>
@endpush

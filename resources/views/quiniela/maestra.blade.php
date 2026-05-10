@extends('layouts.app')
@section('title','Quiniela Maestra')
@section('content')

<div class="page-hero">
  <div class="mascots-row">
    <span title="Maple">🫎</span>
    <span title="Zayu">🐆</span>
    <span title="Clutch">🦅</span>
  </div>
  <div class="hero-eyebrow">Formulario Oficial · Una sola vez · Cierra 10 Jun 23:59 HN</div>
  <h1 class="hero-title"><span class="outline">QUINIELA</span><br><span class="fill">MAESTRA</span></h1>
  <p class="hero-sub">Completa tus predicciones antes del pitazo inaugural. Los puntos se revelan progresivamente — nadie puede relajarse hasta la final en MetLife.</p>
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

{{-- ── PASO 1: CLASIFICADOS DE GRUPOS (24 equipos: 1ro y 2do de cada grupo) ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-teal">1️⃣</div>
    <div class="card-title">Paso 1 · 1ro y 2do de cada Grupo</div>
    <div class="card-pts">48 <small>pts</small></div>
  </div>
  <div class="card-body">

    {{-- Explicación del formato --}}
    <div style="background:rgba(0,184,169,.06);border:1px solid rgba(0,184,169,.2);border-radius:5px;padding:12px 16px;margin-bottom:20px">
      <div style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:13px;letter-spacing:1px;color:var(--teal);margin-bottom:6px">📋 FORMATO MUNDIAL 2026</div>
      <div style="font-size:12px;color:var(--muted);line-height:1.8">
        • <strong style="color:var(--white)">12 grupos × 4 equipos</strong> — clasifican 1ro y 2do de cada grupo = <strong style="color:var(--gold)">24 equipos</strong><br>
        • Los <strong style="color:var(--white)">8 mejores terceros</strong> también clasifican = <strong style="color:var(--gold)">32 equipos en total</strong> para la Ronda de 32<br>
        • <span class="pill pill-teal" style="font-size:11px">2 pts</span> por cada clasificado de grupo correcto ·
          <span class="pill pill-gold" style="font-size:11px">3 pts</span> por cada mejor tercero correcto
      </div>
    </div>

    <div class="group-grid">
      @foreach($groups as $group)
      <div class="group-card" id="group-card-{{ $group->id }}">
        <div class="group-card-title">
          <span>GRUPO {{ $group->name }}</span>
          <span style="font-size:10px;color:var(--muted)">{{ $group->teams->count() }} equipos</span>
        </div>

        {{-- Teams in group for reference --}}
        <div style="margin-bottom:10px;padding:6px 8px;background:rgba(0,0,0,.2);border-radius:3px">
          @foreach($group->teams->sortBy('fifa_ranking') as $team)
          <div style="font-size:11px;color:var(--muted);padding:1px 0;display:flex;align-items:center;gap:5px">
            <span>{{ $team->flag }}</span>
            <span style="color:var(--white)">{{ $team->name }}</span>
            <span style="margin-left:auto;font-size:10px">#{{ $team->fifa_ranking }}</span>
          </div>
          @endforeach
        </div>

        @foreach([1 => ['🥇','1er lugar','gold'], 2 => ['🥈','2do lugar','muted']] as $pos => [$medal,$posLabel,$color])
        <div style="margin-bottom:8px">
          <div style="font-size:9px;letter-spacing:2px;color:var(--{{ $color }});font-family:'Barlow Condensed',sans-serif;font-weight:700;margin-bottom:4px;text-transform:uppercase">
            {{ $medal }} {{ $posLabel }}
          </div>
          <div class="sel-wrap" style="min-width:0">
            <select name="group_picks[{{ $group->id }}][{{ $pos }}]" required
              onchange="updateProgress();syncGroupPicks()"
              data-group="{{ $group->name }}" data-pos="{{ $pos }}">
              <option value="">— Selecciona —</option>
              @foreach($group->teams->sortBy('fifa_ranking') as $team)
              <option value="{{ $team->id }}"
                {{ ($groupPicks[$group->id][$pos] ?? null) == $team->id ? 'selected':'' }}
                data-flag="{{ $team->flag }}" data-name="{{ $team->name }}">
                {{ $team->flag }} {{ $team->name }}
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

{{-- ── PASO 2: 8 MEJORES TERCEROS ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-purple">2️⃣</div>
    <div class="card-title">Paso 2 · 8 Mejores Terceros</div>
    <div class="card-pts">24 <small>pts</small></div>
  </div>
  <div class="card-body">

    <div style="background:rgba(107,63,160,.08);border:1px solid rgba(107,63,160,.25);border-radius:5px;padding:12px 16px;margin-bottom:20px">
      <div style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:13px;letter-spacing:1px;color:#a07de0;margin-bottom:6px">🏅 LOS 8 MEJORES TERCEROS</div>
      <div style="font-size:12px;color:var(--muted);line-height:1.8">
        De los 12 terceros lugares, los <strong style="color:var(--white)">8 con mejor rendimiento</strong> (puntos, diferencia de goles, goles marcados) también clasifican a la Ronda de 32.<br>
        Selecciona los 8 grupos de los que crees que el tercer lugar clasificará.
        <span class="pill pill-purple" style="font-size:11px;margin-left:4px">3 pts c/u = 24 pts</span>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px" id="terceros-grid">
      @php
        $savedTerceros = [];
        if($quiniela) {
          $savedTerceros = \App\Models\QuinielaGroupPick::where('quiniela_id', $quiniela->id)
            ->where('position', 3)->pluck('team_id')->toArray();
        }
      @endphp
      @for($i = 0; $i < 8; $i++)
      <div style="background:var(--card2);border:1px solid rgba(107,63,160,.25);border-radius:4px;padding:8px">
        <div style="font-size:9px;letter-spacing:2px;color:#a07de0;font-family:'Barlow Condensed',sans-serif;font-weight:700;margin-bottom:6px;text-transform:uppercase">
          3ro #{{ $i+1 }}
        </div>
        <div class="sel-wrap" style="min-width:0">
          <select name="best_thirds[]" class="tercero-select" onchange="updateProgress()">
            <option value="">— Grupo —</option>
            @foreach($groups as $group)
            <option value="{{ $group->id }}"
              {{ isset($savedTerceros[$i]) && $savedTerceros[$i] == $group->id ? 'selected':'' }}>
              Grupo {{ $group->name }}
            </option>
            @endforeach
          </select>
        </div>
      </div>
      @endfor
    </div>

    <p style="font-size:11px;color:var(--muted);margin-top:12px;font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;text-align:center">
      💡 Históricamente clasifican los terceros de los grupos más fuertes. En 2022 clasificaron los mejores 3ros de grupos B, C, D, E, F, G, H.
    </p>
  </div>
</div>

{{-- ── PASO 3: CLASIFICADOS A OCTAVOS (16 equipos de la R32) ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-teal">3️⃣</div>
    <div class="card-title">Paso 3 · Clasificados a Octavos de Final</div>
    <div class="card-pts">80 <small>pts</small></div>
  </div>
  <div class="card-body">
    <div style="background:rgba(0,184,169,.06);border:1px solid rgba(0,184,169,.2);border-radius:5px;padding:10px 14px;margin-bottom:16px">
      <div style="font-size:12px;color:var(--muted);line-height:1.8">
        De los 32 equipos en la Ronda de 32, <strong style="color:var(--white)">16 avanzan a Octavos</strong>.
        Elige los 16 que crees que pasan.
        <span class="pill pill-teal" style="font-size:11px;margin-left:4px">5 pts c/u = 80 pts</span>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px">
      @php
        $savedOctavos = $quiniela ? $quiniela->picksByPhase('round_of_16')->pluck('team_id')->toArray() : [];
      @endphp
      @for($i=0; $i<16; $i++)
      <div style="background:var(--card2);border:1px solid rgba(0,184,169,.2);border-radius:4px;padding:7px">
        <div style="font-size:9px;letter-spacing:1px;color:var(--teal);font-family:'Barlow Condensed',sans-serif;font-weight:700;margin-bottom:5px;text-transform:uppercase">8vos #{{ $i+1 }}</div>
        <div class="sel-wrap" style="min-width:0">
          <select name="octavos[]" class="octavos-select" onchange="updateProgress();syncOctavos()">
            <option value="">— Equipo —</option>
            @foreach($teams as $team)
            <option value="{{ $team->id }}" {{ isset($savedOctavos[$i]) && $savedOctavos[$i]==$team->id ? 'selected':'' }}>
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

{{-- ── PASO 4: CLASIFICADOS A CUARTOS (8 equipos) ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-coral">4️⃣</div>
    <div class="card-title">Paso 4 · Clasificados a Cuartos de Final</div>
    <div class="card-pts">40 <small>pts</small></div>
  </div>
  <div class="card-body">
    <div style="background:rgba(255,77,61,.06);border:1px solid rgba(255,77,61,.2);border-radius:5px;padding:10px 14px;margin-bottom:16px">
      <div style="font-size:12px;color:var(--muted);line-height:1.8">
        Los <strong style="color:var(--white)">8 equipos</strong> que llegan a Cuartos de Final.
        Solo puedes elegir entre los que pusiste en Octavos.
        <span class="pill pill-coral" style="font-size:11px;margin-left:4px">5 pts c/u = 40 pts</span>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px">
      @php
        $savedCuartos = $quiniela ? $quiniela->picksByPhase('quarters')->pluck('team_id')->toArray() : [];
      @endphp
      @for($i=0; $i<8; $i++)
      <div style="background:var(--card2);border:1px solid rgba(255,77,61,.2);border-radius:4px;padding:7px">
        <div style="font-size:9px;letter-spacing:1px;color:var(--coral);font-family:'Barlow Condensed',sans-serif;font-weight:700;margin-bottom:5px;text-transform:uppercase">Cuartos #{{ $i+1 }}</div>
        <div class="sel-wrap" style="min-width:0">
          <select name="cuartos[]" class="cuartos-select" onchange="updateProgress();syncCuartos()">
            <option value="">— Equipo —</option>
            @foreach($teams as $team)
            <option value="{{ $team->id }}" {{ isset($savedCuartos[$i]) && $savedCuartos[$i]==$team->id ? 'selected':'' }}>
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

{{-- ── PASO 5: SEMIFINALISTAS ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-purple">5️⃣</div>
    <div class="card-title">Paso 5 · Semifinalistas</div>
    <div class="card-pts">32 <small>pts</small></div>
  </div>
  <div class="card-body">
    <div style="background:rgba(107,63,160,.06);border:1px solid rgba(107,63,160,.2);border-radius:5px;padding:10px 14px;margin-bottom:16px">
      <div style="font-size:12px;color:var(--muted);line-height:1.8">
        Los <strong style="color:var(--white)">4 equipos</strong> que llegan a Semifinales.
        Solo puedes elegir entre los que pusiste en Cuartos.
        <span class="pill pill-purple" style="font-size:11px;margin-left:4px">8 pts c/u = 32 pts</span>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px" id="semis-grid">
      @for($i=0;$i<4;$i++)
      <div style="background:var(--card2);border:1px solid rgba(107,63,160,.3);border-radius:4px;padding:8px">
        <div style="font-size:9px;letter-spacing:2px;color:#a07de0;font-family:'Barlow Condensed',sans-serif;margin-bottom:6px;text-transform:uppercase">Semi {{ $i+1 }}</div>
        <div class="sel-wrap" style="min-width:0">
          <select name="semis[]" required onchange="updateProgress();syncSemis()" class="semis-select">
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

{{-- ── PASO 6: FINALISTAS ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-gold">6️⃣</div>
    <div class="card-title">Paso 6 · Finalistas</div>
    <div class="card-pts">28 <small>pts</small></div>
  </div>
  <div class="card-body">
    <div style="background:rgba(201,168,76,.06);border:1px solid rgba(201,168,76,.2);border-radius:5px;padding:10px 14px;margin-bottom:16px">
      <div style="font-size:12px;color:var(--muted);line-height:1.8">
        Los <strong style="color:var(--white)">2 finalistas</strong> en MetLife Stadium.
        Solo puedes elegir entre los que pusiste en Semis.
        <span class="pill pill-gold" style="font-size:11px;margin-left:4px">14 pts c/u = 28 pts</span>
      </div>
    </div>
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
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:56px;line-height:1;color:var(--gold)">300+ <span style="font-size:18px;color:var(--muted)">pts</span></div>
      </div>
      <div style="display:flex;gap:6px;flex-wrap:wrap">
        <span class="pill pill-teal">1ros/2dos 48pts</span>
        <span class="pill pill-purple">Mejores 3ros 24pts</span>
        <span class="pill pill-purple">Semis 32pts</span>
        <span class="pill pill-gold">Final 28pts</span>
        <span class="pill pill-gold">Podio 40pts</span>
        <span class="pill pill-coral">Premios 49pts</span>
      </div>
    </div>
    <div style="margin-top:14px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:8px">
      @foreach([
        ['🥇 1ro de grupo','2 pts × 12 = 24 pts','teal'],
        ['🥈 2do de grupo','2 pts × 12 = 24 pts','teal'],
        ['🏅 Mejor 3ro','3 pts × 8 = 24 pts','purple'],
        ['⚽ Clasif. Octavos','5 pts × 16 = 80 pts','coral'],
        ['⚽ Clasif. Cuartos','5 pts × 8 = 40 pts','coral'],
        ['⚡ Semifinalistas','8 pts × 4 = 32 pts','purple'],
        ['🏟 Finalistas','14 pts × 2 = 28 pts','gold'],
        ['🏆 Campeón','25 pts','gold'],
        ['🥈 Subcampeón','15 pts','gold'],
        ['👟 Bota de Oro','15 pts','coral'],
        ['🧤 Guante de Oro','10 pts','coral'],
        ['🌟 Mejor Joven','12 pts','coral'],
        ['😱 País Sorpresa','12 pts','coral'],
      ] as [$label,$pts,$color])
      <div style="background:var(--card2);border:1px solid var(--border);border-radius:4px;padding:8px 10px;display:flex;justify-content:space-between;align-items:center">
        <span style="font-size:12px;color:var(--white)">{{ $label }}</span>
        <span class="pill pill-{{ $color }}" style="font-size:11px">{{ $pts }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

<div class="submit-area">
  @if($isClosed)
  <div class="closed-badge">🔒 Quiniela Maestra Cerrada</div>
  @else
  <button type="submit" class="btn btn-gold">🏆 Enviar Quiniela Maestra</button>
  <p class="submit-note">⚠️ Solo se puede enviar una vez · No editable después de enviar · Cierra el 10 de junio a las 23:59 HN</p>
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

// Collect picked teams from a set of selects
function getPickedTeams(selector) {
  const teams = [];
  const seen = new Set();
  document.querySelectorAll(selector).forEach(sel => {
    if (sel.value && !seen.has(sel.value)) {
      seen.add(sel.value);
      const opt = sel.options[sel.selectedIndex];
      teams.push({
        id:   sel.value,
        flag: opt.dataset.flag || '',
        name: opt.dataset.name || opt.text.split('(')[0].trim()
      });
    }
  });
  return teams.sort((a,b) => a.name.localeCompare(b.name));
}

// Populate a set of selects with a list of teams
function populateSelects(selector, teams, label) {
  document.querySelectorAll(selector).forEach(sel => {
    const current = sel.value;
    sel.innerHTML = `<option value="">— ${label} —</option>`;
    teams.forEach(t => {
      const opt = document.createElement('option');
      opt.value = t.id;
      opt.dataset.flag = t.flag;
      opt.dataset.name = t.name;
      opt.textContent = t.flag + ' ' + t.name;
      if (t.id === current) opt.selected = true;
      sel.appendChild(opt);
    });
  });
}

// Cascade: grupos → octavos
function syncGroupPicks() {
  const teams = getPickedTeams('[name^="group_picks"]');
  populateSelects('.octavos-select', teams, 'Equipo clasificado');
  syncOctavos();

  // Prevent same team for 1ro and 2do in same group
  document.querySelectorAll('[name^="group_picks"]').forEach(sel => {
    const m = sel.name.match(/\[(\d+)\]/);
    if (!m) return;
    const groupId = m[1];
    const pair = document.querySelectorAll(`[name^="group_picks[${groupId}]"]`);
    if (pair.length === 2 && pair[0].value && pair[1].value && pair[0].value === pair[1].value) {
      sel.value = '';
    }
  });
}

// Cascade: octavos → cuartos
function syncOctavos() {
  const teams = getPickedTeams('.octavos-select');
  populateSelects('.cuartos-select', teams, 'Equipo de octavos');
  syncCuartos();
}

// Cascade: cuartos → semis
function syncCuartos() {
  const teams = getPickedTeams('.cuartos-select');
  populateSelects('.semis-select', teams, 'Equipo de cuartos');
  syncSemis();
}

// Cascade: semis → final
function syncSemis() {
  const teams = getPickedTeams('.semis-select');
  populateSelects('.final-select', teams, 'Equipo semifinalista');
}

// Init on page load
updateProgress();
// Small delay to let DOM settle before syncing
setTimeout(() => {
  syncGroupPicks();
}, 100);
</script>
@endpush

@extends('layouts.app')
@section('title','Eventos Especiales')
@section('content')

<div class="page-hero" style="padding:32px 0 28px;margin-bottom:28px">
  <div class="hero-eyebrow">Cierra 1 hora antes del partido inaugural · 11 Jun 14:00 UTC</div>
  <h1 class="hero-title" style="font-size:clamp(36px,7vw,72px)">
    <span class="outline">EXTRAS</span> <span class="fill">DIVERTIDOS</span>
  </h1>
  <p class="hero-sub">Predice eventos únicos del torneo. Algunos son casi imposibles — pero si aciertas, los puntos valen doble la emoción.</p>
</div>

@if($isClosed)
<div class="alert alert-error">🔒 Los eventos especiales ya cerraron.</div>
@endif

<form method="POST" action="{{ route('quiniela.especiales.store') }}">
@csrf

@php
$extras = collect($definitions)->filter(fn($d) => !str_starts_with($d['type'], 'pitch_') && !in_array($d['type'], ['long_range_goal','goalkeeper_goal','match_suspended','goal_before_min5','comeback_2goals','last_penalty_wins']));
$retos  = collect($definitions)->filter(fn($d) => in_array($d['type'], ['pitch_invader','long_range_goal','goalkeeper_goal','match_suspended','goal_before_min5','comeback_2goals','last_penalty_wins']));
@endphp

{{-- ── EXTRAS DIVERTIDOS ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-coral">🎯</div>
    <div class="card-title">Extras Divertidos</div>
    <div class="card-pts" style="font-size:14px">hasta 119 <small>pts</small></div>
  </div>
  <div class="card-body">
    @foreach($extras as $def)
    @php
      $pick     = $myPicks[$def['type']] ?? null;
      $resolved = $resolved[$def['type']] ?? null;
    @endphp
    <div class="input-row">
      <div class="input-label">
        <strong>{{ $def['label'] }}</strong>
        @if($resolved)
          @if($resolved->team) <span style="color:var(--teal)">→ {{ $resolved->team->flag }} {{ $resolved->team->name }}</span>
          @elseif($resolved->player_name) <span style="color:var(--teal)">→ {{ $resolved->player_name }}</span>
          @else <span style="color:var(--teal)">→ ✓ Ocurrió</span>
          @endif
        @endif
      </div>

      @if($def['pick_type'] === 'team')
      <div class="sel-wrap">
        <select name="picks[{{ $def['type'] }}][team_id]" {{ $isClosed?'disabled':'' }}>
          <option value="">— Equipo —</option>
          @foreach($teams as $team)
          <option value="{{ $team->id }}" {{ $pick?->team_id == $team->id ? 'selected':'' }}>
            {{ $team->flag }} {{ $team->name }}
          </option>
          @endforeach
        </select>
      </div>

      @elseif($def['pick_type'] === 'player')
      <input type="text" name="picks[{{ $def['type'] }}][player_name]"
        placeholder="Nombre del jugador"
        value="{{ $pick?->player_name }}"
        {{ $isClosed?'disabled':'' }}
        style="max-width:200px">

      @elseif($def['pick_type'] === 'match')
      <div class="sel-wrap">
        <select name="picks[{{ $def['type'] }}][team_id]" {{ $isClosed?'disabled':'' }}>
          <option value="">— Equipo involucrado —</option>
          @foreach($teams as $team)
          <option value="{{ $team->id }}" {{ $pick?->team_id == $team->id ? 'selected':'' }}>
            {{ $team->flag }} {{ $team->name }}
          </option>
          @endforeach
        </select>
      </div>
      @endif

      <div style="display:flex;align-items:center;gap:8px">
        <span class="pill pill-coral">{{ $def['points'] }} pts</span>
        @if($pick && $pick->correct)
        <span class="pill pill-teal">✓ +{{ $def['points'] }}</span>
        @endif
      </div>
    </div>
    @endforeach
  </div>
</div>

{{-- ── RETOS RANDOM ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-lime" style="color:#000">😂</div>
    <div class="card-title">Retos Random</div>
    <div class="card-pts" style="font-size:14px">hasta 44 <small>pts</small></div>
  </div>
  <div class="card-body">
    <p style="font-size:12px;color:var(--muted);margin-bottom:16px;font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px">
      Predice si estos eventos ocurrirán en algún momento del torneo. Si aciertas, sumas los puntos.
    </p>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:8px">
      @foreach($retos as $def)
      @php $pick = $myPicks[$def['type']] ?? null; $res = $resolved[$def['type']] ?? null; @endphp
      <div style="background:var(--card2);border:1px solid {{ $res ? 'rgba(0,184,169,.3)' : 'var(--border)' }};border-radius:5px;padding:12px;display:flex;align-items:center;gap:10px">
        <label class="toggle">
          <input type="checkbox" name="picks[{{ $def['type'] }}][player_name]" value="si"
            {{ $pick?->player_name === 'si' ? 'checked':'' }}
            {{ $isClosed?'disabled':'' }}>
          <div class="toggle-track"></div>
        </label>
        <div style="flex:1">
          <div style="font-size:13px;font-weight:600;color:var(--white)">{{ $def['label'] }}</div>
          @if($res)
          <div style="font-size:11px;color:var(--teal);margin-top:2px">✓ Ocurrió</div>
          @endif
        </div>
        <span class="pill pill-lime">+{{ $def['points'] }}</span>
        @if($pick && $pick->correct)
        <span class="pill pill-teal">✓</span>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ── BONUS ESPECIALES ── --}}
<div class="card" style="border-color:rgba(201,168,76,.3)">
  <div class="card-header">
    <div class="card-icon ci-gold">💎</div>
    <div class="card-title">Bonus Especiales</div>
    <div class="card-pts" style="font-size:14px">hasta 70 <small>pts</small></div>
  </div>
  <div class="card-body">
    <p style="font-size:12px;color:var(--muted);margin-bottom:16px;font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px">
      Estos bonus se calculan automáticamente durante el torneo. No requieren predicción previa.
    </p>
    @foreach([
      ['💎','Acertar campeón + goleador del torneo','+10 pts','Se suma automáticamente si acertaste ambos en la Maestra'],
      ['🔥','3 marcadores exactos seguidos','+15 pts','En cualquier momento del torneo'],
      ['⭐','Jornada perfecta','+20 pts','Todos los pronósticos de una jornada correctos'],
      ['🏆','Acertar la final completa','+25 pts','Marcador exacto + ambos finalistas correctos'],
    ] as [$icon,$label,$pts,$desc])
    <div class="input-row">
      <div class="input-label">
        <strong>{{ $icon }} {{ $label }}</strong>{{ $desc }}
      </div>
      <span class="pill pill-gold">{{ $pts }}</span>
    </div>
    @endforeach
  </div>
</div>

@if(!$isClosed)
<div class="submit-area">
  <button type="submit" class="btn btn-coral">🎯 Guardar Eventos Especiales</button>
  <p class="submit-note">Puedes modificar hasta 1 hora antes del partido inaugural (11 Jun 14:00 UTC)</p>
</div>
@endif

</form>
@endsection

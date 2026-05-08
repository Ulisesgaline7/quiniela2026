@extends('layouts.app')
@section('title','Tabla de Posiciones')
@section('content')

<div class="page-hero" style="padding:32px 0 28px;margin-bottom:28px">
  <div class="hero-mascots">🫎 🐆 🦅</div>
  <div class="hero-eyebrow">FIFA World Cup 26™ · Actualizado en tiempo real</div>
  <h1 class="hero-title"><span class="outline">TABLA</span> <span class="fill">GENERAL</span></h1>
</div>

<div class="card">
  <div class="card-header">
    <div class="card-icon ci-gold">🏅</div>
    <div class="card-title">Clasificación</div>
    <div class="card-pts">{{ $standings->count() }} <small>jugadores</small></div>
  </div>
  <div class="card-body" style="padding:0">
    <table class="lb-table">
      <thead>
        <tr>
          <th style="width:46px">#</th>
          <th>Jugador</th>
          <th style="text-align:right">Maestra</th>
          <th style="text-align:right">Partidos</th>
          <th style="text-align:right;color:var(--gold)">Total</th>
        </tr>
      </thead>
      <tbody>
        @forelse($standings as $i => $row)
        <tr style="{{ $row->id === auth()->id() ? 'background:rgba(201,168,76,.04)' : '' }}">
          <td class="lb-rank {{ $i===0?'r1':($i===1?'r2':($i===2?'r3':'')) }}">
            @if($i===0) 🥇 @elseif($i===1) 🥈 @elseif($i===2) 🥉 @else {{ $i+1 }} @endif
          </td>
          <td>
            <div class="lb-name">
              {{ $row->name }}
              @if($row->id === auth()->id())
              <span style="font-size:10px;color:var(--teal);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;margin-left:6px">TÚ</span>
              @endif
            </div>
            @if($row->quiniela)
            <div style="font-size:11px;color:var(--muted);margin-top:2px;font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px">
              {{ $row->quiniela->champion?->flag }} {{ $row->quiniela->champion?->name }}
              @if($row->quiniela->runner_up_id)
              · {{ $row->quiniela->runnerUp?->flag }} {{ $row->quiniela->runnerUp?->name }}
              @endif
            </div>
            @else
            <div style="font-size:11px;color:var(--coral);margin-top:2px;font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px">Sin quiniela maestra</div>
            @endif
          </td>
          <td style="text-align:right;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:18px;color:var(--muted)">{{ $row->maestra_pts }}</td>
          <td style="text-align:right;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:18px;color:var(--muted)">{{ $row->partido_pts }}</td>
          <td class="lb-pts">{{ $row->grand_total }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px">Nadie ha enviado su quiniela todavía. ¡Sé el primero!</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($recentPredictions->count())
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-lime">⚡</div>
    <div class="card-title">Últimos Pronósticos Puntuados</div>
  </div>
  <div class="card-body" style="padding:0">
    @foreach($recentPredictions as $pred)
    <div style="display:flex;align-items:center;gap:14px;padding:11px 18px;border-bottom:1px solid rgba(201,168,76,.04)">
      <div style="flex:1">
        <div style="font-size:13px;font-weight:600;color:var(--white)">{{ $pred->user->name }}</div>
        <div style="font-size:12px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;margin-top:2px">
          {{ $pred->match->homeTeam->flag }} {{ $pred->match->homeTeam->short_name }}
          <span style="color:var(--gold);font-family:'Barlow Condensed',sans-serif;font-size:16px;font-weight:900;margin:0 6px">{{ $pred->home_score }}–{{ $pred->away_score }}</span>
          {{ $pred->match->awayTeam->short_name }} {{ $pred->match->awayTeam->flag }}
        </div>
      </div>
      <div style="text-align:right">
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:26px;color:{{ $pred->total_points>=10?'var(--teal)':($pred->total_points>=5?'var(--gold)':'var(--muted)') }}">+{{ $pred->total_points }}</div>
        <div style="font-size:9px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;text-transform:uppercase">pts</div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endif

{{-- SISTEMA DE PUNTOS --}}
<div class="card" style="border-color:rgba(201,168,76,.2)">
  <div class="card-header">
    <div class="card-icon ci-teal">📋</div>
    <div class="card-title">Sistema de Puntos</div>
  </div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px">
      <div>
        <div style="font-size:10px;color:var(--gold);font-family:'Barlow Condensed',sans-serif;letter-spacing:2px;margin-bottom:10px;text-transform:uppercase">🏆 Quiniela Maestra</div>
        @foreach(['Campeón · 20 pts','Subcampeón · 15 pts','Tercer lugar · 10 pts','Balón de Oro · 15 pts','Bota de Oro · 15 pts','Guante de Oro · 10 pts','Mejor Joven · 10 pts','País Sorpresa · 10 pts','Clasificados Grupos · 1 pt c/u','Semifinalistas · 5 pts c/u','Finalistas · 14 pts c/u'] as $item)
        <div style="font-size:12px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;padding:3px 0;border-bottom:1px solid rgba(201,168,76,.04)">{{ $item }}</div>
        @endforeach
      </div>
      <div>
        <div style="font-size:10px;color:var(--teal);font-family:'Barlow Condensed',sans-serif;letter-spacing:2px;margin-bottom:10px;text-transform:uppercase">⚽ Por Partido</div>
        @foreach(['Marcador exacto · 10 pts','Ganador correcto · 4 pts','Diferencia exacta · 6 pts','Primer goleador · +3 pts','Tarjeta roja · +2 pts','Prórroga · +4 pts','Penales · +3 pts'] as $item)
        <div style="font-size:12px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;padding:3px 0;border-bottom:1px solid rgba(201,168,76,.04)">{{ $item }}</div>
        @endforeach
      </div>
    </div>
  </div>
</div>

@endsection

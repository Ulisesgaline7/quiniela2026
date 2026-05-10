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
        @foreach([
          'Campeón · 25 pts','Subcampeón · 15 pts',
          'Semifinalistas · 8 pts c/u',
          'Clasificados grupos · 2 pts c/u',
          'Clasificados 16avos · 3 pts c/u',
          'Clasificados 8vos · 5 pts c/u',
          'Bota de Oro · 15 pts','Guante de Oro · 10 pts',
          'Mejor Joven · 12 pts','País Sorpresa · 12 pts',
        ] as $item)
        <div style="font-size:12px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;padding:3px 0;border-bottom:1px solid rgba(201,168,76,.04)">{{ $item }}</div>
        @endforeach
      </div>
      <div>
        <div style="font-size:10px;color:var(--teal);font-family:'Barlow Condensed',sans-serif;letter-spacing:2px;margin-bottom:10px;text-transform:uppercase">⚽ Por Partido</div>
        @foreach([
          'Marcador exacto · 12 pts',
          'Diferencia exacta · 7 pts',
          'Ganador correcto · 4 pts',
          'Primer goleador · +4 pts',
          'Ambos equipos anotan · +2 pts',
          'Más de 3 goles · +2 pts',
          'Tarjeta roja · +2 pts',
          'Penal en el partido · +2 pts',
          'Gol en tiempo agregado · +3 pts',
          'Prórroga · +5 pts',
          'Penales · +4 pts',
        ] as $item)
        <div style="font-size:12px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;padding:3px 0;border-bottom:1px solid rgba(201,168,76,.04)">{{ $item }}</div>
        @endforeach
      </div>
      <div>
        <div style="font-size:10px;color:var(--coral);font-family:'Barlow Condensed',sans-serif;letter-spacing:2px;margin-bottom:10px;text-transform:uppercase">🎯 Eventos Especiales</div>
        @foreach([
          'Primer eliminado · 10 pts',
          'Primera clasificada · 8 pts',
          'Primer 0-0 · 6 pts',
          'Primer hat-trick · 8 pts',
          'Primer autogol · 5 pts',
          'Más goleador en grupos · 8 pts',
          'Menos goleado · 8 pts',
          'Partido con más goles · 10 pts',
          'Selección decepción · 10 pts',
          'Selección revelación · 10 pts',
          'Jugador revelación · 8 pts',
        ] as $item)
        <div style="font-size:12px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;padding:3px 0;border-bottom:1px solid rgba(201,168,76,.04)">{{ $item }}</div>
        @endforeach
        <div style="font-size:10px;color:var(--lime);font-family:'Barlow Condensed',sans-serif;letter-spacing:2px;margin-top:12px;margin-bottom:8px;text-transform:uppercase">💎 Bonus</div>
        @foreach([
          'Campeón + Goleador · +10 pts',
          '3 exactos seguidos · +15 pts',
          'Jornada perfecta · +20 pts',
          'Final completa · +25 pts',
        ] as $item)
        <div style="font-size:12px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;padding:3px 0;border-bottom:1px solid rgba(201,168,76,.04)">{{ $item }}</div>
        @endforeach
      </div>
    </div>
  </div>
</div>

@endsection

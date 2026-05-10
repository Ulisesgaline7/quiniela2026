@extends('layouts.app')
@section('title','Admin')
@section('content')

<div class="page-hero" style="padding:24px 0 20px;margin-bottom:24px">
  <div class="hero-eyebrow">Panel de Administración</div>
  <h1 class="hero-title" style="font-size:clamp(36px,7vw,64px)"><span class="fill">ADMIN</span> <span class="outline">PANEL</span></h1>
</div>

{{-- ── GESTIÓN DE USUARIOS ── --}}
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-teal">👥</div>
    <div class="card-title">Gestión de Usuarios</div>
    <div class="card-pts">{{ $users->count() }} <small>usuarios</small></div>
  </div>
  <div class="card-body">
    {{-- Create user form --}}
    <form method="POST" action="{{ route('admin.users.create') }}" style="display:grid;grid-template-columns:1fr 1fr auto auto;gap:10px;align-items:end;margin-bottom:20px">
      @csrf
      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">Nombre Completo</label>
        <input type="text" name="name" placeholder="Ej: Juan García" required>
      </div>
      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">Usuario (para login)</label>
        <input type="text" name="username" placeholder="juan_garcia" required pattern="[a-zA-Z0-9_\-]+">
      </div>
      <div style="display:flex;align-items:center;gap:6px;padding-bottom:2px">
        <input type="checkbox" name="is_admin" id="is_admin" style="width:16px;height:16px;accent-color:var(--coral)">
        <label for="is_admin" style="font-size:12px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;cursor:pointer">Admin</label>
      </div>
      <button type="submit" class="btn btn-teal btn-sm">+ Crear</button>
    </form>

    {{-- Users list --}}
    <table class="lb-table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Usuario</th>
          <th>Rol</th>
          <th>Quiniela</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td style="font-weight:600;color:var(--white)">{{ $user->name }}</td>
          <td style="font-family:'Barlow Condensed',sans-serif;font-size:15px;color:var(--teal);letter-spacing:1px">{{ $user->username }}</td>
          <td>
            @if($user->is_admin)
            <span class="pill pill-coral">Admin</span>
            @else
            <span class="pill pill-gold">Jugador</span>
            @endif
          </td>
          <td>
            @if($user->quiniela?->submitted)
            <span class="pill pill-teal">✓ Enviada</span>
            @else
            <span style="font-size:12px;color:var(--muted);font-family:'Barlow Condensed',sans-serif">Pendiente</span>
            @endif
          </td>
          <td>
            @if(!$user->is_admin)
            <form method="POST" action="{{ route('admin.users.delete',$user) }}" onsubmit="return confirm('¿Eliminar a {{ $user->name }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-outline btn-sm" style="color:var(--coral);border-color:rgba(255,77,61,.3)">✕</button>
            </form>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- ── CREAR PARTIDO ELIMINATORIO ── --}}
<div class="card" style="border-color:rgba(0,204,170,.2)">
  <div class="card-header">
    <div class="card-icon ci-teal">➕</div>
    <div class="card-title">Crear Partido Eliminatorio</div>
  </div>
  <div class="card-body">
    <p style="font-size:12px;color:var(--muted);margin-bottom:16px;font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px">
      Agrega partidos de Ronda de 32, Octavos, Cuartos, Semis y Final cuando se conozcan los clasificados. Los pronósticos abren automáticamente y cierran 1 hora antes del kickoff.
    </p>
    <form method="POST" action="{{ route('admin.match.create') }}">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">🏠 Equipo Local</label>
        <div class="sel-wrap">
          <select name="home_team_id" required>
            <option value="">— Selecciona —</option>
            @foreach($allTeams as $team)
            <option value="{{ $team->id }}">{{ $team->flag }} {{ $team->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">✈️ Equipo Visitante</label>
        <div class="sel-wrap">
          <select name="away_team_id" required>
            <option value="">— Selecciona —</option>
            @foreach($allTeams as $team)
            <option value="{{ $team->id }}">{{ $team->flag }} {{ $team->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">🏟 Fase</label>
        <div class="sel-wrap">
          <select name="phase" required>
            <option value="round_of_32">Ronda de 32</option>
            <option value="round_of_16">Octavos de Final</option>
            <option value="quarters">Cuartos de Final</option>
            <option value="semis">Semifinales</option>
            <option value="third_place">Tercer Lugar</option>
            <option value="final">Final</option>
          </select>
        </div>
      </div>
      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">📅 Fecha y Hora (HN)</label>
        <input type="datetime-local" name="kickoff_at" required style="width:100%">
      </div>
      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">🏟 Estadio</label>
        <input type="text" name="venue" placeholder="MetLife Stadium" style="width:100%">
      </div>
      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">📍 Ciudad</label>
        <input type="text" name="city" placeholder="Nueva York" style="width:100%">
      </div>
    </div>
    <button type="submit" class="btn btn-teal btn-sm">➕ Crear Partido</button>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-header">
    <div class="card-icon ci-coral">⚽</div>
    <div class="card-title">Cargar Resultados</div>
  </div>
  <div class="card-body" style="padding:0">
    @php
    $grouped = $matches->groupBy('phase');
    $phaseOrder = ['groups','round_of_32','round_of_16','quarters','semis','third_place','final'];
    @endphp
    @foreach($phaseOrder as $phaseKey)
    @if($grouped->has($phaseKey))
    <div style="padding:10px 18px 4px;font-family:'Barlow Condensed',sans-serif;font-size:10px;letter-spacing:3px;color:var(--gold);border-bottom:1px solid var(--border);text-transform:uppercase">
      {{ \App\Models\WorldMatch::phaseName($phaseKey) }}
    </div>
    @foreach($grouped[$phaseKey] as $match)
    <div class="admin-match">
      <div style="flex:1;min-width:200px">
        <div style="font-size:14px;color:var(--white);font-weight:500">
          {{ $match->homeTeam->flag }} {{ $match->homeTeam->short_name }}
          <span style="color:var(--muted);margin:0 6px">vs</span>
          {{ $match->awayTeam->short_name }} {{ $match->awayTeam->flag }}
        </div>
        <div style="font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;margin-top:2px">
          {{ $match->kickoff_at->format('d M H:i') }}
          @if($match->city) · {{ $match->city }} @endif
        </div>
      </div>

      @if($match->isFinished())
      <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:22px;color:var(--teal)">
        {{ $match->home_score }}–{{ $match->away_score }}
        @if($match->had_extra_time) <span style="font-size:11px;color:var(--muted)">ET</span> @endif
        @if($match->had_penalties)  <span style="font-size:11px;color:var(--muted)">PEN</span> @endif
      </div>
      <span class="pill pill-teal">✓ Puntuado</span>
      @else
      <form method="POST" action="{{ route('admin.match.score',$match) }}" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
        @csrf
        <input type="number" name="home_score" min="0" max="30" placeholder="0" class="admin-score-inp" required>
        <span style="font-family:'Barlow Condensed',sans-serif;font-weight:900;color:var(--muted);font-size:18px">–</span>
        <input type="number" name="away_score" min="0" max="30" placeholder="0" class="admin-score-inp" required>

        <div class="sel-wrap" style="min-width:120px">
          <select name="first_scorer_team_id">
            <option value="">1er gol</option>
            <option value="{{ $match->homeTeam->id }}">{{ $match->homeTeam->flag }} {{ $match->homeTeam->short_name }}</option>
            <option value="{{ $match->awayTeam->id }}">{{ $match->awayTeam->flag }} {{ $match->awayTeam->short_name }}</option>
          </select>
        </div>

        <label style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:4px;cursor:pointer">
          <input type="checkbox" name="had_red_card" value="1" style="accent-color:var(--coral)"> 🟥
        </label>
        @if(in_array($match->phase,['round_of_32','round_of_16','quarters','semis','third_place','final']))
        <label style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:4px;cursor:pointer">
          <input type="checkbox" name="had_extra_time" value="1"> ET
        </label>
        <label style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:4px;cursor:pointer">
          <input type="checkbox" name="had_penalties" value="1"> PEN
        </label>
        @endif
        <button type="submit" class="btn btn-coral btn-sm">Guardar</button>
      </form>
      @endif
    </div>
    @endforeach
    @endif
    @endforeach
  </div>
</div>

{{-- ── PUNTUAR MAESTRA ── --}}
<div class="card" style="border-color:rgba(201,168,76,.3)">
  <div class="card-header">
    <div class="card-icon ci-gold">🏆</div>
    <div class="card-title">Puntuar Quiniela Maestra</div>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.maestra.score') }}">
    @csrf
    @php $allTeams = \App\Models\Team::orderBy('name')->get(); @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;margin-bottom:20px">
      @foreach([['champion_id','🥇 Campeón'],['runner_up_id','🥈 Subcampeón'],['third_place_id','🥉 Tercer Lugar'],['top_scorer_team_id','⚽ Más Goleadora'],['best_defense_id','🛡 Menos Goleada'],['surprise_team_id','😱 País Sorpresa']] as [$field,$label])
      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">{{ $label }}</label>
        <div class="sel-wrap">
          <select name="{{ $field }}" {{ $field!=='surprise_team_id'?'required':'' }}>
            <option value="">— País —</option>
            @foreach($allTeams as $team)
            <option value="{{ $team->id }}">{{ $team->flag }} {{ $team->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      @endforeach

      @php
      $playerLists = [
        'golden_ball'  => ['Lionel Messi (ARG)','Kylian Mbappé (FRA)','Erling Haaland (NOR)','Vinicius Jr. (BRA)','Jude Bellingham (ENG)','Lamine Yamal (ESP)','Pedri (ESP)','Florian Wirtz (ALE)'],
        'golden_boot'  => ['Kylian Mbappé (FRA)','Erling Haaland (NOR)','Vinicius Jr. (BRA)','Lionel Messi (ARG)','Harry Kane (ENG)','Lautaro Martínez (ARG)','Cristiano Ronaldo (POR)'],
        'golden_glove' => ['Emiliano Martínez (ARG)','Thibaut Courtois (BEL)','Mike Maignan (FRA)','Alisson (BRA)','Diogo Costa (POR)','Jordan Pickford (ENG)'],
        'best_young'   => ['Lamine Yamal (ESP)','Florian Wirtz (ALE)','Warren Zaïre-Emery (FRA)','Endrick (BRA)','Alejandro Garnacho (ARG)'],
      ];
      @endphp
      @foreach([['golden_ball','🎖 Balón de Oro'],['golden_boot','👟 Bota de Oro'],['golden_glove','🧤 Guante de Oro'],['best_young','🌟 Mejor Joven']] as [$field,$label])
      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">{{ $label }}</label>
        <div class="sel-wrap">
          <select name="{{ $field }}" required>
            <option value="">— Jugador —</option>
            @foreach($playerLists[$field] as $p)
            <option value="{{ $p }}">{{ $p }}</option>
            @endforeach
          </select>
        </div>
      </div>
      @endforeach

      <div>
        <label style="font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;display:block;margin-bottom:5px;text-transform:uppercase">🔢 Total Goles Real</label>
        <input type="number" name="total_goals_real" min="50" max="400" required style="width:100px">
      </div>
    </div>

    <div class="divider">Equipos Clasificados Reales</div>
    @foreach(['round_of_32'=>['Ronda de 32',24],'semis'=>['Semifinalistas',4],'final_real_teams'=>['Finalistas',2]] as $phase=>[$label,$count])
    <div style="margin-bottom:16px">
      <div style="font-size:10px;letter-spacing:2px;color:var(--gold);font-family:'Barlow Condensed',sans-serif;margin-bottom:8px;text-transform:uppercase">{{ $label }} ({{ $count }})</div>
      <div style="display:grid;grid-template-columns:repeat({{ min($count,6) }},1fr);gap:6px">
        @for($i=0;$i<$count;$i++)
        <div class="sel-wrap" style="min-width:0">
          <select name="{{ $phase }}[]" required>
            <option value="">— País —</option>
            @foreach($allTeams as $team)
            <option value="{{ $team->id }}">{{ $team->flag }} {{ $team->name }}</option>
            @endforeach
          </select>
        </div>
        @endfor
      </div>
    </div>
    @endforeach

    <div style="text-align:center;margin-top:20px">
      <button type="submit" class="btn btn-gold">🏆 Puntuar Quinielas Maestras</button>
    </div>
    </form>
  </div>
</div>

@endsection

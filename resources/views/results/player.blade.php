<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#000000">
<title>{{ $user->name }} · Quiniela FIFA 2026</title>
<meta property="og:title" content="{{ $user->name }} · Quiniela FIFA 2026">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--black:#000;--white:#fff;--gold:#C9A84C;--gold2:#E8C96A;--card:#0F0F0F;--card2:#181818;--border:rgba(201,168,76,.2);--muted:#555;--text:#F5F0E8;--teal:#00CCAA;--red:#CC0000;--lime:#CCFF00;--purple:#6600CC}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
body{background:var(--black);color:var(--text);font-family:'Barlow',sans-serif;min-height:100vh;background-image:radial-gradient(ellipse 120% 40% at 50% 0%,rgba(201,168,76,.1) 0%,transparent 55%)}
.stripe{height:4px;background:linear-gradient(90deg,#CC0000,#FF4400,#CCCC00,#CCFF00,#00CCAA,#0044CC,#6600CC,#CC3366)}
.back-btn{display:flex;align-items:center;gap:6px;padding:12px 16px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;font-size:13px;letter-spacing:1px;text-decoration:none;border-bottom:1px solid var(--border)}
.back-btn:hover{color:var(--gold)}

/* PROFILE HEADER */
.profile-header{padding:20px 16px;text-align:center;border-bottom:1px solid var(--border)}
.profile-avatar{width:72px;height:72px;border-radius:50%;background:var(--gold);color:var(--black);font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:32px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;box-shadow:0 0 0 3px rgba(201,168,76,.3),0 0 20px rgba(201,168,76,.2)}
.profile-name{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:26px;letter-spacing:2px;color:var(--white)}
.profile-rank{font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:14px;color:var(--muted);letter-spacing:1px;margin-top:2px}
.profile-pts{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:52px;color:var(--gold);line-height:1;margin-top:8px}
.profile-pts span{font-size:20px;color:var(--muted)}
.pts-breakdown{display:flex;justify-content:center;gap:10px;margin-top:8px;flex-wrap:wrap}
.pts-chip{background:var(--card2);border:1px solid var(--border);border-radius:20px;padding:4px 12px;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:12px;letter-spacing:1px;color:var(--muted)}
.pts-chip span{color:var(--white)}

/* SECTION */
.section-title{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:12px;letter-spacing:3px;color:var(--gold);text-transform:uppercase;padding:14px 16px 8px;border-bottom:1px solid var(--border)}

/* STREAKS */
.streak-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border)}
.streak-cell{background:var(--card);padding:14px 10px;text-align:center}
.streak-val{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:28px;color:var(--white);line-height:1}
.streak-val.fire{color:#FF4400}
.streak-val.gold{color:var(--gold)}
.streak-val.teal{color:var(--teal)}
.streak-label{font-size:9px;letter-spacing:1px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;text-transform:uppercase;margin-top:4px}

/* BADGES */
.badges-wrap{padding:12px 16px;display:flex;flex-wrap:wrap;gap:8px}
.badge{display:flex;align-items:center;gap:6px;background:var(--card2);border:1px solid var(--border);border-radius:8px;padding:8px 12px}
.badge-icon{font-size:20px}
.badge-info{flex:1}
.badge-name{font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:13px;color:var(--white);letter-spacing:.5px}
.badge-desc{font-size:11px;color:var(--muted)}
.badge.gold{border-color:rgba(201,168,76,.4);background:rgba(201,168,76,.06)}
.badge.teal{border-color:rgba(0,204,170,.3);background:rgba(0,204,170,.05)}

/* PREDICTIONS LIST */
.pred-list{padding:0}
.pred-row{display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid rgba(201,168,76,.04)}
.pred-match{flex:1;min-width:0}
.pred-teams{font-size:13px;font-weight:500;color:var(--white);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pred-result{font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;margin-top:1px}
.pred-score{font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:16px;text-align:center;min-width:50px}
.pred-pts{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:20px;text-align:right;min-width:44px}
.pred-pts.exact{color:var(--teal)}
.pred-pts.good{color:var(--gold)}
.pred-pts.zero{color:var(--muted)}
.pred-badge{font-size:14px;min-width:20px;text-align:center}

/* MAESTRA PICKS */
.maestra-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:12px 16px}
.maestra-item{background:var(--card2);border:1px solid var(--border);border-radius:6px;padding:10px 12px}
.maestra-label{font-size:9px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;text-transform:uppercase;margin-bottom:4px}
.maestra-val{font-size:14px;font-weight:600;color:var(--white)}

/* SHARE */
.share-bar{display:flex;gap:8px;padding:12px 16px;border-top:1px solid var(--border);margin-top:8px}
.share-btn{flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border-radius:6px;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:13px;letter-spacing:1px;text-transform:uppercase;cursor:pointer;border:none;transition:all .2s}
.share-wa{background:#25D366;color:#fff}
</style>
</head>
<body>
<div class="stripe"></div>

<a href="{{ route('results.public') }}" class="back-btn">← Tabla General</a>

{{-- PROFILE HEADER --}}
<div class="profile-header">
  <div class="profile-avatar">{{ strtoupper(substr($user->name,0,1)) }}</div>
  <div class="profile-name">{{ $user->name }}</div>
  <div class="profile-rank">
    @if($rank !== false)
      @if($rank === 0) 👑 Líder @elseif($rank === 1) 🥈 2do lugar @elseif($rank === 2) 🥉 3er lugar
      @else Posición #{{ $rank + 1 }} @endif
      · de {{ $standings->count() }} jugadores
    @endif
  </div>
  @php $myRow = $standings->firstWhere('id',$user->id); @endphp
  <div class="profile-pts">{{ $myRow?->grand_total ?? 0 }} <span>pts</span></div>
  <div class="pts-breakdown">
    <div class="pts-chip">Maestra <span>{{ $myRow?->maestra_pts ?? 0 }}</span></div>
    <div class="pts-chip">Partidos <span>{{ $myRow?->partido_pts ?? 0 }}</span></div>
    @if(($myRow?->special_pts ?? 0) > 0)
    <div class="pts-chip">Extras <span>{{ $myRow->special_pts }}</span></div>
    @endif
  </div>
</div>

{{-- STREAKS --}}
<div class="section-title">⚡ Rachas y Estadísticas</div>
<div class="streak-grid">
  <div class="streak-cell">
    <div class="streak-val fire">{{ $streaks['current_exact'] }}</div>
    <div class="streak-label">Racha Exactos Actual</div>
  </div>
  <div class="streak-cell">
    <div class="streak-val gold">{{ $streaks['max_exact'] }}</div>
    <div class="streak-label">Mejor Racha Exactos</div>
  </div>
  <div class="streak-cell">
    <div class="streak-val teal">{{ $streaks['max_win'] }}</div>
    <div class="streak-label">Mejor Racha Aciertos</div>
  </div>
  <div class="streak-cell">
    <div class="streak-val gold">{{ $streaks['total_exact'] }}</div>
    <div class="streak-label">Total Exactos</div>
  </div>
  <div class="streak-cell">
    <div class="streak-val teal">{{ $streaks['accuracy'] }}%</div>
    <div class="streak-label">% Aciertos</div>
  </div>
  <div class="streak-cell">
    <div class="streak-val {{ $streaks['perfect_days'] > 0 ? 'gold' : '' }}">{{ $streaks['perfect_days'] }}</div>
    <div class="streak-label">Jornadas Perfectas</div>
  </div>
</div>

{{-- BADGES --}}
@if(count($badges))
<div class="section-title">🏅 Logros Desbloqueados</div>
<div class="badges-wrap">
  @foreach($badges as $badge)
  <div class="badge {{ in_array($badge[0],['💎','🏆','🧠']) ? 'gold' : (in_array($badge[0],['📊','⭐']) ? 'teal' : '') }}">
    <div class="badge-icon">{{ $badge[0] }}</div>
    <div class="badge-info">
      <div class="badge-name">{{ $badge[1] }}</div>
      <div class="badge-desc">{{ $badge[2] }}</div>
    </div>
  </div>
  @endforeach
</div>
@endif

{{-- QUINIELA MAESTRA --}}
@if($quiniela)
<div class="section-title">🏆 Quiniela Maestra</div>
<div class="maestra-grid">
  <div class="maestra-item" style="border-color:rgba(201,168,76,.3);background:rgba(201,168,76,.05)">
    <div class="maestra-label">🥇 Campeón</div>
    <div class="maestra-val">{{ $quiniela->champion?->flag }} {{ $quiniela->champion?->name ?? '—' }}</div>
  </div>
  <div class="maestra-item">
    <div class="maestra-label">🥈 Subcampeón</div>
    <div class="maestra-val">{{ $quiniela->runnerUp?->flag }} {{ $quiniela->runnerUp?->name ?? '—' }}</div>
  </div>
  <div class="maestra-item">
    <div class="maestra-label">👟 Bota de Oro</div>
    <div class="maestra-val" style="font-size:12px">{{ $quiniela->golden_boot ?? '—' }}</div>
  </div>
  <div class="maestra-item">
    <div class="maestra-label">😱 País Sorpresa</div>
    <div class="maestra-val">{{ $quiniela->surpriseTeam?->flag }} {{ $quiniela->surpriseTeam?->name ?? '—' }}</div>
  </div>
</div>
@endif

{{-- PREDICTIONS --}}
@php $scoredPreds = $predictions->filter(fn($p) => $p->scored)->sortByDesc(fn($p) => $p->match->kickoff_at); @endphp
@if($scoredPreds->count())
<div class="section-title">⚽ Pronósticos Puntuados ({{ $scoredPreds->count() }})</div>
<div class="pred-list">
  @foreach($scoredPreds as $pred)
  @php
    $isExact = $pred->pts_exact > 0;
    $isGood  = $pred->pts_result > 0;
    $ptsClass = $isExact ? 'exact' : ($isGood ? 'good' : 'zero');
    $badge = $isExact ? '🎯' : ($isGood ? '✓' : '✗');
  @endphp
  <div class="pred-row">
    <div class="pred-badge">{{ $badge }}</div>
    <div class="pred-match">
      <div class="pred-teams">
        {{ $pred->match->homeTeam->flag }} {{ $pred->match->homeTeam->short_name }}
        vs
        {{ $pred->match->awayTeam->short_name }} {{ $pred->match->awayTeam->flag }}
      </div>
      <div class="pred-result">
        Real: {{ $pred->match->home_score }}–{{ $pred->match->away_score }}
        @if($pred->pts_exact) · 🎯 Exacto @endif
        @if($pred->pts_red_card) · 🟥 @endif
        @if($pred->pts_extra_time) · ⏱ @endif
      </div>
    </div>
    <div class="pred-score" style="color:{{ $isExact ? 'var(--teal)' : ($isGood ? 'var(--gold)' : 'var(--muted)') }}">
      {{ $pred->home_score }}–{{ $pred->away_score }}
    </div>
    <div class="pred-pts {{ $ptsClass }}">+{{ $pred->total_points }}</div>
  </div>
  @endforeach
</div>
@else
<div style="padding:30px;text-align:center;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;font-size:13px">
  Aún no hay pronósticos puntuados
</div>
@endif

{{-- SHARE --}}
<div class="share-bar">
  <button class="share-btn share-wa" onclick="sharePlayer()">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    Compartir mi perfil
  </button>
</div>

<script>
function sharePlayer() {
  const url = encodeURIComponent(window.location.href);
  const pts = document.querySelector('.profile-pts')?.textContent?.trim() || '';
  const text = encodeURIComponent(`⚽ Mi perfil en la Quiniela FIFA 2026™\n🏆 {{ $user->name }} · ${pts}\n`);
  window.open('https://wa.me/?text=' + text + url, '_blank');
}
</script>
</body>
</html>

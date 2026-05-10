<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#000000">
<title>Tabla · Quiniela FIFA 2026</title>
<meta property="og:title" content="🏆 Tabla Quiniela FIFA World Cup 2026">
<meta property="og:description" content="Resultados en tiempo real de la quiniela del Mundial 2026">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --black:#000;--white:#fff;--gold:#C9A84C;--gold2:#E8C96A;
  --card:#0F0F0F;--card2:#181818;--border:rgba(201,168,76,.2);
  --muted:#555;--text:#F5F0E8;
  --red:#CC0000;--orange:#FF4400;--purple:#6600CC;
  --blue:#0044CC;--teal:#00CCAA;--lime:#CCFF00;
}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{font-size:16px}
body{background:var(--black);color:var(--text);font-family:'Barlow',sans-serif;min-height:100vh;
  background-image:radial-gradient(ellipse 120% 40% at 50% 0%,rgba(201,168,76,.1) 0%,transparent 55%)}

.stripe{height:4px;background:linear-gradient(90deg,#CC0000,#FF4400,#CCCC00,#CCFF00,#00CCAA,#0044CC,#6600CC,#CC3366)}

/* HEADER */
.header{padding:20px 16px 16px;text-align:center;border-bottom:1px solid var(--border)}
.header-logo{display:flex;align-items:center;justify-content:center;gap:6px;margin-bottom:8px}
.header-26{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:36px;color:var(--white);letter-spacing:-3px;line-height:1}
.header-trophy{font-size:28px;filter:drop-shadow(0 0 10px rgba(201,168,76,.5))}
.header-title{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:18px;letter-spacing:3px;color:var(--gold);text-transform:uppercase}
.header-sub{font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:2px;margin-top:2px}
.header-mascots{font-size:22px;margin-top:6px;display:flex;justify-content:center;gap:4px}

/* NAV TABS */
.tabs{display:flex;border-bottom:1px solid var(--border);overflow-x:auto;scrollbar-width:none}
.tabs::-webkit-scrollbar{display:none}
.tab{flex:1;min-width:80px;padding:12px 8px;text-align:center;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:12px;letter-spacing:1px;color:var(--muted);text-decoration:none;text-transform:uppercase;border-bottom:2px solid transparent;white-space:nowrap;transition:all .2s}
.tab.active{color:var(--gold);border-bottom-color:var(--gold)}
.tab:hover{color:var(--white)}

/* SHARE BUTTON */
.share-bar{display:flex;gap:8px;padding:12px 16px;border-bottom:1px solid var(--border)}
.share-btn{flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border-radius:6px;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:13px;letter-spacing:1px;text-transform:uppercase;cursor:pointer;border:none;transition:all .2s;text-decoration:none}
.share-wa{background:#25D366;color:#fff}
.share-wa:hover{background:#1ebe5d}
.share-copy{background:var(--card2);color:var(--gold);border:1px solid var(--border)}
.share-copy:hover{border-color:var(--gold)}

/* STANDINGS TABLE */
.standings{padding:0}
.standing-row{display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid rgba(201,168,76,.05);transition:background .15s;text-decoration:none;color:inherit}
.standing-row:hover{background:rgba(201,168,76,.03)}
.standing-row.me{background:rgba(201,168,76,.06);border-left:3px solid var(--gold)}
.rank{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:22px;width:36px;text-align:center;flex-shrink:0}
.rank.r1{color:var(--gold)}.rank.r2{color:#C0C0C0}.rank.r3{color:#CD7F32}.rank.rn{color:rgba(201,168,76,.25)}
.avatar{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:16px;flex-shrink:0;color:var(--black)}
.player-info{flex:1;min-width:0}
.player-name{font-weight:600;font-size:15px;color:var(--white);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.player-pick{font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pts-col{text-align:right;flex-shrink:0}
.pts-total{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:26px;color:var(--gold);line-height:1}
.pts-breakdown{font-size:10px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;margin-top:1px}

/* LEADER CARD */
.leader-card{margin:16px;background:linear-gradient(135deg,rgba(201,168,76,.15) 0%,rgba(201,168,76,.05) 100%);border:1px solid rgba(201,168,76,.3);border-radius:10px;padding:16px;text-align:center}
.leader-crown{font-size:32px;margin-bottom:4px}
.leader-name{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:24px;color:var(--gold);letter-spacing:2px}
.leader-pts{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:48px;color:var(--white);line-height:1}
.leader-pts span{font-size:18px;color:var(--muted)}
.leader-pick{font-size:12px;color:var(--muted);margin-top:4px}

/* STATS SECTION */
.section-title{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:13px;letter-spacing:3px;color:var(--gold);text-transform:uppercase;padding:14px 16px 8px;border-bottom:1px solid var(--border)}
.stat-grid{display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border)}
.stat-cell{background:var(--card);padding:12px 14px}
.stat-label{font-size:10px;letter-spacing:2px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;text-transform:uppercase;margin-bottom:4px}
.stat-val{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:22px;color:var(--white)}
.stat-val.gold{color:var(--gold)}
.stat-val.teal{color:var(--teal)}
.stat-val.red{color:var(--red)}

/* RACHA BADGES */
.badges-row{display:flex;gap:6px;flex-wrap:wrap;padding:12px 16px}
.badge{display:flex;align-items:center;gap:5px;background:var(--card2);border:1px solid var(--border);border-radius:20px;padding:5px 10px;font-size:12px;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:.5px;color:var(--text)}
.badge.gold{border-color:rgba(201,168,76,.4);background:rgba(201,168,76,.08);color:var(--gold)}
.badge.teal{border-color:rgba(0,204,170,.3);background:rgba(0,204,170,.06);color:var(--teal)}

/* LAST MATCH */
.last-match{margin:12px 16px;background:var(--card);border:1px solid var(--border);border-radius:8px;padding:14px}
.last-match-label{font-size:10px;letter-spacing:3px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;text-transform:uppercase;margin-bottom:8px}
.last-match-score{display:flex;align-items:center;justify-content:center;gap:12px}
.lm-team{text-align:center;flex:1}
.lm-flag{font-size:28px;display:block;margin-bottom:3px}
.lm-name{font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;text-transform:uppercase}
.lm-score{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:36px;color:var(--gold);letter-spacing:2px}

/* FOOTER */
.footer{padding:20px 16px;text-align:center;border-top:1px solid var(--border);margin-top:8px}
.footer-text{font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px}
.footer-link{color:var(--gold);text-decoration:none}

/* AVATAR COLORS */
.av-0{background:#CC0000}.av-1{background:#FF4400}.av-2{background:#6600CC}
.av-3{background:#0044CC}.av-4{background:#00CCAA}.av-5{background:#CC3366}
.av-6{background:#CCCC00;color:#000}.av-7{background:#CCFF00;color:#000}
.av-8{background:#006633}.av-9{background:#001A66}
</style>
</head>
<body>
<div class="stripe"></div>

{{-- HEADER --}}
<div class="header">
  <div class="header-logo">
    <span class="header-26">2</span>
    <span class="header-trophy">🏆</span>
    <span class="header-26">6</span>
  </div>
  <div class="header-title">Quiniela FIFA World Cup™</div>
  <div class="header-sub">TABLA DE POSICIONES · TIEMPO REAL</div>
  <div class="header-mascots">
    <span title="Maple">🫎</span>
    <span title="Zayu">🐆</span>
    <span title="Clutch">🦅</span>
  </div>
</div>

{{-- TABS --}}
<div class="tabs">
  <a href="{{ route('results.public') }}" class="tab active">🏅 Tabla</a>
  <a href="{{ route('results.matchday') }}" class="tab">⚽ Jornada</a>
  @auth
  <a href="{{ route('leaderboard') }}" class="tab">📊 Detalle</a>
  @endauth
</div>

{{-- SHARE BAR --}}
<div class="share-bar">
  <button class="share-btn share-wa" onclick="shareWhatsApp()">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    Compartir
  </button>
  <button class="share-btn share-copy" onclick="copyLink()">
    📋 Copiar Link
  </button>
</div>

{{-- LEADER CARD --}}
@if($standings->count())
@php $leader = $standings->first(); @endphp
<div class="leader-card">
  <div class="leader-crown">👑</div>
  <div class="leader-name">{{ $leader->name }}</div>
  <div class="leader-pts">{{ $leader->grand_total }} <span>pts</span></div>
  @if($leader->quiniela?->champion)
  <div class="leader-pick">🏆 {{ $leader->quiniela->champion->flag }} {{ $leader->quiniela->champion->name }}</div>
  @endif
</div>
@endif

{{-- LAST MATCH --}}
@if($lastMatch)
<div class="last-match">
  <div class="last-match-label">⚽ Último Resultado</div>
  <div class="last-match-score">
    <div class="lm-team">
      <span class="lm-flag">{{ $lastMatch->homeTeam->flag }}</span>
      <span class="lm-name">{{ $lastMatch->homeTeam->short_name }}</span>
    </div>
    <div class="lm-score">{{ $lastMatch->home_score }}–{{ $lastMatch->away_score }}</div>
    <div class="lm-team">
      <span class="lm-flag">{{ $lastMatch->awayTeam->flag }}</span>
      <span class="lm-name">{{ $lastMatch->awayTeam->short_name }}</span>
    </div>
  </div>
</div>
@endif

{{-- STANDINGS --}}
<div class="section-title">🏅 Clasificación General</div>
<div class="standings">
  @foreach($standings as $i => $row)
  @php
    $avatarColors = ['av-0','av-1','av-2','av-3','av-4','av-5','av-6','av-7','av-8','av-9'];
    $avClass = $avatarColors[$i % 10];
    $rankClass = $i===0?'r1':($i===1?'r2':($i===2?'r3':'rn'));
  @endphp
  <a href="{{ route('results.player',$row->id) }}" class="standing-row">
    <div class="rank {{ $rankClass }}">
      @if($i===0) 🥇 @elseif($i===1) 🥈 @elseif($i===2) 🥉 @else {{ $i+1 }} @endif
    </div>
    <div class="avatar {{ $avClass }}">{{ strtoupper(substr($row->name,0,1)) }}</div>
    <div class="player-info">
      <div class="player-name">{{ $row->name }}</div>
      <div class="player-pick">
        @if($row->quiniela?->champion)
        🏆 {{ $row->quiniela->champion->flag }} {{ $row->quiniela->champion->name }}
        @if($row->quiniela->runner_up_id) · {{ $row->quiniela->runnerUp?->flag }} {{ $row->quiniela->runnerUp?->name }} @endif
        @else
        <span style="color:rgba(255,77,61,.6)">Sin quiniela maestra</span>
        @endif
      </div>
    </div>
    <div class="pts-col">
      <div class="pts-total">{{ $row->grand_total }}</div>
      <div class="pts-breakdown">
        M:{{ $row->maestra_pts }} P:{{ $row->partido_pts }}
        @if($row->special_pts > 0) E:{{ $row->special_pts }} @endif
      </div>
    </div>
  </a>
  @endforeach
</div>

{{-- STATS HIGHLIGHTS --}}
<div class="section-title">📊 Estadísticas Destacadas</div>
<div class="stat-grid">
  @php
    $mostExact   = collect($stats)->sortByDesc('total_exact')->first();
    $bestAccuracy= collect($stats)->sortByDesc('accuracy')->first();
    $bestStreak  = collect($stats)->sortByDesc('max_exact')->first();
    $mostPerfect = collect($stats)->sortByDesc('perfect_days')->first();
  @endphp
  <div class="stat-cell">
    <div class="stat-label">🎯 Más Exactos</div>
    <div class="stat-val gold">{{ $mostExact['total_exact'] ?? 0 }}</div>
    <div style="font-size:11px;color:var(--muted);margin-top:2px">{{ $mostExact['name'] ?? '—' }}</div>
  </div>
  <div class="stat-cell">
    <div class="stat-label">📈 Mejor %</div>
    <div class="stat-val teal">{{ $bestAccuracy['accuracy'] ?? 0 }}%</div>
    <div style="font-size:11px;color:var(--muted);margin-top:2px">{{ $bestAccuracy['name'] ?? '—' }}</div>
  </div>
  <div class="stat-cell">
    <div class="stat-label">⚡ Racha Exactos</div>
    <div class="stat-val gold">{{ $bestStreak['max_exact'] ?? 0 }}</div>
    <div style="font-size:11px;color:var(--muted);margin-top:2px">{{ $bestStreak['name'] ?? '—' }}</div>
  </div>
  <div class="stat-cell">
    <div class="stat-label">⭐ Jornadas Perf.</div>
    <div class="stat-val teal">{{ $mostPerfect['perfect_days'] ?? 0 }}</div>
    <div style="font-size:11px;color:var(--muted);margin-top:2px">{{ $mostPerfect['name'] ?? '—' }}</div>
  </div>
</div>

{{-- FOOTER --}}
<div class="footer">
  <div class="footer-text">
    🫎🐆🦅 &nbsp; FIFA WORLD CUP 26™ &nbsp; 🫎🐆🦅<br>
    <span style="margin-top:6px;display:block">
      @auth
      <a href="{{ route('leaderboard') }}" class="footer-link">Ver tabla completa →</a>
      @else
      <a href="{{ route('login') }}" class="footer-link">Iniciar sesión →</a>
      @endauth
    </span>
  </div>
</div>

<script>
function shareWhatsApp() {
  const url = encodeURIComponent(window.location.href);
  const text = encodeURIComponent('🏆 Tabla Quiniela FIFA World Cup 2026™\n⚽ ¡Mira cómo vamos!\n');
  window.open('https://wa.me/?text=' + text + url, '_blank');
}
function copyLink() {
  navigator.clipboard.writeText(window.location.href).then(() => {
    const btn = document.querySelector('.share-copy');
    btn.textContent = '✓ Copiado';
    setTimeout(() => btn.textContent = '📋 Copiar Link', 2000);
  });
}
</script>
</body>
</html>

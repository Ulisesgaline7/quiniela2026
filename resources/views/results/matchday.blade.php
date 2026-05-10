<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#000000">
<title>Jornada {{ $day }} · Quiniela FIFA 2026</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--black:#000;--white:#fff;--gold:#C9A84C;--card:#0F0F0F;--card2:#181818;--border:rgba(201,168,76,.2);--muted:#555;--text:#F5F0E8;--teal:#00CCAA;--red:#CC0000}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
body{background:var(--black);color:var(--text);font-family:'Barlow',sans-serif;min-height:100vh}
.stripe{height:4px;background:linear-gradient(90deg,#CC0000,#FF4400,#CCCC00,#CCFF00,#00CCAA,#0044CC,#6600CC,#CC3366)}
.back-btn{display:flex;align-items:center;gap:6px;padding:12px 16px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;font-size:13px;letter-spacing:1px;text-decoration:none;border-bottom:1px solid var(--border)}
.header{padding:16px;border-bottom:1px solid var(--border);text-align:center}
.header-title{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:22px;letter-spacing:3px;color:var(--gold);text-transform:uppercase}
.header-sub{font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:2px;margin-top:2px}

/* DAY SELECTOR */
.day-nav{display:flex;overflow-x:auto;scrollbar-width:none;border-bottom:1px solid var(--border);padding:0 8px}
.day-nav::-webkit-scrollbar{display:none}
.day-btn{flex-shrink:0;padding:10px 14px;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:13px;letter-spacing:1px;color:var(--muted);text-decoration:none;border-bottom:2px solid transparent;white-space:nowrap;transition:all .2s}
.day-btn.active{color:var(--gold);border-bottom-color:var(--gold)}
.day-btn:hover{color:var(--white)}

/* SHARE */
.share-bar{display:flex;gap:8px;padding:10px 16px;border-bottom:1px solid var(--border)}
.share-btn{flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:9px;border-radius:6px;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:12px;letter-spacing:1px;text-transform:uppercase;cursor:pointer;border:none;transition:all .2s}
.share-wa{background:#25D366;color:#fff}
.share-copy{background:var(--card2);color:var(--gold);border:1px solid var(--border)}

/* MATCH BLOCK */
.match-block{border-bottom:2px solid var(--border);margin-bottom:0}
.match-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:rgba(201,168,76,.04)}
.match-teams{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:16px;letter-spacing:1px;color:var(--white)}
.match-score{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:20px;color:var(--gold)}
.match-meta{font-size:10px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px}

/* PREDICTIONS GRID */
.preds-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:1px;background:var(--border)}
.pred-cell{background:var(--card);padding:10px 12px;position:relative}
.pred-cell.exact{background:rgba(0,204,170,.08);border-left:2px solid var(--teal)}
.pred-cell.correct{background:rgba(201,168,76,.05);border-left:2px solid rgba(201,168,76,.4)}
.pred-cell.wrong{opacity:.7}
.pred-cell.pending{opacity:.5}
.pred-player{font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pred-score-val{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:22px;color:var(--white);line-height:1}
.pred-pts-val{font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:13px;margin-top:3px}
.pred-pts-val.exact{color:var(--teal)}
.pred-pts-val.good{color:var(--gold)}
.pred-pts-val.zero{color:var(--muted)}
.pred-pts-val.pending{color:var(--muted)}
.pred-exact-badge{position:absolute;top:6px;right:6px;font-size:12px}

/* DAY SUMMARY */
.day-summary{padding:14px 16px;border-bottom:1px solid var(--border)}
.day-summary-title{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:12px;letter-spacing:3px;color:var(--gold);text-transform:uppercase;margin-bottom:10px}
.day-ranking{display:flex;flex-direction:column;gap:6px}
.day-rank-row{display:flex;align-items:center;gap:10px;padding:8px 10px;background:var(--card2);border-radius:5px;border:1px solid var(--border)}
.day-rank-pos{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:18px;width:28px;color:rgba(201,168,76,.3)}
.day-rank-pos.r1{color:var(--gold)}.day-rank-pos.r2{color:#C0C0C0}.day-rank-pos.r3{color:#CD7F32}
.day-rank-name{flex:1;font-weight:600;font-size:14px;color:var(--white)}
.day-rank-pts{font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:20px;color:var(--gold)}
.perfect-badge{background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.3);border-radius:20px;padding:2px 8px;font-size:10px;font-family:'Barlow Condensed',sans-serif;font-weight:700;color:var(--gold);letter-spacing:1px}
</style>
</head>
<body>
<div class="stripe"></div>
<a href="{{ route('results.public') }}" class="back-btn">← Tabla General</a>

<div class="header">
  <div class="header-title">⚽ Jornada {{ $day }}</div>
  <div class="header-sub">PRONÓSTICOS DE TODOS LOS JUGADORES</div>
</div>

{{-- DAY SELECTOR --}}
<div class="day-nav">
  @foreach($days as $d)
  <a href="{{ route('results.matchday',['day'=>$d]) }}" class="day-btn {{ $d==$day?'active':'' }}">J{{ $d }}</a>
  @endforeach
</div>

{{-- SHARE --}}
<div class="share-bar">
  <button class="share-btn share-wa" onclick="shareMatchday()">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    Compartir Jornada
  </button>
  <button class="share-btn share-copy" onclick="copyLink()">📋 Copiar</button>
</div>

{{-- DAY RANKING --}}
@php
  $dayRanking = collect($grid)->sortByDesc('day_pts')->values();
  $maxDayPts  = $dayRanking->first()['day_pts'] ?? 0;
@endphp
@if($dayRanking->sum('day_pts') > 0)
<div class="day-summary">
  <div class="day-summary-title">🏅 Ranking Jornada {{ $day }}</div>
  <div class="day-ranking">
    @foreach($dayRanking->take(5) as $ri => $row)
    <div class="day-rank-row">
      <div class="day-rank-pos {{ $ri===0?'r1':($ri===1?'r2':($ri===2?'r3':'')) }}">
        @if($ri===0) 🥇 @elseif($ri===1) 🥈 @elseif($ri===2) 🥉 @else {{ $ri+1 }} @endif
      </div>
      <div class="day-rank-name">{{ $row['user']->name }}</div>
      @if($row['day_pts'] === $maxDayPts && $maxDayPts > 0 && $matches->count() > 1)
      <span class="perfect-badge">⭐ JORNADA</span>
      @endif
      <div class="day-rank-pts">{{ $row['day_pts'] }}</div>
    </div>
    @endforeach
  </div>
</div>
@endif

{{-- MATCHES WITH ALL PREDICTIONS --}}
@foreach($matches as $match)
<div class="match-block">
  <div class="match-header">
    <div>
      <div class="match-teams">
        {{ $match->homeTeam->flag }} {{ $match->homeTeam->short_name }}
        vs
        {{ $match->awayTeam->short_name }} {{ $match->awayTeam->flag }}
      </div>
      <div class="match-meta">{{ $match->kickoff_at->format('d M · H:i') }} · {{ $match->city }}</div>
    </div>
    @if($match->isFinished())
    <div class="match-score">{{ $match->home_score }}–{{ $match->away_score }}</div>
    @else
    <div style="font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px">PENDIENTE</div>
    @endif
  </div>

  <div class="preds-grid">
    @foreach($grid as $row)
    @php
      $pred = $row['preds'][$match->id] ?? null;
      $isExact   = $pred && $pred->pts_exact > 0;
      $isCorrect = $pred && ($pred->pts_result > 0 || $pred->pts_exact > 0);
      $isScored  = $pred && $pred->scored;
      $cellClass = !$pred ? 'pending' : ($isExact ? 'exact' : ($isCorrect ? 'correct' : ($isScored ? 'wrong' : 'pending')));
      $ptsClass  = !$isScored ? 'pending' : ($isExact ? 'exact' : ($isCorrect ? 'good' : 'zero'));
    @endphp
    <div class="pred-cell {{ $cellClass }}">
      @if($isExact)<div class="pred-exact-badge">🎯</div>@endif
      <div class="pred-player">{{ $row['user']->name }}</div>
      @if($pred)
        <div class="pred-score-val">{{ $pred->home_score }}–{{ $pred->away_score }}</div>
        <div class="pred-pts-val {{ $ptsClass }}">
          @if($isScored) +{{ $pred->total_points }} pts
          @else ⏳ pendiente
          @endif
        </div>
      @else
        <div class="pred-score-val" style="color:var(--muted)">—</div>
        <div class="pred-pts-val pending">sin pronóstico</div>
      @endif
    </div>
    @endforeach
  </div>
</div>
@endforeach

<div style="padding:20px;text-align:center;border-top:1px solid var(--border);margin-top:8px">
  <a href="{{ route('results.public') }}" style="color:var(--gold);font-family:'Barlow Condensed',sans-serif;font-size:13px;letter-spacing:1px;text-decoration:none">← Ver Tabla General</a>
</div>

<script>
function shareMatchday() {
  const url = encodeURIComponent(window.location.href);
  const text = encodeURIComponent('⚽ Pronósticos Jornada {{ $day }} · Quiniela FIFA 2026™\n¡Mira quién acertó!\n');
  window.open('https://wa.me/?text=' + text + url, '_blank');
}
function copyLink() {
  navigator.clipboard.writeText(window.location.href).then(() => {
    const btn = document.querySelector('.share-copy');
    btn.textContent = '✓ Copiado';
    setTimeout(() => btn.textContent = '📋 Copiar', 2000);
  });
}
</script>
</body>
</html>

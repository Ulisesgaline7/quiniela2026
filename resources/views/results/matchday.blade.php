
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#0A0A0A">
<title>Jornada {{ $day }} · Quiniela FIFA 2026</title>
<meta property="og:title" content="⚽ Jornada {{ $day }} — Quiniela FIFA World Cup 2026">
<meta property="og:description" content="Predicciones de todos los participantes para la Jornada {{ $day }}">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,400;0,600;0,700;0,800;0,900;1,800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════════
   FIFA WORLD CUP 2026 — Matchday Scoreboard
   ═══════════════════════════════════════════════════ */
:root {
  --black:   #000000;
  --bg:      #0A0A0A;
  --card:    #111111;
  --card2:   #181818;
  --card3:   #1E1E1E;
  --white:   #FFFFFF;
  --gold:    #C9A84C;
  --gold2:   #E8C96A;
  --text:    #F5F0E8;
  --muted:   #555555;
  --border:  rgba(201,168,76,.18);
  /* FIFA 2026 official palette */
  --f-red:    #CC0000;
  --f-orange: #FF4400;
  --f-yellow: #CCCC00;
  --f-lime:   #CCFF00;
  --f-teal:   #00CCAA;
  --f-blue:   #0044CC;
  --f-purple: #6600CC;
  --f-pink:   #CC3366;
  /* Result colors */
  --g-color:  #00CC66;   /* Ganó  — green  */
  --e-color:  #CCCC00;   /* Empate — yellow */
  --p-color:  #CC0000;   /* Perdió — red   */
}

*, *::before, *::after {
  margin: 0; padding: 0; box-sizing: border-box;
  -webkit-tap-highlight-color: transparent;
}
html { font-size: 16px; scroll-behavior: smooth; }
body {
  background: var(--bg);
  color: var(--text);
  font-family: 'Barlow', sans-serif;
  min-height: 100vh;
  background-image:
    radial-gradient(ellipse 140% 45% at 50% -5%, rgba(201,168,76,.10) 0%, transparent 55%),
    radial-gradient(ellipse 70% 35% at 100% 100%, rgba(0,204,170,.04) 0%, transparent 50%),
    radial-gradient(ellipse 70% 35% at 0% 80%,   rgba(102,0,204,.04) 0%, transparent 50%);
}

/* ── RAINBOW STRIPE ── */
.stripe {
  height: 5px;
  background: linear-gradient(90deg,
    var(--f-red)    0%,
    var(--f-orange) 14.3%,
    var(--f-yellow) 28.6%,
    var(--f-lime)   42.9%,
    var(--f-teal)   57.1%,
    var(--f-blue)   71.4%,
    var(--f-purple) 85.7%,
    var(--f-pink)   100%);
}

/* ── HEADER ── */
.header {
  padding: 20px 16px 16px;
  text-align: center;
  border-bottom: 1px solid var(--border);
  position: relative;
  overflow: hidden;
}
.header::before {
  content: '26';
  position: absolute;
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: clamp(160px, 28vw, 320px);
  line-height: 1;
  color: rgba(201,168,76,.04);
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none;
  letter-spacing: -8px;
  white-space: nowrap;
}
.header-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  margin-bottom: 6px;
}
.logo-num {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 38px;
  color: var(--white);
  letter-spacing: -3px;
  line-height: 1;
}
.logo-trophy {
  font-size: 28px;
  filter: drop-shadow(0 0 12px rgba(201,168,76,.6));
  margin: 0 -2px;
}
.header-title {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 17px;
  letter-spacing: 4px;
  color: var(--gold);
  text-transform: uppercase;
}
.header-sub {
  font-size: 10px;
  color: var(--muted);
  font-family: 'Barlow Condensed', sans-serif;
  letter-spacing: 3px;
  margin-top: 2px;
  text-transform: uppercase;
}
.header-mascots {
  font-size: 22px;
  margin-top: 8px;
  display: flex;
  justify-content: center;
  gap: 4px;
  filter: drop-shadow(0 2px 8px rgba(201,168,76,.3));
}

/* ── TABS ── */
.tabs {
  display: flex;
  border-bottom: 1px solid var(--border);
  overflow-x: auto;
  scrollbar-width: none;
  background: rgba(0,0,0,.4);
}
.tabs::-webkit-scrollbar { display: none; }
.tab {
  flex-shrink: 0;
  padding: 11px 14px;
  text-align: center;
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 700;
  font-size: 12px;
  letter-spacing: 1.5px;
  color: var(--muted);
  text-decoration: none;
  text-transform: uppercase;
  border-bottom: 3px solid transparent;
  white-space: nowrap;
  transition: all .2s;
}
.tab.active {
  color: var(--gold);
  border-bottom-color: var(--gold);
  background: rgba(201,168,76,.04);
}
.tab:hover:not(.active) { color: var(--white); }

/* ── DAY SELECTOR ── */
.day-selector {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 12px 16px;
  border-bottom: 1px solid var(--border);
  overflow-x: auto;
  scrollbar-width: none;
  background: rgba(0,0,0,.3);
}
.day-selector::-webkit-scrollbar { display: none; }
.day-label {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 700;
  font-size: 10px;
  letter-spacing: 3px;
  color: var(--muted);
  text-transform: uppercase;
  flex-shrink: 0;
  margin-right: 4px;
}
.day-btn {
  flex-shrink: 0;
  min-width: 40px;
  padding: 6px 12px;
  background: var(--card2);
  border: 1px solid var(--border);
  border-radius: 4px;
  color: var(--muted);
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 700;
  font-size: 13px;
  letter-spacing: 1px;
  text-decoration: none;
  text-align: center;
  transition: all .15s;
  white-space: nowrap;
}
.day-btn.active {
  background: var(--gold);
  border-color: var(--gold);
  color: var(--black);
}
.day-btn:hover:not(.active) {
  border-color: var(--gold);
  color: var(--gold);
}

/* ── SHARE BAR ── */
.share-bar {
  display: flex;
  gap: 8px;
  padding: 10px 16px;
  border-bottom: 1px solid var(--border);
}
.share-btn {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 9px 12px;
  border-radius: 5px;
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 700;
  font-size: 12px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  cursor: pointer;
  border: none;
  transition: all .2s;
  text-decoration: none;
}
.share-wa  { background: #25D366; color: #fff; }
.share-wa:hover { background: #1ebe5d; }
.share-copy { background: var(--card2); color: var(--gold); border: 1px solid var(--border); }
.share-copy:hover { border-color: var(--gold); }

/* ── MAIN CONTAINER ── */
.container {
  max-width: 900px;
  margin: 0 auto;
  padding: 16px 12px 80px;
}
@media (min-width: 768px) {
  .container { padding: 24px 20px 60px; }
}

/* ── SECTION TITLE ── */
.section-title {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 800;
  font-size: 11px;
  letter-spacing: 4px;
  color: var(--gold);
  text-transform: uppercase;
  padding: 0 0 10px;
  border-bottom: 1px solid var(--border);
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.section-title::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border);
}

/* ── MATCH BLOCK ── */
.match-block {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 20px;
  animation: fadeUp .35s ease both;
}
.match-block:nth-child(1) { animation-delay: .04s; }
.match-block:nth-child(2) { animation-delay: .08s; }
.match-block:nth-child(3) { animation-delay: .12s; }
.match-block:nth-child(4) { animation-delay: .16s; }
.match-block:nth-child(5) { animation-delay: .20s; }
.match-block:nth-child(6) { animation-delay: .24s; }

/* ── MATCH HEADER ── */
.match-head {
  display: flex;
  align-items: center;
  padding: 14px 16px 12px;
  border-bottom: 1px solid var(--border);
  background: rgba(201,168,76,.03);
  gap: 12px;
  flex-wrap: wrap;
}
.match-teams {
  display: flex;
  align-items: center;
  gap: 10px;
  flex: 1;
  min-width: 0;
}
.match-team-flag { font-size: 28px; flex-shrink: 0; }
.match-team-info { min-width: 0; }
.match-team-name {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 800;
  font-size: 16px;
  letter-spacing: 1px;
  color: var(--white);
  text-transform: uppercase;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.match-team-short {
  font-size: 10px;
  color: var(--muted);
  font-family: 'Barlow Condensed', sans-serif;
  letter-spacing: 2px;
  text-transform: uppercase;
}
.match-vs-block {
  text-align: center;
  flex-shrink: 0;
  padding: 0 6px;
}
.match-vs {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 13px;
  letter-spacing: 3px;
  color: rgba(201,168,76,.3);
  text-transform: uppercase;
}
.match-score-display {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 28px;
  color: var(--gold);
  letter-spacing: 2px;
  line-height: 1;
}
.match-score-display.pending {
  font-size: 18px;
  color: var(--muted);
  letter-spacing: 1px;
}
.match-meta-right {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 4px;
  flex-shrink: 0;
}
.match-status-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 3px 8px;
  border-radius: 3px;
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 700;
  font-size: 10px;
  letter-spacing: 2px;
  text-transform: uppercase;
}
.badge-finished {
  background: rgba(0,204,102,.1);
  border: 1px solid rgba(0,204,102,.3);
  color: #00CC66;
}
.badge-live {
  background: rgba(255,68,0,.15);
  border: 1px solid rgba(255,68,0,.4);
  color: var(--f-orange);
  animation: pulse 1.5s infinite;
}
.badge-pending {
  background: rgba(85,85,85,.15);
  border: 1px solid rgba(85,85,85,.3);
  color: var(--muted);
}
.match-time {
  font-size: 10px;
  color: var(--muted);
  font-family: 'Barlow Condensed', sans-serif;
  letter-spacing: 1px;
}

/* ── DISTRIBUTION BAR ── */
.dist-section {
  padding: 10px 16px 12px;
  border-bottom: 1px solid rgba(201,168,76,.06);
}
.dist-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 7px;
}
.dist-total {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 600;
  font-size: 10px;
  letter-spacing: 2px;
  color: var(--muted);
  text-transform: uppercase;
}
.dist-total strong {
  color: var(--white);
  font-weight: 800;
}
.dist-bar-wrap {
  display: flex;
  height: 22px;
  border-radius: 4px;
  overflow: hidden;
  gap: 2px;
  margin-bottom: 6px;
}
.dist-seg {
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 800;
  font-size: 11px;
  letter-spacing: .5px;
  color: rgba(0,0,0,.85);
  transition: width .6s cubic-bezier(.4,0,.2,1);
  min-width: 0;
  overflow: hidden;
  white-space: nowrap;
}
.dist-seg.seg-g { background: var(--g-color); }
.dist-seg.seg-e { background: var(--e-color); }
.dist-seg.seg-p { background: var(--p-color); color: rgba(255,255,255,.85); }
.dist-labels {
  display: flex;
  gap: 12px;
}
.dist-label-item {
  display: flex;
  align-items: center;
  gap: 5px;
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 700;
  font-size: 11px;
  letter-spacing: 1px;
  color: var(--muted);
}
.dist-dot {
  width: 8px; height: 8px;
  border-radius: 2px;
  flex-shrink: 0;
}
.dot-g { background: var(--g-color); }
.dot-e { background: var(--e-color); }
.dot-p { background: var(--p-color); }
.dist-label-item strong { color: var(--white); }

/* ── PARTICIPANTS GRID ── */
.participants-section {
  padding: 12px 14px 14px;
}
.participants-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 6px;
}
@media (min-width: 480px) {
  .participants-grid { grid-template-columns: repeat(3, 1fr); gap: 7px; }
}
@media (min-width: 640px) {
  .participants-grid { grid-template-columns: repeat(4, 1fr); gap: 8px; }
}
@media (min-width: 900px) {
  .participants-grid { grid-template-columns: repeat(5, 1fr); gap: 8px; }
}

/* ── PARTICIPANT CARD ── */
.part-card {
  background: var(--card2);
  border: 1px solid rgba(201,168,76,.08);
  border-radius: 6px;
  padding: 8px 8px 7px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  position: relative;
  transition: border-color .15s, transform .15s;
  min-width: 0;
}
.part-card:hover {
  border-color: rgba(201,168,76,.25);
  transform: translateY(-1px);
}
.part-card.result-g { border-color: rgba(0,204,102,.25); background: rgba(0,204,102,.04); }
.part-card.result-e { border-color: rgba(204,204,0,.25); background: rgba(204,204,0,.04); }
.part-card.result-p { border-color: rgba(204,0,0,.2);   background: rgba(204,0,0,.04); }

.part-avatar {
  width: 30px; height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 14px;
  color: var(--black);
  flex-shrink: 0;
}
.part-name {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 700;
  font-size: 11px;
  letter-spacing: .5px;
  color: var(--text);
  text-align: center;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  width: 100%;
  line-height: 1.2;
}
.part-score {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 16px;
  color: var(--white);
  letter-spacing: 1px;
  line-height: 1;
}
.part-score.no-pred {
  font-size: 12px;
  color: var(--muted);
  font-weight: 600;
  letter-spacing: 0;
}
.part-result-letter {
  width: 22px; height: 22px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 13px;
  letter-spacing: 0;
  flex-shrink: 0;
}
.letter-g { background: var(--g-color); color: #000; }
.letter-e { background: var(--e-color); color: #000; }
.letter-p { background: var(--p-color); color: #fff; }
.letter-none {
  background: rgba(85,85,85,.2);
  border: 1px solid rgba(85,85,85,.3);
  color: var(--muted);
  font-size: 11px;
}

/* ── POINTS BADGE on card ── */
.part-pts {
  position: absolute;
  top: 4px; right: 4px;
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 800;
  font-size: 10px;
  color: var(--gold);
  letter-spacing: .5px;
  line-height: 1;
}

/* ── EMPTY STATE ── */
.empty-state {
  text-align: center;
  padding: 48px 20px;
  color: var(--muted);
}
.empty-state-icon { font-size: 48px; margin-bottom: 12px; }
.empty-state-text {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 700;
  font-size: 16px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--muted);
}

/* ── DAY SUMMARY BAR ── */
.day-summary {
  display: flex;
  gap: 8px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}
.day-stat {
  flex: 1;
  min-width: 80px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 10px 12px;
  text-align: center;
}
.day-stat-val {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 26px;
  line-height: 1;
  color: var(--white);
}
.day-stat-val.gold  { color: var(--gold); }
.day-stat-val.teal  { color: var(--f-teal); }
.day-stat-val.green { color: var(--g-color); }
.day-stat-label {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 600;
  font-size: 9px;
  letter-spacing: 2px;
  color: var(--muted);
  text-transform: uppercase;
  margin-top: 3px;
}

/* ── LEADERBOARD MINI (day ranking) ── */
.day-ranking {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 20px;
}
.day-ranking-header {
  padding: 10px 16px;
  border-bottom: 1px solid var(--border);
  background: rgba(201,168,76,.03);
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 800;
  font-size: 11px;
  letter-spacing: 3px;
  color: var(--gold);
  text-transform: uppercase;
}
.day-rank-row {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 16px;
  border-bottom: 1px solid rgba(201,168,76,.04);
  transition: background .15s;
}
.day-rank-row:last-child { border-bottom: none; }
.day-rank-row:hover { background: rgba(201,168,76,.025); }
.day-rank-num {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 20px;
  width: 32px;
  text-align: center;
  flex-shrink: 0;
  color: rgba(201,168,76,.2);
}
.day-rank-num.r1 { color: var(--gold); }
.day-rank-num.r2 { color: #C0C0C0; }
.day-rank-num.r3 { color: #CD7F32; }
.day-rank-avatar {
  width: 32px; height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 14px;
  color: var(--black);
  flex-shrink: 0;
}
.day-rank-name {
  flex: 1;
  font-weight: 600;
  font-size: 14px;
  color: var(--white);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.day-rank-pts {
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 900;
  font-size: 22px;
  color: var(--gold);
  flex-shrink: 0;
}
.day-rank-pts small {
  font-family: 'Barlow', sans-serif;
  font-size: 10px;
  color: var(--muted);
  font-weight: 400;
}

/* ── FOOTER ── */
.footer {
  padding: 20px 16px;
  text-align: center;
  border-top: 1px solid var(--border);
  margin-top: 8px;
}
.footer-text {
  font-size: 11px;
  color: var(--muted);
  font-family: 'Barlow Condensed', sans-serif;
  letter-spacing: 1px;
}
.footer-link { color: var(--gold); text-decoration: none; }

/* ── AVATAR PALETTE ── */
.av-0  { background: #CC0000; }
.av-1  { background: #FF4400; }
.av-2  { background: #6600CC; }
.av-3  { background: #0044CC; }
.av-4  { background: #00CCAA; color: #000; }
.av-5  { background: #CC3366; }
.av-6  { background: #CCCC00; color: #000; }
.av-7  { background: #CCFF00; color: #000; }
.av-8  { background: #006633; }
.av-9  { background: #001A66; }
.av-10 { background: #7A0000; }
.av-11 { background: #330066; }
.av-12 { background: #003319; }
.av-13 { background: #9966CC; }
.av-14 { background: #FF6633; }
.av-15 { background: #0099CC; }

/* ── ANIMATIONS ── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50%       { opacity: .55; }
}

/* ── RESPONSIVE TWEAKS ── */
@media (max-width: 400px) {
  .participants-grid { grid-template-columns: repeat(2, 1fr); }
  .part-score { font-size: 14px; }
  .part-name  { font-size: 10px; }
}
</style>
</head>
<body>
<div class="stripe"></div>

{{-- ═══════════════════════════════════════════════════
     HEADER
     ═══════════════════════════════════════════════════ --}}
<div class="header">
  <div class="header-logo">
    <span class="logo-num">2</span>
    <span class="logo-trophy">🏆</span>
    <span class="logo-num">6</span>
  </div>
  <div class="header-title">Quiniela FIFA World Cup™</div>
  <div class="header-sub">JORNADA {{ $day }} · PREDICCIONES</div>
  <div class="header-mascots">
    <span title="Maple (Canadá)">🫎</span>
    <span title="Zayu (México)">🐆</span>
    <span title="Clutch (USA)">🦅</span>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════
     NAVIGATION TABS
     ═══════════════════════════════════════════════════ --}}
<div class="tabs">
  <a href="{{ route('results.public') }}"   class="tab">🏅 Tabla</a>
  <a href="{{ route('results.matchday') }}" class="tab active">⚽ Jornada</a>
  @auth
  <a href="{{ route('leaderboard') }}" class="tab">📊 Detalle</a>
  @endauth
</div>

{{-- ═══════════════════════════════════════════════════
     DAY SELECTOR
     ═══════════════════════════════════════════════════ --}}
<div class="day-selector">
  <span class="day-label">Jornada</span>
  @foreach($days as $d)
  <a href="{{ route('results.matchday', ['day' => $d]) }}"
     class="day-btn {{ $d == $day ? 'active' : '' }}">
    J{{ $d }}
  </a>
  @endforeach
</div>

{{-- ═══════════════════════════════════════════════════
     SHARE BAR
     ═══════════════════════════════════════════════════ --}}
<div class="share-bar">
  <button class="share-btn share-wa" onclick="shareWhatsApp()">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
    WhatsApp
  </button>
  <button class="share-btn share-copy" onclick="copyLink(this)">
    📋 Copiar Link
  </button>
</div>

{{-- ═══════════════════════════════════════════════════
     MAIN CONTENT
     ═══════════════════════════════════════════════════ --}}
<div class="container">

@php
  /* ── Avatar color palette ── */
  $avPalette = ['av-0','av-1','av-2','av-3','av-4','av-5','av-6','av-7',
                'av-8','av-9','av-10','av-11','av-12','av-13','av-14','av-15'];

  /* ── Build a user-index map for consistent avatar colors ── */
  $userIndex = [];
  foreach ($grid as $i => $row) {
    $userIndex[$row['user']->id] = $i;
  }

  /* ── Day-level stats ── */
  $totalMatches    = $matches->count();
  $finishedMatches = $matches->filter(fn($m) => $m->isFinished())->count();
  $totalPreds      = 0;
  foreach ($matches as $m) { $totalPreds += $m->predictions->count(); }
@endphp

{{-- ── DAY SUMMARY STATS ── --}}
@if($matches->count())
<div class="day-summary">
  <div class="day-stat">
    <div class="day-stat-val gold">{{ $totalMatches }}</div>
    <div class="day-stat-label">Partidos</div>
  </div>
  <div class="day-stat">
    <div class="day-stat-val teal">{{ $finishedMatches }}</div>
    <div class="day-stat-label">Jugados</div>
  </div>
  <div class="day-stat">
    <div class="day-stat-val">{{ count($grid) }}</div>
    <div class="day-stat-label">Participantes</div>
  </div>
  <div class="day-stat">
    <div class="day-stat-val green">{{ $totalPreds }}</div>
    <div class="day-stat-label">Predicciones</div>
  </div>
</div>
@endif

{{-- ── DAY RANKING (only if at least one match is finished) ── --}}
@if($finishedMatches > 0 && count($grid) > 0)
<div class="day-ranking">
  <div class="day-ranking-header">🏅 Ranking Jornada {{ $day }}</div>
  @foreach($grid as $i => $row)
  @php
    $rankClass = $i === 0 ? 'r1' : ($i === 1 ? 'r2' : ($i === 2 ? 'r3' : ''));
    $avClass   = $avPalette[$userIndex[$row['user']->id] % count($avPalette)];
    $rankEmoji = $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : ($i + 1)));
  @endphp
  <div class="day-rank-row">
    <div class="day-rank-num {{ $rankClass }}">{{ $rankEmoji }}</div>
    <div class="day-rank-avatar {{ $avClass }}">
      {{ strtoupper(substr($row['user']->name, 0, 1)) }}
    </div>
    <div class="day-rank-name">{{ $row['user']->name }}</div>
    <div class="day-rank-pts">
      {{ $row['day_pts'] }} <small>pts</small>
    </div>
  </div>
  @endforeach
</div>
@endif

{{-- ═══════════════════════════════════════════════════
     MATCH BLOCKS
     ═══════════════════════════════════════════════════ --}}
@if($matches->isEmpty())
  <div class="empty-state">
    <div class="empty-state-icon">⚽</div>
    <div class="empty-state-text">No hay partidos en esta jornada</div>
  </div>
@else

@foreach($matches as $match)
@php
  /* ── Match result ── */
  $matchResult   = $match->result;   // 'home' | 'away' | 'draw' | null
  $isFinished    = $match->isFinished();
  $isLive        = $match->status === 'live' || $match->status === 'in_progress';

  /* ── Prediction counts ── */
  $preds         = $match->predictions;
  $totalMatchPreds = $preds->count();
  $countHome     = $preds->where('result', 'home')->count();
  $countDraw     = $preds->where('result', 'draw')->count();
  $countAway     = $preds->where('result', 'away')->count();

  $pctHome = $totalMatchPreds > 0 ? round($countHome / $totalMatchPreds * 100) : 0;
  $pctDraw = $totalMatchPreds > 0 ? round($countDraw / $totalMatchPreds * 100) : 0;
  $pctAway = $totalMatchPreds > 0 ? round($countAway / $totalMatchPreds * 100) : 0;

  /* ── Determine G/E/P label for a prediction ──
     G = Ganó  (predicted result matches actual)
     E = Empate (both predicted draw and it was a draw)
     P = Perdió (wrong result)
     null = match not finished yet
  ── */
  $getResultLetter = function($pred) use ($matchResult, $isFinished) {
    if (!$isFinished || $matchResult === null) return null;
    if (!$pred) return null;
    $predResult = $pred->result;
    if ($predResult === $matchResult) {
      return $matchResult === 'draw' ? 'E' : 'G';
    }
    return 'P';
  };

  /* ── Status badge ── */
  $statusLabel = $isFinished ? 'Finalizado' : ($isLive ? 'En Vivo' : 'Pendiente');
  $statusClass = $isFinished ? 'badge-finished' : ($isLive ? 'badge-live' : 'badge-pending');
  $statusIcon  = $isFinished ? '✓' : ($isLive ? '●' : '○');
@endphp

<div class="match-block">

  {{-- ── MATCH HEADER ── --}}
  <div class="match-head">
    {{-- Home team --}}
    <div class="match-teams">
      <span class="match-team-flag">{{ $match->homeTeam->flag }}</span>
      <div class="match-team-info">
        <div class="match-team-name">{{ $match->homeTeam->short_name ?? $match->homeTeam->name }}</div>
        <div class="match-team-short">Local</div>
      </div>
    </div>

    {{-- Score / VS --}}
    <div class="match-vs-block">
      @if($isFinished)
        <div class="match-score-display">
          {{ $match->home_score }}&ndash;{{ $match->away_score }}
        </div>
      @elseif($isLive)
        <div class="match-score-display">
          {{ $match->home_score ?? '?' }}&ndash;{{ $match->away_score ?? '?' }}
        </div>
      @else
        <div class="match-vs">VS</div>
        @if($match->kickoff_at)
        <div class="match-time">{{ $match->kickoff_at->format('d/m H:i') }}</div>
        @endif
      @endif
    </div>

    {{-- Away team --}}
    <div class="match-teams" style="flex-direction:row-reverse;text-align:right">
      <span class="match-team-flag">{{ $match->awayTeam->flag }}</span>
      <div class="match-team-info" style="text-align:right">
        <div class="match-team-name">{{ $match->awayTeam->short_name ?? $match->awayTeam->name }}</div>
        <div class="match-team-short">Visitante</div>
      </div>
    </div>

    {{-- Status badge --}}
    <div class="match-meta-right">
      <span class="match-status-badge {{ $statusClass }}">
        {{ $statusIcon }} {{ $statusLabel }}
      </span>
      @if($match->group_name)
      <span style="font-size:10px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px">
        GRUPO {{ $match->group_name }}
      </span>
      @endif
    </div>
  </div>

  {{-- ── DISTRIBUTION BAR ── --}}
  <div class="dist-section">
    <div class="dist-header">
      <span class="dist-total">
        <strong>{{ $totalMatchPreds }}</strong> predicciones
      </span>
      @if($totalMatchPreds === 0)
      <span style="font-size:10px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;letter-spacing:1px">
        Sin predicciones aún
      </span>
      @endif
    </div>

    @if($totalMatchPreds > 0)
    <div class="dist-bar-wrap">
      @if($pctHome > 0)
      <div class="dist-seg seg-g" style="width:{{ $pctHome }}%">
        @if($pctHome >= 12) {{ $pctHome }}% @endif
      </div>
      @endif
      @if($pctDraw > 0)
      <div class="dist-seg seg-e" style="width:{{ $pctDraw }}%">
        @if($pctDraw >= 12) {{ $pctDraw }}% @endif
      </div>
      @endif
      @if($pctAway > 0)
      <div class="dist-seg seg-p" style="width:{{ $pctAway }}%">
        @if($pctAway >= 12) {{ $pctAway }}% @endif
      </div>
      @endif
    </div>
    <div class="dist-labels">
      <div class="dist-label-item">
        <span class="dist-dot dot-g"></span>
        <span>G <strong>{{ $pctHome }}%</strong></span>
        <span style="color:rgba(85,85,85,.6)">({{ $countHome }})</span>
      </div>
      <div class="dist-label-item">
        <span class="dist-dot dot-e"></span>
        <span>E <strong>{{ $pctDraw }}%</strong></span>
        <span style="color:rgba(85,85,85,.6)">({{ $countDraw }})</span>
      </div>
      <div class="dist-label-item">
        <span class="dist-dot dot-p"></span>
        <span>P <strong>{{ $pctAway }}%</strong></span>
        <span style="color:rgba(85,85,85,.6)">({{ $countAway }})</span>
      </div>
    </div>
    @endif
  </div>

  {{-- ── PARTICIPANTS GRID ── --}}
  <div class="participants-section">
    <div class="participants-grid">
      @foreach($grid as $i => $row)
      @php
        $user      = $row['user'];
        $pred      = $row['preds'][$match->id] ?? null;
        $letter    = $getResultLetter($pred);
        $avClass   = $avPalette[$userIndex[$user->id] % count($avPalette)];

        /* Card border class based on result */
        $cardClass = '';
        if ($letter === 'G') $cardClass = 'result-g';
        elseif ($letter === 'E') $cardClass = 'result-e';
        elseif ($letter === 'P') $cardClass = 'result-p';

        /* Points for this match */
        $matchPts = $pred?->total_points ?? 0;
      @endphp
      <div class="part-card {{ $cardClass }}">
        {{-- Points badge (only if match finished and pred exists) --}}
        @if($isFinished && $pred && $matchPts > 0)
        <div class="part-pts">+{{ $matchPts }}</div>
        @endif

        {{-- Avatar --}}
        <div class="part-avatar {{ $avClass }}">
          {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>

        {{-- Name --}}
        <div class="part-name" title="{{ $user->name }}">
          {{ $user->username ?? explode(' ', $user->name)[0] }}
        </div>

        {{-- Predicted score --}}
        @if($pred)
          <div class="part-score">{{ $pred->home_score }}&ndash;{{ $pred->away_score }}</div>
        @else
          <div class="part-score no-pred">—</div>
        @endif

        {{-- Result letter --}}
        @if($letter === 'G')
          <div class="part-result-letter letter-g">G</div>
        @elseif($letter === 'E')
          <div class="part-result-letter letter-e">E</div>
        @elseif($letter === 'P')
          <div class="part-result-letter letter-p">P</div>
        @elseif($pred && !$isFinished)
          <div class="part-result-letter letter-none">?</div>
        @else
          <div class="part-result-letter letter-none">–</div>
        @endif
      </div>
      @endforeach
    </div>
  </div>

</div>{{-- /.match-block --}}
@endforeach

@endif {{-- end if matches not empty --}}

{{-- ── LEGEND ── --}}
<div style="display:flex;gap:12px;flex-wrap:wrap;padding:4px 0 20px;justify-content:center">
  <div style="display:flex;align-items:center;gap:6px;font-family:'Barlow Condensed',sans-serif;font-size:11px;letter-spacing:1px;color:var(--muted)">
    <div style="width:18px;height:18px;border-radius:3px;background:var(--g-color);display:flex;align-items:center;justify-content:center;font-weight:900;font-size:11px;color:#000">G</div>
    Ganó (resultado correcto)
  </div>
  <div style="display:flex;align-items:center;gap:6px;font-family:'Barlow Condensed',sans-serif;font-size:11px;letter-spacing:1px;color:var(--muted)">
    <div style="width:18px;height:18px;border-radius:3px;background:var(--e-color);display:flex;align-items:center;justify-content:center;font-weight:900;font-size:11px;color:#000">E</div>
    Empate acertado
  </div>
  <div style="display:flex;align-items:center;gap:6px;font-family:'Barlow Condensed',sans-serif;font-size:11px;letter-spacing:1px;color:var(--muted)">
    <div style="width:18px;height:18px;border-radius:3px;background:var(--p-color);display:flex;align-items:center;justify-content:center;font-weight:900;font-size:11px;color:#fff">P</div>
    Perdió (resultado incorrecto)
  </div>
  <div style="display:flex;align-items:center;gap:6px;font-family:'Barlow Condensed',sans-serif;font-size:11px;letter-spacing:1px;color:var(--muted)">
    <div style="width:18px;height:18px;border-radius:3px;background:rgba(85,85,85,.2);border:1px solid rgba(85,85,85,.3);display:flex;align-items:center;justify-content:center;font-weight:900;font-size:11px;color:var(--muted)">?</div>
    Pendiente
  </div>
</div>

{{-- ── FOOTER ── --}}
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

</div>{{-- /.container --}}

<script>
function shareWhatsApp() {
  const day  = {{ $day }};
  const url  = encodeURIComponent(window.location.href);
  const text = encodeURIComponent(
    '⚽ Jornada ' + day + ' — Quiniela FIFA World Cup 2026™\n' +
    '🫎🐆🦅 ¡Mira las predicciones!\n'
  );
  window.open('https://wa.me/?text=' + text + url, '_blank');
}

function copyLink(btn) {
  navigator.clipboard.writeText(window.location.href).then(() => {
    const orig = btn.textContent;
    btn.textContent = '✓ Copiado';
    setTimeout(() => { btn.textContent = orig; }, 2000);
  }).catch(() => {
    /* fallback for older browsers */
    const ta = document.createElement('textarea');
    ta.value = window.location.href;
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    document.body.removeChild(ta);
    const orig = btn.textContent;
    btn.textContent = '✓ Copiado';
    setTimeout(() => { btn.textContent = orig; }, 2000);
  });
}
</script>
</body>
</html>

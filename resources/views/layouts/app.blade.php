<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Quiniela') — FIFA WORLD CUP 26™</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,400;0,600;0,700;0,800;0,900;1,800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
:root{
  --black:#0A0A0A; --white:#F2EDE4; --gold:#C9A84C; --gold2:#E8C96A;
  --coral:#FF4D3D; --teal:#00B8A9; --purple:#6B3FA0; --lime:#AADD00;
  --card:#141414; --card2:#1C1C1C; --border:rgba(201,168,76,.18);
  --muted:#5A5A5A; --text:#F2EDE4; --red:#E03030;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{
  background:var(--black);color:var(--text);
  font-family:'Barlow',sans-serif;min-height:100vh;
  background-image:
    radial-gradient(ellipse 120% 40% at 50% 0%,rgba(201,168,76,.10) 0%,transparent 60%),
    radial-gradient(ellipse 60% 30% at 100% 100%,rgba(0,184,169,.06) 0%,transparent 50%),
    radial-gradient(ellipse 60% 30% at 0% 80%,rgba(107,63,160,.06) 0%,transparent 50%);
}
/* ── NAV ── */
.nav{
  display:flex;align-items:center;justify-content:space-between;
  padding:0 28px;height:60px;position:sticky;top:0;z-index:200;
  background:rgba(10,10,10,.96);backdrop-filter:blur(20px);
  border-bottom:1px solid var(--border);
}
.nav-brand{display:flex;align-items:center;gap:0;text-decoration:none}
.nav-brand-fifa{
  background:var(--gold);color:var(--black);
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:13px;letter-spacing:3px;padding:5px 10px 4px;
  clip-path:polygon(6px 0,100% 0,calc(100% - 6px) 100%,0 100%);
}
.nav-brand-wc{
  font-family:'Barlow Condensed',sans-serif;font-weight:800;
  font-size:16px;letter-spacing:2px;color:var(--white);
  padding-left:10px;text-transform:uppercase;
}
.nav-brand-wc em{color:var(--coral);font-style:normal}
.nav-links{display:flex;gap:0;align-items:center}
.nav-link{
  color:var(--muted);font-family:'Barlow Condensed',sans-serif;
  font-weight:600;font-size:13px;letter-spacing:2px;
  padding:0 14px;height:60px;display:flex;align-items:center;
  text-decoration:none;text-transform:uppercase;
  border-bottom:3px solid transparent;transition:all .2s;
}
.nav-link:hover{color:var(--white);border-bottom-color:rgba(201,168,76,.4)}
.nav-link.active{color:var(--gold);border-bottom-color:var(--gold)}
.nav-link.admin{color:var(--coral)}
.nav-right{display:flex;align-items:center;gap:12px}
.nav-user-name{
  font-family:'Barlow Condensed',sans-serif;font-weight:700;
  font-size:13px;letter-spacing:1px;color:var(--white)
}
.nav-logout{
  background:none;border:1px solid rgba(201,168,76,.25);
  color:var(--muted);cursor:pointer;font-family:'Barlow Condensed',sans-serif;
  font-size:11px;letter-spacing:2px;padding:5px 12px;
  text-transform:uppercase;transition:all .2s;
}
.nav-logout:hover{border-color:var(--coral);color:var(--coral)}
/* ── CONTAINER ── */
.container{max-width:1000px;margin:0 auto;padding:36px 20px 100px}
/* ── ALERTS ── */
.alert{
  padding:12px 16px;margin-bottom:20px;
  font-family:'Barlow Condensed',sans-serif;font-size:14px;letter-spacing:1px;
  display:flex;align-items:center;gap:10px;border-left:3px solid;
}
.alert-success{background:rgba(0,184,169,.08);border-color:var(--teal);color:var(--teal)}
.alert-error{background:rgba(255,77,61,.08);border-color:var(--coral);color:var(--coral)}
/* ── HERO ── */
.page-hero{
  position:relative;overflow:hidden;
  padding:48px 0 40px;margin-bottom:40px;text-align:center;
}
.page-hero::before{
  content:'26';position:absolute;
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:clamp(200px,30vw,380px);line-height:1;
  color:rgba(201,168,76,.04);top:50%;left:50%;
  transform:translate(-50%,-50%);pointer-events:none;letter-spacing:-10px;
  white-space:nowrap;
}
.hero-eyebrow{
  font-family:'Barlow Condensed',sans-serif;font-weight:600;
  font-size:11px;letter-spacing:6px;color:var(--teal);
  text-transform:uppercase;margin-bottom:12px;
}
.hero-title{
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:clamp(52px,10vw,96px);line-height:.88;
  text-transform:uppercase;letter-spacing:-1px;color:var(--white);
}
.hero-title .outline{
  color:transparent;-webkit-text-stroke:2px var(--gold);
}
.hero-title .fill{color:var(--gold)}
.hero-sub{
  font-size:14px;color:var(--muted);margin-top:14px;
  max-width:460px;margin-left:auto;margin-right:auto;line-height:1.7;
}
.hero-mascots{font-size:32px;margin-bottom:10px;display:flex;justify-content:center;gap:6px}
/* ── CARDS ── */
.card{
  background:var(--card);border:1px solid var(--border);
  border-radius:6px;overflow:hidden;margin-bottom:18px;
  animation:fadeUp .3s ease both;
}
.card:nth-child(1){animation-delay:.04s}.card:nth-child(2){animation-delay:.08s}
.card:nth-child(3){animation-delay:.12s}.card:nth-child(4){animation-delay:.16s}
.card:nth-child(5){animation-delay:.20s}
.card-header{
  display:flex;align-items:center;gap:12px;padding:13px 20px;
  border-bottom:1px solid var(--border);background:rgba(201,168,76,.03);
}
.card-icon{
  width:32px;height:32px;border-radius:5px;display:flex;
  align-items:center;justify-content:center;font-size:16px;flex-shrink:0;
}
.ci-gold{background:var(--gold)}.ci-coral{background:var(--coral)}
.ci-teal{background:var(--teal)}.ci-purple{background:var(--purple)}
.ci-lime{background:var(--lime);color:#000}
.card-title{
  font-family:'Barlow Condensed',sans-serif;font-weight:700;
  font-size:14px;letter-spacing:2px;color:var(--white);text-transform:uppercase;
}
.card-pts{
  margin-left:auto;font-family:'Barlow Condensed',sans-serif;
  font-weight:900;font-size:22px;color:var(--gold);letter-spacing:1px;
}
.card-pts small{font-family:'Barlow',sans-serif;font-size:10px;color:var(--muted);font-weight:400;letter-spacing:0}
.card-body{padding:18px 20px}
/* ── FORM ── */
.input-row{
  display:flex;align-items:center;gap:12px;padding:11px 0;
  border-bottom:1px solid rgba(201,168,76,.05);
}
.input-row:last-child{border-bottom:none}
.input-label{flex:1;font-size:13px;color:var(--muted)}
.input-label strong{color:var(--white);display:block;font-size:14px;margin-bottom:1px;font-weight:600}
.sel-wrap{position:relative;min-width:190px}
.sel-wrap::after{
  content:'▾';position:absolute;right:9px;top:50%;
  transform:translateY(-50%);color:var(--gold);pointer-events:none;font-size:11px;
}
select{
  width:100%;background:var(--card2);border:1px solid var(--border);
  border-radius:4px;color:var(--text);font-family:'Barlow',sans-serif;
  font-size:13px;padding:8px 26px 8px 11px;cursor:pointer;
  outline:none;appearance:none;transition:border-color .2s;
}
select:focus{border-color:var(--gold)}
select option{background:#1C1C1C}
input[type=number],input[type=text],input[type=email],input[type=password]{
  background:var(--card2);border:1px solid var(--border);border-radius:4px;
  color:var(--text);font-family:'Barlow',sans-serif;font-size:14px;
  padding:9px 12px;outline:none;transition:border-color .2s;width:100%;
}
input[type=number]{
  font-family:'Barlow Condensed',sans-serif;font-weight:700;
  font-size:22px;width:80px;text-align:center;-moz-appearance:textfield;
}
input[type=number]::-webkit-inner-spin-button{-webkit-appearance:none}
input:focus{border-color:var(--gold)}
/* ── BUTTONS ── */
.btn{
  border:none;font-family:'Barlow Condensed',sans-serif;font-weight:800;
  font-size:16px;letter-spacing:3px;padding:13px 44px;
  cursor:pointer;transition:all .2s;display:inline-block;
  text-decoration:none;text-transform:uppercase;
  clip-path:polygon(10px 0,100% 0,calc(100% - 10px) 100%,0 100%);
}
.btn-gold{background:var(--gold);color:var(--black)}
.btn-gold:hover{background:var(--gold2);transform:translateY(-2px);box-shadow:0 8px 28px rgba(201,168,76,.35)}
.btn-coral{background:var(--coral);color:#fff}
.btn-coral:hover{background:#ff3322;transform:translateY(-2px);box-shadow:0 8px 28px rgba(255,77,61,.35)}
.btn-teal{background:var(--teal);color:var(--black)}
.btn-teal:hover{background:#00a096;transform:translateY(-2px)}
.btn-sm{font-size:12px;padding:7px 16px;letter-spacing:1px;clip-path:none;border-radius:3px}
.btn-outline{background:none;border:1px solid var(--border);color:var(--muted);clip-path:none;border-radius:3px}
.btn-outline:hover{border-color:var(--gold);color:var(--gold)}
/* ── PILLS ── */
.pill{
  display:inline-flex;align-items:center;gap:4px;
  border-radius:3px;padding:3px 9px;
  font-family:'Barlow Condensed',sans-serif;font-weight:700;
  font-size:12px;letter-spacing:1px;
}
.pill-gold{background:rgba(201,168,76,.12);border:1px solid rgba(201,168,76,.25);color:var(--gold)}
.pill-teal{background:rgba(0,184,169,.10);border:1px solid rgba(0,184,169,.25);color:var(--teal)}
.pill-coral{background:rgba(255,77,61,.10);border:1px solid rgba(255,77,61,.25);color:var(--coral)}
.pill-lime{background:rgba(170,221,0,.10);border:1px solid rgba(170,221,0,.25);color:var(--lime)}
.pill-purple{background:rgba(107,63,160,.12);border:1px solid rgba(107,63,160,.3);color:#a07de0}
/* ── DIVIDER ── */
.divider{
  display:flex;align-items:center;gap:12px;margin:24px 0 18px;
  color:var(--muted);font-size:10px;letter-spacing:4px;
  font-family:'Barlow Condensed',sans-serif;font-weight:600;text-transform:uppercase;
}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border)}
/* ── TOGGLE ── */
.toggle{position:relative;width:42px;height:22px;flex-shrink:0}
.toggle input{opacity:0;width:0;height:0}
.toggle-track{
  position:absolute;inset:0;background:var(--card2);
  border:1px solid var(--border);border-radius:22px;cursor:pointer;transition:all .2s;
}
.toggle input:checked+.toggle-track{background:var(--teal);border-color:var(--teal)}
.toggle-track::after{
  content:'';position:absolute;left:3px;top:50%;transform:translateY(-50%);
  width:14px;height:14px;border-radius:50%;background:var(--muted);transition:all .2s;
}
.toggle input:checked+.toggle-track::after{left:calc(100% - 17px);background:#fff}
/* ── PROGRESS ── */
.progress-wrap{margin:0 0 28px}
.progress-label{
  display:flex;justify-content:space-between;
  font-family:'Barlow Condensed',sans-serif;font-weight:600;
  font-size:10px;letter-spacing:3px;color:var(--muted);
  text-transform:uppercase;margin-bottom:7px;
}
.progress-track{height:4px;background:var(--card2);overflow:hidden}
.progress-fill{
  height:100%;
  background:linear-gradient(90deg,var(--coral),var(--gold),var(--lime));
  transition:width .5s cubic-bezier(.4,0,.2,1);
}
/* ── SCOREBOARD (FIFA 2026 style) ── */
.scoreboard{
  display:flex;align-items:stretch;
  background:#111;border-radius:6px;overflow:hidden;
  box-shadow:5px 5px 0 var(--coral),-5px -5px 0 var(--teal);
  margin:18px auto;max-width:480px;
}
.sb-team{
  flex:1;display:flex;align-items:center;gap:8px;padding:12px 14px;
  font-family:'Barlow Condensed',sans-serif;font-weight:700;
  font-size:14px;letter-spacing:1px;color:var(--white);text-transform:uppercase;
}
.sb-team.right{flex-direction:row-reverse;text-align:right}
.sb-score{
  background:var(--gold);color:var(--black);
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:32px;padding:12px 20px;display:flex;align-items:center;
  gap:10px;letter-spacing:2px;
}
.sb-score input{
  background:transparent;border:none;color:var(--black);
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:32px;width:44px;text-align:center;outline:none;
  -moz-appearance:textfield;
}
.sb-score input::-webkit-inner-spin-button{-webkit-appearance:none}
.sb-sep{color:rgba(0,0,0,.4);font-size:24px}
/* ── MATCH CARD ── */
.match-header{
  display:flex;align-items:center;justify-content:center;gap:16px;
  padding:18px 0 14px;border-bottom:1px solid var(--border);margin-bottom:18px;
}
.match-team{text-align:center;flex:1}
.match-flag{font-size:36px;display:block;margin-bottom:5px}
.match-team-name{
  font-family:'Barlow Condensed',sans-serif;font-weight:600;
  font-size:13px;letter-spacing:2px;color:var(--muted);text-transform:uppercase;
}
.match-center{text-align:center}
.match-vs{
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:28px;color:rgba(201,168,76,.25);letter-spacing:4px;
}
.match-meta{
  font-size:10px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;
  font-weight:600;letter-spacing:2px;margin-top:3px;text-transform:uppercase;
}
/* ── BONUS ROW ── */
.bonus-row{
  display:flex;align-items:center;gap:10px;padding:10px 0;
  border-bottom:1px solid rgba(201,168,76,.05);
}
.bonus-row:last-child{border-bottom:none}
.bonus-label{font-size:13px;color:var(--muted);flex:1}
.bonus-label strong{color:var(--white);display:block;margin-bottom:1px}
.bonus-pts{
  font-family:'Barlow Condensed',sans-serif;font-weight:800;
  font-size:15px;color:var(--lime);letter-spacing:1px;
}
/* ── RESULT BTNS ── */
.result-btns{display:flex;gap:8px}
.result-btn{
  flex:1;background:var(--card2);border:1px solid var(--border);
  border-radius:4px;padding:9px;color:var(--muted);
  font-family:'Barlow Condensed',sans-serif;font-weight:600;
  font-size:12px;letter-spacing:1px;cursor:pointer;text-align:center;
  transition:all .2s;text-transform:uppercase;
}
.result-btn.selected,.result-btn:hover{
  background:rgba(0,184,169,.12);border-color:var(--teal);color:var(--white);
}
/* ── PHASE NAV ── */
.phase-nav{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:24px}
.phase-pill{
  background:var(--card);border:1px solid var(--border);
  padding:5px 14px;font-size:11px;font-family:'Barlow Condensed',sans-serif;
  font-weight:600;letter-spacing:2px;color:var(--muted);cursor:pointer;
  transition:all .15s;text-decoration:none;text-transform:uppercase;
}
.phase-pill.active{background:var(--gold);border-color:var(--gold);color:var(--black)}
.phase-pill:hover:not(.active){border-color:var(--gold);color:var(--gold)}
/* ── GROUP GRID ── */
.group-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.group-card{
  background:var(--card2);border:1px solid var(--border);
  border-radius:5px;padding:14px;
}
.group-card-title{
  font-family:'Barlow Condensed',sans-serif;font-weight:800;
  font-size:11px;letter-spacing:3px;color:var(--gold);
  text-transform:uppercase;margin-bottom:10px;
  display:flex;align-items:center;justify-content:space-between;
}
.group-team-row{
  display:flex;align-items:center;gap:8px;padding:5px 0;
  border-bottom:1px solid rgba(201,168,76,.05);font-size:13px;
}
.group-team-row:last-child{border-bottom:none}
.group-team-flag{font-size:18px;flex-shrink:0}
.group-team-name{flex:1;font-weight:500;color:var(--white)}
.group-team-rank{font-size:11px;color:var(--muted);font-family:'Barlow Condensed',sans-serif}
/* ── PODIUM ── */
.podium{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px}
.podium-slot{
  position:relative;border:1px solid var(--border);
  border-radius:5px;padding:14px;background:var(--card2);transition:all .2s;
}
.podium-slot:hover{border-color:rgba(201,168,76,.35);transform:translateY(-2px)}
.podium-slot.gold{border-color:rgba(201,168,76,.35);background:rgba(201,168,76,.04)}
.podium-num{
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:44px;line-height:1;color:rgba(201,168,76,.1);
  position:absolute;right:8px;top:4px;
}
.podium-num.g1{color:rgba(201,168,76,.2)}
.podium-label{
  font-size:9px;letter-spacing:3px;color:var(--muted);
  font-family:'Barlow Condensed',sans-serif;font-weight:600;
  margin-bottom:8px;text-transform:uppercase;
}
/* ── LEADERBOARD ── */
.lb-table{width:100%;border-collapse:collapse}
.lb-table th{
  font-family:'Barlow Condensed',sans-serif;font-weight:700;
  font-size:10px;letter-spacing:3px;color:var(--muted);
  text-align:left;padding:9px 14px;border-bottom:1px solid var(--border);
  text-transform:uppercase;
}
.lb-table td{padding:13px 14px;border-bottom:1px solid rgba(201,168,76,.04);font-size:14px}
.lb-table tr:hover td{background:rgba(201,168,76,.025)}
.lb-rank{
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:24px;color:rgba(201,168,76,.2);width:46px;
}
.lb-rank.r1{color:var(--gold)}.lb-rank.r2{color:#C0C0C0}.lb-rank.r3{color:#CD7F32}
.lb-name{font-weight:600;color:var(--white)}
.lb-pts{
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:22px;color:var(--gold);text-align:right;
}
/* ── COMPARISON ── */
.compare-grid{display:grid;grid-template-columns:1fr auto 1fr;gap:0;align-items:center}
.compare-team{padding:16px}
.compare-team.right{text-align:right}
.compare-stat-row{
  display:grid;grid-template-columns:1fr auto 1fr;gap:8px;
  align-items:center;padding:8px 0;border-bottom:1px solid rgba(201,168,76,.05);
}
.compare-stat-row:last-child{border-bottom:none}
.compare-bar-wrap{height:4px;background:var(--card2);border-radius:2px;overflow:hidden;margin-top:3px}
.compare-bar{height:100%;background:var(--gold);border-radius:2px;transition:width .6s}
.compare-bar.teal{background:var(--teal)}
.compare-label{
  font-family:'Barlow Condensed',sans-serif;font-weight:600;
  font-size:10px;letter-spacing:2px;color:var(--muted);text-align:center;
  text-transform:uppercase;
}
.compare-val{
  font-family:'Barlow Condensed',sans-serif;font-weight:800;
  font-size:20px;color:var(--white);
}
/* ── ADMIN ── */
.admin-match{
  display:flex;align-items:center;gap:10px;padding:12px 18px;
  border-bottom:1px solid rgba(201,168,76,.05);flex-wrap:wrap;
}
.admin-match:last-child{border-bottom:none}
.admin-score-inp{
  width:56px;text-align:center;font-size:17px;padding:5px 7px;
  font-family:'Barlow Condensed',sans-serif;font-weight:700;
}
/* ── CLOSED BADGE ── */
.closed-badge{
  display:inline-flex;align-items:center;gap:5px;
  background:rgba(255,77,61,.1);border:1px solid rgba(255,77,61,.25);
  color:var(--coral);font-family:'Barlow Condensed',sans-serif;
  font-weight:700;font-size:11px;letter-spacing:2px;padding:3px 10px;
  text-transform:uppercase;
}
/* ── POSTER STRIPE ── */
.poster-stripe{
  height:5px;
  background:linear-gradient(90deg,var(--coral) 0%,var(--gold) 33%,var(--teal) 66%,var(--lime) 100%);
  margin-bottom:0;
}
/* ── SUBMIT ── */
.submit-area{text-align:center;margin-top:36px}
.submit-note{font-size:12px;color:var(--muted);margin-top:10px}
/* ── ANIMATIONS ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
/* ── RESPONSIVE ── */
@media(max-width:640px){
  .nav{padding:0 14px}
  .container{padding:20px 14px 80px}
  .podium,.group-grid{grid-template-columns:1fr}
  .sel-wrap{min-width:130px}
  .nav-right .nav-user-name{display:none}
  .scoreboard{flex-direction:column}
  .sb-team.right{flex-direction:row}
  .compare-grid{grid-template-columns:1fr}
}
</style>
@stack('styles')
</head>
<body>
<div class="poster-stripe"></div>
<nav class="nav">
  <a href="{{ route('leaderboard') }}" class="nav-brand">
    <div class="nav-brand-fifa">FIFA</div>
    <div class="nav-brand-wc">WORLD CUP <em>26</em>™</div>
  </a>
  <div class="nav-links">
    <a href="{{ route('quiniela.maestra') }}"  class="nav-link {{ request()->routeIs('quiniela.maestra*') ? 'active':'' }}">🏆 Maestra</a>
    <a href="{{ route('quiniela.partidos') }}" class="nav-link {{ request()->routeIs('quiniela.partidos*') ? 'active':'' }}">⚽ Partidos</a>
    <a href="{{ route('leaderboard') }}"       class="nav-link {{ request()->routeIs('leaderboard') ? 'active':'' }}">🏅 Tabla</a>
    @if(auth()->user()->is_admin)
    <a href="{{ route('admin.index') }}" class="nav-link admin">⚙ Admin</a>
    @endif
  </div>
  <div class="nav-right">
    <span class="nav-user-name">{{ auth()->user()->name }}</span>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="nav-logout">Salir</button>
    </form>
  </div>
</nav>
<div class="container">
  @if(session('success'))
  <div class="alert alert-success">✓ {{ session('success') }}</div>
  @endif
  @if(session('error'))
  <div class="alert alert-error">⚠ {{ session('error') }}</div>
  @endif
  @yield('content')
</div>
@stack('scripts')
</body>
</html>

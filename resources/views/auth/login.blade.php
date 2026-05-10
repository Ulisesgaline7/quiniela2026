<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#000000">
<title>FIFA World Cup 26™ · Quiniela</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
:root{
  --black:#000; --white:#fff; --gold:#C9A84C; --gold2:#E8C96A;
  --f-red:#CC0000; --f-orange:#FF4400; --f-purple:#6600CC;
  --f-blue:#0044CC; --f-teal:#00CCAA; --f-lime:#CCFF00;
  --card:#0F0F0F; --card2:#181818; --border:rgba(201,168,76,.2);
  --muted:#555; --text:#F5F0E8;
}
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{height:100%;height:100dvh}
body{
  background:var(--black);color:var(--text);
  font-family:'Barlow',sans-serif;
  display:flex;flex-direction:column;
  overflow-x:hidden;
}

/* ── POSTER BACKGROUND ── */
.login-bg{
  position:fixed;inset:0;z-index:0;
  background:
    radial-gradient(ellipse 120% 60% at 50% 0%,rgba(201,168,76,.15) 0%,transparent 50%),
    radial-gradient(ellipse 80% 50% at 0% 100%,rgba(204,0,0,.08) 0%,transparent 50%),
    radial-gradient(ellipse 80% 50% at 100% 0%,rgba(102,0,204,.08) 0%,transparent 50%),
    #000;
}
/* Big "26" watermark like official poster */
.login-bg::before{
  content:'26';
  position:absolute;bottom:-60px;right:-20px;
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:clamp(200px,50vw,400px);line-height:1;letter-spacing:-15px;
  color:rgba(255,255,255,.03);pointer-events:none;
}

/* ── RAINBOW STRIPE ── */
.stripe{
  height:4px;width:100%;flex-shrink:0;
  background:linear-gradient(90deg,
    #CC0000 0%,#FF4400 14%,#CCCC00 28%,
    #CCFF00 42%,#00CCAA 57%,#0044CC 71%,
    #6600CC 85%,#CC3366 100%);
  position:relative;z-index:10;
}

/* ── MAIN CONTENT ── */
.login-wrap{
  flex:1;display:flex;flex-direction:column;
  align-items:center;justify-content:center;
  padding:20px 20px calc(20px + env(safe-area-inset-bottom));
  position:relative;z-index:1;
}

/* ── TROPHY + LOGO SECTION ── */
.login-brand{text-align:center;margin-bottom:24px}

.login-trophy{
  font-size:clamp(60px,15vw,90px);
  display:block;margin-bottom:8px;
  filter:drop-shadow(0 0 30px rgba(201,168,76,.5));
  animation:float 3s ease-in-out infinite;
}
@keyframes float{
  0%,100%{transform:translateY(0)}
  50%{transform:translateY(-8px)}
}

/* FIFA 26 logo recreation */
.login-logo{
  display:inline-flex;align-items:center;gap:0;
  margin-bottom:10px;
}
.login-logo-26{
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:clamp(48px,12vw,72px);line-height:.9;
  color:var(--white);letter-spacing:-4px;
  text-shadow:0 0 40px rgba(201,168,76,.3);
}
.login-logo-trophy-inline{
  font-size:clamp(36px,9vw,54px);
  margin:0 -4px;
  filter:drop-shadow(0 2px 8px rgba(201,168,76,.6));
}
.login-logo-fifa{
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:clamp(14px,3.5vw,20px);letter-spacing:4px;
  color:var(--gold);display:block;text-align:center;
  margin-top:4px;
}
.login-logo-wc{
  font-family:'Barlow Condensed',sans-serif;font-weight:600;
  font-size:clamp(10px,2.5vw,13px);letter-spacing:3px;
  color:var(--muted);display:block;text-align:center;
}

/* ── MASCOTS ── */
.login-mascots{
  font-size:clamp(28px,7vw,40px);
  display:flex;justify-content:center;gap:4px;
  margin-bottom:6px;
  filter:drop-shadow(0 2px 6px rgba(0,0,0,.5));
}
.login-mascots span{
  display:inline-block;
  animation:bounce 2s ease-in-out infinite;
}
.login-mascots span:nth-child(1){animation-delay:0s}
.login-mascots span:nth-child(2){animation-delay:.3s}
.login-mascots span:nth-child(3){animation-delay:.6s}
@keyframes bounce{
  0%,100%{transform:translateY(0) rotate(0deg)}
  50%{transform:translateY(-6px) rotate(3deg)}
}

/* ── CARD ── */
.login-card{
  width:100%;max-width:400px;
  background:rgba(15,15,15,.95);
  border:1px solid var(--border);
  border-radius:12px;
  padding:28px 24px;
  box-shadow:
    0 0 0 1px rgba(201,168,76,.05),
    0 20px 60px rgba(0,0,0,.8),
    0 0 80px rgba(201,168,76,.05);
  backdrop-filter:blur(20px);
}

.login-title{
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:22px;letter-spacing:3px;color:var(--white);
  text-transform:uppercase;margin-bottom:6px;
}
.login-subtitle{
  font-size:12px;color:var(--muted);
  font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;
  margin-bottom:24px;
}

.form-group{margin-bottom:18px}
.form-label{
  display:block;font-family:'Barlow Condensed',sans-serif;font-weight:700;
  font-size:10px;letter-spacing:3px;color:var(--muted);
  text-transform:uppercase;margin-bottom:8px;
}
.form-input{
  width:100%;background:rgba(255,255,255,.05);
  border:1px solid rgba(201,168,76,.2);border-radius:6px;
  color:var(--text);font-family:'Barlow',sans-serif;
  font-size:18px;padding:14px 16px;outline:none;
  transition:all .2s;
  -webkit-appearance:none;
}
.form-input:focus{
  border-color:var(--gold);
  background:rgba(201,168,76,.05);
  box-shadow:0 0 0 3px rgba(201,168,76,.1);
}
.form-input::placeholder{color:rgba(255,255,255,.2)}
.form-error{
  font-size:12px;color:#FF4444;margin-top:6px;
  font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px;
  display:flex;align-items:center;gap:4px;
}

/* ── LOGIN BUTTON ── */
.login-btn{
  width:100%;background:var(--gold);color:var(--black);border:none;
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:18px;letter-spacing:4px;padding:16px;
  cursor:pointer;transition:all .2s;text-transform:uppercase;
  border-radius:6px;margin-top:4px;
  -webkit-appearance:none;
  position:relative;overflow:hidden;
}
.login-btn::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(255,255,255,.1) 0%,transparent 50%);
}
.login-btn:hover,.login-btn:active{
  background:var(--gold2);
  transform:translateY(-1px);
  box-shadow:0 8px 24px rgba(201,168,76,.4);
}
.login-btn:active{transform:translateY(0)}

/* ── HINT ── */
.login-hint{
  text-align:center;margin-top:20px;
  font-size:12px;color:var(--muted);
  font-family:'Barlow Condensed',sans-serif;letter-spacing:1px;
  line-height:1.7;
}
.login-hint strong{color:var(--f-teal)}

/* ── HOST COUNTRIES ── */
.host-flags{
  display:flex;justify-content:center;gap:8px;
  margin-top:20px;padding-top:16px;
  border-top:1px solid rgba(255,255,255,.06);
}
.host-flag{
  display:flex;flex-direction:column;align-items:center;gap:3px;
  font-size:24px;
}
.host-flag span{
  font-size:9px;letter-spacing:1px;color:var(--muted);
  font-family:'Barlow Condensed',sans-serif;font-weight:600;
  text-transform:uppercase;
}

/* ── BOTTOM STRIPE ── */
.login-bottom-stripe{
  height:3px;width:100%;flex-shrink:0;
  background:linear-gradient(90deg,
    #CC0000 0%,#FF4400 14%,#CCCC00 28%,
    #CCFF00 42%,#00CCAA 57%,#0044CC 71%,
    #6600CC 85%,#CC3366 100%);
}
</style>
</head>
<body>
<div class="stripe"></div>
<div class="login-bg"></div>

<div class="login-wrap">
  <div class="login-brand">
    {{-- Mascots --}}
    <div class="login-mascots">
      <span title="Maple (Canadá)">🫎</span>
      <span title="Zayu (México)">🐆</span>
      <span title="Clutch (USA)">🦅</span>
    </div>

    {{-- FIFA 26 Logo --}}
    <div class="login-logo">
      <span class="login-logo-26">2</span>
      <span class="login-logo-trophy-inline">🏆</span>
      <span class="login-logo-26">6</span>
    </div>
    <div class="login-logo-fifa">FIFA WORLD CUP™</div>
    <div class="login-logo-wc">QUINIELA OFICIAL · 2026</div>
  </div>

  <div class="login-card">
    <div class="login-title">Iniciar Sesión</div>
    <div class="login-subtitle">Ingresa tu nombre de usuario para entrar</div>

    @if(session('status'))
    <div style="background:rgba(0,204,170,.1);border-left:3px solid #00CCAA;color:#00CCAA;padding:10px 14px;margin-bottom:16px;font-family:'Barlow Condensed',sans-serif;font-size:13px;letter-spacing:1px;border-radius:4px">
      {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label class="form-label" for="username">👤 Usuario</label>
        <input class="form-input" type="text" id="username" name="username"
          value="{{ old('username') }}" required autofocus autocomplete="username"
          placeholder="tu_usuario" inputmode="text" autocapitalize="none">
        @error('username')
        <div class="form-error">⚠ {{ $message }}</div>
        @enderror
      </div>
      <button type="submit" class="login-btn">⚽ &nbsp;ENTRAR</button>
    </form>

    <div class="login-hint">
      ¿No tienes cuenta?<br>
      <strong>Pide al administrador que te cree una.</strong><br>
      Solo necesitas tu nombre de usuario — sin contraseña.
    </div>

    <div class="host-flags">
      <div class="host-flag"><span>🇺🇸</span><span>USA</span></div>
      <div class="host-flag"><span>🇲🇽</span><span>México</span></div>
      <div class="host-flag"><span>🇨🇦</span><span>Canadá</span></div>
    </div>
  </div>
</div>

<div class="login-bottom-stripe"></div>
</body>
</html>

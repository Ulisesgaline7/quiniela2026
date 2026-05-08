<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso — FIFA WORLD CUP 26™</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
:root{--black:#0A0A0A;--white:#F2EDE4;--gold:#C9A84C;--gold2:#E8C96A;--coral:#FF4D3D;--teal:#00B8A9;--card:#141414;--card2:#1C1C1C;--border:rgba(201,168,76,.18);--muted:#5A5A5A;--text:#F2EDE4}
*{margin:0;padding:0;box-sizing:border-box}
body{
  background:var(--black);color:var(--text);font-family:'Barlow',sans-serif;
  min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;
  background-image:
    radial-gradient(ellipse 100% 50% at 50% 0%,rgba(201,168,76,.12) 0%,transparent 55%),
    radial-gradient(ellipse 50% 40% at 0% 100%,rgba(255,77,61,.06) 0%,transparent 50%),
    radial-gradient(ellipse 50% 40% at 100% 0%,rgba(0,184,169,.06) 0%,transparent 50%);
}
.stripe{height:4px;width:100%;background:linear-gradient(90deg,#FF4D3D,#C9A84C,#00B8A9,#AADD00);position:fixed;top:0;left:0}
.login-wrap{width:100%;max-width:400px;padding:20px}
.login-brand{text-align:center;margin-bottom:32px}
.login-brand-badge{
  display:inline-block;background:var(--gold);color:var(--black);
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:28px;letter-spacing:4px;padding:8px 20px 6px;
  clip-path:polygon(10px 0,100% 0,calc(100% - 10px) 100%,0 100%);
  margin-bottom:8px;
}
.login-brand-sub{
  font-family:'Barlow Condensed',sans-serif;font-weight:700;
  font-size:13px;letter-spacing:4px;color:var(--muted);text-transform:uppercase;
}
.login-mascots{font-size:28px;margin-bottom:6px;display:flex;justify-content:center;gap:4px}
.login-card{
  background:var(--card);border:1px solid var(--border);
  border-radius:8px;padding:32px;
  box-shadow:0 0 60px rgba(201,168,76,.06);
}
.login-title{
  font-family:'Barlow Condensed',sans-serif;font-weight:800;
  font-size:22px;letter-spacing:3px;color:var(--white);
  text-transform:uppercase;margin-bottom:24px;
}
.form-group{margin-bottom:18px}
.form-label{
  display:block;font-family:'Barlow Condensed',sans-serif;font-weight:600;
  font-size:10px;letter-spacing:3px;color:var(--muted);
  text-transform:uppercase;margin-bottom:7px;
}
.form-input{
  width:100%;background:var(--card2);border:1px solid var(--border);
  border-radius:4px;color:var(--text);font-family:'Barlow',sans-serif;
  font-size:16px;padding:12px 14px;outline:none;transition:border-color .2s;
}
.form-input:focus{border-color:var(--gold)}
.form-error{font-size:12px;color:#FF4D3D;margin-top:5px;font-family:'Barlow Condensed',sans-serif;letter-spacing:.5px}
.login-btn{
  width:100%;background:var(--gold);color:var(--black);border:none;
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:18px;letter-spacing:4px;padding:14px;
  cursor:pointer;transition:all .2s;text-transform:uppercase;
  clip-path:polygon(10px 0,100% 0,calc(100% - 10px) 100%,0 100%);
  margin-top:8px;
}
.login-btn:hover{background:var(--gold2);box-shadow:0 8px 28px rgba(201,168,76,.3)}
.login-hint{
  text-align:center;margin-top:20px;
  font-size:12px;color:var(--muted);font-family:'Barlow Condensed',sans-serif;
  letter-spacing:1px;line-height:1.6;
}
.login-hint strong{color:var(--teal)}
/* Big "26" watermark */
.wm{
  position:fixed;bottom:-40px;right:-20px;
  font-family:'Barlow Condensed',sans-serif;font-weight:900;
  font-size:300px;line-height:1;color:rgba(201,168,76,.03);
  pointer-events:none;letter-spacing:-10px;
}
</style>
</head>
<body>
<div class="stripe"></div>
<div class="wm">26</div>
<div class="login-wrap">
  <div class="login-brand">
    <div class="login-mascots">🫎 🐆 🦅</div>
    <div class="login-brand-badge">FIFA</div>
    <div class="login-brand-sub">World Cup 26™ · Quiniela Oficial</div>
  </div>
  <div class="login-card">
    <div class="login-title">Iniciar Sesión</div>
    @if(session('status'))
    <div style="background:rgba(0,184,169,.1);border-left:3px solid #00B8A9;color:#00B8A9;padding:10px 14px;margin-bottom:16px;font-family:'Barlow Condensed',sans-serif;font-size:13px;letter-spacing:1px">
      {{ session('status') }}
    </div>
    @endif
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label class="form-label" for="username">Nombre de usuario</label>
        <input class="form-input" type="text" id="username" name="username"
          value="{{ old('username') }}" required autofocus autocomplete="username"
          placeholder="tu_usuario">
        @error('username')
        <div class="form-error">{{ $message }}</div>
        @enderror
      </div>
      <button type="submit" class="login-btn">⚽ Entrar</button>
    </form>
    <div class="login-hint">
      ¿No tienes cuenta? <strong>Pide al administrador que te cree una.</strong><br>
      No necesitas contraseña — solo tu nombre de usuario.
    </div>
  </div>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Acceso') · Quiniela 2026</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;700&family=Oswald:wght@300;400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --gold:#F5C842; --green:#0E7C3A; --dark:#0a0f0d; --card:#111a14; --card2:#162019; --border:rgba(245,200,66,0.15); --text:#e8f0ea; --muted:#6b8070; --accent:#3bffa0; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            background:var(--dark); color:var(--text); font-family:'DM Sans',sans-serif;
            min-height:100vh; display:flex; align-items:center; justify-content:center;
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -10%, rgba(14,124,58,0.25) 0%, transparent 60%),
                repeating-linear-gradient(0deg, transparent, transparent 39px, rgba(245,200,66,0.03) 40px),
                repeating-linear-gradient(90deg, transparent, transparent 39px, rgba(245,200,66,0.03) 40px);
        }
        .auth-box { width:100%; max-width:420px; padding:24px; }
        .auth-logo { text-align:center; margin-bottom:32px; }
        .auth-logo a { font-family:'Bebas Neue',sans-serif; font-size:36px; letter-spacing:4px; color:var(--gold); text-decoration:none; }
        .auth-logo a span { color:var(--accent); }
        .auth-logo p { font-family:'Oswald',sans-serif; font-size:11px; letter-spacing:3px; color:var(--muted); margin-top:4px; }
        .auth-card { background:var(--card); border:1px solid var(--border); border-radius:14px; padding:32px; }
        .auth-title { font-family:'Oswald',sans-serif; font-size:18px; letter-spacing:2px; color:var(--text); margin-bottom:24px; }
        .form-group { margin-bottom:18px; }
        label { display:block; font-size:11px; letter-spacing:1px; color:var(--muted); font-family:'Oswald',sans-serif; margin-bottom:6px; }
        input[type="text"], input[type="email"], input[type="password"] {
            width:100%; background:var(--card2); border:1px solid var(--border); border-radius:8px;
            color:var(--text); font-family:'DM Sans',sans-serif; font-size:14px;
            padding:11px 14px; outline:none; transition:border-color .2s;
        }
        input:focus { border-color:var(--gold); }
        .btn-auth { width:100%; background:var(--gold); color:var(--dark); border:none; font-family:'Bebas Neue',sans-serif; font-size:18px; letter-spacing:2px; padding:14px; border-radius:8px; cursor:pointer; transition:all .2s; margin-top:8px; }
        .btn-auth:hover { background:#ffe040; }
        .auth-link { color:var(--muted); font-size:13px; text-decoration:none; transition:color .2s; }
        .auth-link:hover { color:var(--gold); }
        .auth-footer { text-align:center; margin-top:20px; font-size:13px; color:var(--muted); }
        .error-msg { font-size:12px; color:#ff7070; margin-top:4px; font-family:'Oswald',sans-serif; letter-spacing:.5px; }
        .alert-success { background:rgba(59,255,160,0.1); border:1px solid rgba(59,255,160,0.3); color:var(--accent); padding:10px 14px; border-radius:8px; font-size:13px; font-family:'Oswald',sans-serif; letter-spacing:.5px; margin-bottom:16px; }
        .checkbox-row { display:flex; align-items:center; gap:8px; }
        .checkbox-row input[type="checkbox"] { width:16px; height:16px; accent-color:var(--green); }
        .checkbox-row label { margin:0; font-size:13px; color:var(--muted); letter-spacing:0; }
    </style>
</head>
<body>
<div class="auth-box">
    <div class="auth-logo">
        <a href="/">QUINIELA<span>26</span></a>
        <p>MUNDIAL 2026</p>
    </div>
    <div class="auth-card">
        @yield('content')
    </div>
</div>
</body>
</html>

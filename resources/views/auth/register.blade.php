@extends('layouts.auth')
@section('title', 'Crear Cuenta')

@section('content')
<div class="auth-title">CREAR CUENTA</div>

<form method="POST" action="{{ route('register') }}">
@csrf

<div class="form-group">
    <label for="name">NOMBRE</label>
    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Tu nombre o apodo">
    @error('name') <div class="error-msg">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="email">CORREO ELECTRÓNICO</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username">
    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="password">CONTRASEÑA</label>
    <input type="password" id="password" name="password" required autocomplete="new-password">
    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="password_confirmation">CONFIRMAR CONTRASEÑA</label>
    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
</div>

<button type="submit" class="btn-auth">REGISTRARME</button>

<div class="auth-footer" style="margin-top:16px">
    <a href="{{ route('login') }}" class="auth-link">¿Ya tienes cuenta? Inicia sesión</a>
</div>
</form>
@endsection

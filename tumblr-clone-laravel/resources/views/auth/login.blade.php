@extends('layouts.app')

@section('content')
<div class="auth-form">
    <h1>Autentificare</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Parolă</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" class="btn btn-primary btn-block">Autentificare</button>
    </form>

    <p class="auth-alt">Nu ai cont? <a href="{{ route('register') }}">Înregistrează-te</a></p>
</div>
@endsection

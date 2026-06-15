@extends('layouts.app')

@section('content')
<div class="settings">
    <h1>Setări</h1>

    <section class="settings-section">
        <h2>Avatar</h2>
        <img src="{{ $user->avatar }}" alt="avatar" class="avatar avatar--lg">
        <form method="POST" action="{{ route('settings.avatar') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="avatar" accept="image/*" required>
            <button type="submit" class="btn btn-primary">Încarcă</button>
        </form>
    </section>

    <section class="settings-section">
        <h2>Bio</h2>
        <form method="POST" action="{{ route('settings') }}">
            @csrf
            <textarea name="bio" rows="4" maxlength="500">{{ $user->bio }}</textarea>
            <button type="submit" class="btn btn-primary">Salvează bio</button>
        </form>
    </section>

    <section class="settings-section">
        <h2>Schimbă parola</h2>
        <form method="POST" action="{{ route('settings.password') }}">
            @csrf

            <label for="current_password">Parola actuală</label>
            <input type="password" name="current_password" id="current_password" required>

            <label for="new_password">Parola nouă</label>
            <input type="password" name="new_password" id="new_password" required minlength="6">

            <label for="confirm_password">Confirmă parola nouă</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit" class="btn btn-primary">Schimbă parola</button>
        </form>
    </section>
</div>
@endsection

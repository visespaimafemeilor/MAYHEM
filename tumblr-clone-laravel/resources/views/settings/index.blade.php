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
        <h2>Culoare de accent</h2>
        <form method="POST" action="{{ route('settings') }}">
            @csrf
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px;">
                @php
                    $colors = [
                        ''         => 'Implicit',
                        '#8a6cf5'  => 'Violet',
                        '#00ff41'  => 'Terminal',
                        '#00bcd4'  => 'Cyan',
                        '#ff5722'  => 'Portocaliu',
                        '#e91e63'  => 'Roz',
                        '#4caf50'  => 'Verde',
                        '#ffeb3b'  => 'Galben',
                    ];
                @endphp
                @foreach ($colors as $val => $label)
                    <label class="color-option" style="display:flex;flex-direction:column;align-items:center;gap:4px;cursor:pointer;">
                        <input type="radio" name="accent_color" value="{{ $val }}"
                               @if($user->accent_color === $val) checked @endif
                               style="display:none;">
                        <span style="display:inline-block;width:32px;height:32px;border-radius:50%;
                                     background:{{ $val ?: 'var(--accent)' }};
                                     border:3px solid {{ $user->accent_color === $val ? '#fff' : 'transparent' }};
                                     transition:border 0.2s;"></span>
                        <small style="font-size:0.7rem;color:var(--text-muted);">{{ $label }}</small>
                    </label>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary">Salvează culoarea</button>
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

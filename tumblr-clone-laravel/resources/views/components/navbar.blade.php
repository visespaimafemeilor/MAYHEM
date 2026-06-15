<nav class="navbar">
    <div class="container navbar-inner">
        <a href="{{ route('dashboard') }}" class="navbar-brand">Tumbleweed</a>

        <div class="navbar-links">
            @auth
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">+ Postează</a>
                <a href="{{ route('profile', Auth::user()->username) }}" class="nav-link">Profil</a>
                <a href="{{ route('settings') }}" class="nav-link">Setări</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer;font-family:inherit;font-size:inherit;color:inherit;padding:6px 10px">Ieșire</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">Autentificare</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Înregistrare</a>
            @endauth
        </div>
    </div>
</nav>

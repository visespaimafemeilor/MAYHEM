<nav class="navbar">
    <div class="container navbar-inner">
        <a href="{{ route('dashboard') }}" class="navbar-brand">MAYHEM</a>

        <div class="nav-links">
            @auth
                <form method="GET" action="{{ route('dashboard') }}" class="search-form">
                    <input type="search" name="q" placeholder="Caută..." value="{{ request('q') }}">
                </form>
                <a href="{{ route('explore') }}" class="nav-link">Explorează</a>
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">+ Postează</a>
                <a href="{{ route('notifications') }}" class="nav-link" id="notif-link">
                    Notificări
                    <span id="notif-badge" class="notif-badge" style="display:none;">0</span>
                </a>
                <a href="{{ route('profile', Auth::user()->username) }}" class="nav-link">Profil</a>
                <a href="{{ route('settings') }}" class="nav-link">Setări</a>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="nav-link btn-logout">Ieșire</button>
                </form>
            @else
                <a href="{{ route('explore') }}" class="nav-link">Explorează</a>
                <a href="{{ route('login') }}" class="nav-link">Autentificare</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Înregistrare</a>
            @endauth
        </div>
    </div>
</nav>

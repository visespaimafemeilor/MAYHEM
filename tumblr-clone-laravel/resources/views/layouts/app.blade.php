<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MAYHEM')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('head')
</head>
<body>

    @include('components.navbar')

    <main class="container">
        @if (session('error'))
            <p class="alert alert-error">{{ session('error') }}</p>
        @endif

        @if (session('success'))
            <p class="alert alert-success">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <p class="alert alert-error">{{ $errors->first() }}</p>
        @endif

        @yield('content')
    </main>

    @include('components.footer')

    <script>var BASE_URL = '{{ url('/') }}';</script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>

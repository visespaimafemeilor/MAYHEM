@extends('layouts.app')

@push('head')
@if($profileUser->accent_color)
<style>
    body { --accent: {{ $profileUser->accent_color }}; --accent-hover: {{ $profileUser->accent_color }}cc; }
    .post-card-tags a { color: var(--accent) !important; background: color-mix(in srgb, var(--accent) 10%, transparent) !important; }
    .pagination .active span { background: var(--accent) !important; border-color: var(--accent) !important; }
</style>
@endif
@endpush

@section('content')
<div class="profile">
    <a href="{{ route('dashboard') }}" class="back-link">Înapoi la Dashboard</a>
    <div class="profile-header">
        <img src="{{ $profileUser->avatar }}"
             alt="avatar" class="avatar avatar--lg">
        <h1>{{ $profileUser->username }}</h1>

        @if ($profileUser->bio)
            <p class="profile-bio">{{ nl2br(e($profileUser->bio)) }}</p>
        @endif

        <p class="profile-meta">
            Membru din {{ $profileUser->created_at->format('F Y') }}
        </p>

        @auth
            @if (Auth::user()->id !== $profileUser->id)
                <button class="btn btn-follow @if ($isFollowing) following @endif"
                        data-user-id="{{ $profileUser->id }}">
                    {{ $isFollowing ? 'Urmărești' : 'Urmărește' }}
                </button>
            @endif
        @endauth
    </div>

    <div class="profile-posts">
        @forelse ($posts as $post)
            @include('components.post-card', ['post' => $post])
        @empty
            <div class="empty-state">
                <p>Acest utilizator nu a postat încă nimic.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

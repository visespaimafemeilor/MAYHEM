@extends('layouts.app')

@section('content')
<div class="dashboard">
    <h1 class="page-title">Dashboard</h1>

    @forelse ($posts as $post)
        @include('components.post-card', ['post' => $post])
    @empty
        <div class="empty-state">
            <p>Nicio postare în feed. Începe să urmărești utilizatori sau
                <a href="{{ route('posts.create') }}">creează prima postare</a>.</p>
        </div>
    @endforelse
</div>
@endsection

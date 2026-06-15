@extends('layouts.app')

@section('content')
<div class="dashboard">
    <div class="filter-header">
        <h1 class="page-title">
            @if (request('q'))
                Rezultate pentru „{{ e(request('q')) }}”
            @elseif (request('tag'))
                #{{ request('tag') }}
            @elseif (request('type'))
                Postări {{ __('types.' . request('type')) }}
            @else
                Dashboard
            @endif
        </h1>

        <div class="filter-bar">
            <a href="{{ route('dashboard', ['type' => null, 'tag' => null, 'q' => null]) }}"
               class="filter-btn @if(!request('type') && !request('tag') && !request('q')) filter-active @endif">Toate</a>
            <a href="{{ route('dashboard', ['type' => 'text']) }}"
               class="filter-btn @if(request('type') === 'text') filter-active @endif">Text</a>
            <a href="{{ route('dashboard', ['type' => 'image']) }}"
               class="filter-btn @if(request('type') === 'image') filter-active @endif">Imagine</a>
            <a href="{{ route('dashboard', ['type' => 'quote']) }}"
               class="filter-btn @if(request('type') === 'quote') filter-active @endif">Citat</a>
            <a href="{{ route('dashboard', ['type' => 'link']) }}"
               class="filter-btn @if(request('type') === 'link') filter-active @endif">Link</a>
        </div>
    </div>

    @if (isset($tags) && $tags->isNotEmpty())
        <div class="tag-bar">
            <span class="tag-bar-label">Tag-uri:</span>
            @foreach ($tags as $tag)
                <a href="{{ route('dashboard', ['tag' => $tag->slug]) }}"
                   class="tag-link @if(request('tag') === $tag->slug) tag-link-active @endif">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    @endif

    <div class="feed">
        @forelse ($posts as $post)
            @include('components.post-card', ['post' => $post])
        @empty
            <div class="empty-state">
                <p>
                    @if (request('q'))
                        Nicio postare nu corespunde căutării.
                    @else
                        Nicio postare în feed. Începe să urmărești utilizatori sau
                        <a href="{{ route('posts.create') }}">creează prima postare</a>.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $posts->links() }}
    </div>
</div>
@endsection

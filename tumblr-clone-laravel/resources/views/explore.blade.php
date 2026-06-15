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
                Postări populare — {{ request('type') }}
            @else
                Explorează
            @endif
        </h1>

        <div class="filter-bar">
            <a href="{{ route('explore', ['type' => null, 'tag' => null, 'q' => null]) }}"
               class="filter-btn @if(!request('type') && !request('tag') && !request('q')) filter-active @endif">Toate</a>
            <a href="{{ route('explore', ['type' => 'text']) }}"
               class="filter-btn @if(request('type') === 'text') filter-active @endif">Text</a>
            <a href="{{ route('explore', ['type' => 'image']) }}"
               class="filter-btn @if(request('type') === 'image') filter-active @endif">Imagine</a>
            <a href="{{ route('explore', ['type' => 'quote']) }}"
               class="filter-btn @if(request('type') === 'quote') filter-active @endif">Citat</a>
            <a href="{{ route('explore', ['type' => 'link']) }}"
               class="filter-btn @if(request('type') === 'link') filter-active @endif">Link</a>
        </div>
    </div>

    <p class="explore-subtitle">Cele mai apreciate postări din ultimele 30 de zile</p>

    @if (isset($tags) && $tags->isNotEmpty())
        <div class="tag-bar">
            <span class="tag-bar-label">Tag-uri:</span>
            @foreach ($tags as $tag)
                <a href="{{ route('explore', ['tag' => $tag->slug]) }}"
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
                <p>Nicio postare populară momentan.</p>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $posts->links() }}
    </div>
</div>
@endsection

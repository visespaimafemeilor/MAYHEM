@extends('layouts.app')

@section('content')
<div class="dashboard">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
        <h1 class="page-title" style="margin-bottom:0;">
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

        <div style="display:flex;gap:6px;flex-wrap:wrap;">
            <a href="{{ route('explore', ['type' => null, 'tag' => null, 'q' => null]) }}"
               class="btn btn-sm @if(!request('type') && !request('tag') && !request('q')) btn-primary @endif">Toate</a>
            <a href="{{ route('explore', ['type' => 'text']) }}"
               class="btn btn-sm @if(request('type') === 'text') btn-primary @endif">Text</a>
            <a href="{{ route('explore', ['type' => 'image']) }}"
               class="btn btn-sm @if(request('type') === 'image') btn-primary @endif">Imagine</a>
            <a href="{{ route('explore', ['type' => 'quote']) }}"
               class="btn btn-sm @if(request('type') === 'quote') btn-primary @endif">Citat</a>
            <a href="{{ route('explore', ['type' => 'link']) }}"
               class="btn btn-sm @if(request('type') === 'link') btn-primary @endif">Link</a>
        </div>
    </div>

    <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:20px;">
        Cele mai apreciate postări din ultimele 30 de zile
    </p>

    @if (isset($tags) && $tags->isNotEmpty())
        <div style="margin-bottom:20px;display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
            <span style="font-size:0.8rem;color:var(--text-muted);margin-right:4px;">Tag-uri:</span>
            @foreach ($tags as $tag)
                <a href="{{ route('explore', ['tag' => $tag->slug]) }}"
                   style="font-size:0.8rem;color:var(--accent);background:rgba(138,108,245,0.08);padding:2px 12px;border-radius:999px;text-decoration:none;border:1px solid transparent;transition:all 0.2s;"
                   @if(request('tag') === $tag->slug) style="font-size:0.8rem;color:#fff;background:var(--accent);padding:2px 12px;border-radius:999px;text-decoration:none;" @endif>
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

    <div style="margin-top:32px;">
        {{ $posts->links() }}
    </div>
</div>
@endsection

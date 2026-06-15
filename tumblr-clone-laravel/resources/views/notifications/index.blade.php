@extends('layouts.app')

@section('content')
<div class="notifications-page">
    <h1 class="page-title">Notificări</h1>

    @forelse ($notifications as $notification)
        <div class="notification-item @if($notification->unread()) unread @endif"
             data-id="{{ $notification->id }}">
            <div class="notification-icon">
                @switch($notification->data['type'] ?? '')
                    @case('like') &#9829; @break
                    @case('follow') &#10149; @break
                    @case('reblog') &#128257; @break
                    @default &#9672;
                @endswitch
            </div>
            <div class="notification-body">
                <p>{{ $notification->data['message'] ?? '' }}</p>
                <small>{{ $notification->created_at->diffForHumans() }}</small>
            </div>
            @if($notification->unread())
                <button class="btn-mark-read" data-id="{{ $notification->id }}"
                        onclick="markRead(this)">OK</button>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <p>Nu ai nicio notificare.</p>
        </div>
    @endforelse

    <div style="margin-top:32px;">
        {{ $notifications->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
function markRead(el) {
    var id = el.dataset.id;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', BASE_URL + '/notifications/read', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.content);
    xhr.onload = function () {
        if (xhr.status === 200) {
            el.closest('.notification-item').classList.remove('unread');
            el.remove();
        }
    };
    xhr.send('id=' + encodeURIComponent(id));
}
</script>
@endpush

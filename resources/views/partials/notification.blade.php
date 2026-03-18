<article class="list-group-item d-flex justify-content-between align-items-start mb-1 {{ $notification->seen ? 'bg-secondary text-white' : 'bg-white' }}" style="border-bottom:1px solid rgba(0,0,0,0.08);">
    <div class="d-flex align-items-start">
        <div class="text-center">
            <a href="{{ $notification->url() }}" class="d-block text-decoration-none {{ $notification->seen ? 'text-white' : 'text-dark' }}">
                <strong class="d-block">{{ $notification->message() }}</strong>
            </a>
        </div>
    </div>

    <div class="d-flex flex-column align-items-center ms-3">
        @if (!$notification->seen)
            <form action="{{ route('notifications.markSeen', ['notification' => $notification]) }}" method="POST" class="mb-1">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as seen" aria-label="Mark as seen">✔</button>
            </form>
        @endif

        <form action="{{ route('notifications.destroy', ['notification' => $notification]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" aria-label="Delete">🗑</button>
        </form>
    </div>
</article>
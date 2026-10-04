@forelse($messages as $message)
    @php
        $isMine = (int) $message->sender_id === (int) Auth::id();
    @endphp

    <div class="d-flex mb-3 {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}" id="msg-{{ $message->id }}">
        <div style="max-width: 75%;">
            <div
                class="px-3 py-2 rounded-3 shadow-sm {{ $isMine ? 'bg-primary text-white' : 'bg-white border' }}"
                style="white-space: pre-wrap; word-break: break-word;"
            >{{ $message->body }}</div>

            <div class="small text-muted mt-1 {{ $isMine ? 'text-end' : '' }}">
                {{ $isMine ? 'You' : (optional($message->sender)->name ?? 'Support') }}
                &bull;
                <span title="{{ $message->created_at?->toDayDateTimeString() }}">
                    {{ $message->created_at?->diffForHumans() }}
                </span>
                @if($isMine)
                    &bull;
                    <span class="msg-status fw-semibold {{ $message->read_at ? 'text-success' : '' }}">
                        {{ $message->read_at ? 'Seen' : 'Sent' }}
                    </span>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="text-center text-muted py-5">
        <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
        No messages yet — say hello and our team will reply here.
    </div>
@endforelse

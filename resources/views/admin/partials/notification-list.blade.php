@forelse($notifications as $notification)
    @php
        $data = $notification->data;
        $isUnread = is_null($notification->read_at);
        $type = $data['type'] ?? 'generic';
        $icon = match ($type) {
            'new_order' => 'bi-bag-check',
            'new_user' => 'bi-person-plus',
            default => 'bi-bell',
        };
    @endphp

    {{-- Each entry is a POST so the click can mark the notification read (CSRF-safe). --}}
    <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}">
        @csrf
        <button
            type="submit"
            class="dropdown-item d-flex gap-2 align-items-start py-3 border-bottom text-start text-wrap {{ $isUnread ? 'bg-primary-subtle' : '' }}"
            title="{{ $data['message'] ?? 'Notification' }}"
        >
            <span class="fs-5 text-primary"><i class="bi {{ $icon }}"></i></span>

            <span class="flex-grow-1">
                <span class="d-block small {{ $isUnread ? 'fw-semibold text-dark' : 'text-muted' }}">
                    {{ $data['message'] ?? 'Notification' }}
                </span>

                @if($type === 'new_order')
                    <span class="d-block text-muted" style="font-size: .78rem;">
                        {{ $data['customer_email'] ?? 'Unknown customer' }}
                        @if(isset($data['total_amount']))
                            &bull; ${{ number_format((float) $data['total_amount'], 2) }}
                        @endif
                        @if(!empty($data['items_count']))
                            &bull; {{ $data['items_count'] }} item(s)
                        @endif
                    </span>
                @elseif($type === 'new_user')
                    <span class="d-block text-muted" style="font-size: .78rem;">
                        {{ $data['user_email'] ?? 'No email on record' }}
                        @if(!empty($data['user_role']))
                            &bull; {{ ucfirst($data['user_role']) }}
                        @endif
                    </span>
                @endif

                <span class="d-block text-muted" style="font-size: .75rem;">
                    <i class="bi bi-clock me-1"></i>
                    <span title="{{ $notification->created_at?->toDayDateTimeString() }}">
                        {{ $notification->created_at?->diffForHumans() }}
                    </span>
                    &bull;
                    @if($isUnread)
                        <span class="text-primary fw-semibold">Unread</span>
                    @else
                        Read
                    @endif
                </span>
            </span>
        </button>
    </form>
@empty
    <div class="px-3 py-5 text-center text-muted small">
        <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
        No notifications yet.
    </div>
@endforelse

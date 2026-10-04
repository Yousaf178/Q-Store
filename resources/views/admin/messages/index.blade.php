@extends('layouts.app')

@section('title', 'Customer Chats - Admin Panel')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="p-4 bg-dark text-white rounded-3 shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1"><i class="bi bi-chat-dots text-warning me-2"></i> Customer Chats</h2>
                <p class="text-white-50 mb-0">Every customer conversation in one place. Replies show up instantly in their chat window.</p>
            </div>
            <div>
                <span class="badge {{ $totalUnread > 0 ? 'bg-danger' : 'bg-success' }} fs-6 px-3 py-2">
                    <i class="bi bi-envelope{{ $totalUnread > 0 ? '-exclamation' : '-check' }} me-1"></i>
                    {{ $totalUnread }} unread message{{ $totalUnread === 1 ? '' : 's' }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <form action="{{ route('admin.messages.index') }}" method="GET" class="row g-2">
            <div class="col-md-9">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search by customer name or email…"
                    value="{{ $search }}"
                >
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
                @if($search)
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <div class="list-group list-group-flush">
        @forelse($conversations as $conversation)
            @php
                $last = $lastMessages[$conversation->last_message_id] ?? null;
                $customer = $last?->user;
                $unread = (int) $conversation->unread_count;
            @endphp

            <a
                href="{{ route('admin.messages.show', $conversation->user_id) }}"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-start gap-3 py-3 {{ $unread > 0 ? 'bg-primary-subtle' : '' }}"
            >
                <div class="d-flex gap-3 align-items-start">
                    <span class="fs-3 text-secondary"><i class="bi bi-person-circle"></i></span>
                    <div>
                        <div class="fw-bold {{ $unread > 0 ? 'text-dark' : 'text-muted' }}">
                            {{ $customer->name ?? 'Customer #' . $conversation->user_id }}
                        </div>
                        <div class="small text-muted">{{ $customer->email ?? '' }}</div>
                        <div class="small text-truncate" style="max-width: 30rem;">
                            @if($last)
                                @if($last->is_from_admin)
                                    <span class="badge bg-secondary-subtle text-secondary">You</span>
                                @endif
                                {{ Str::limit($last->body, 80) }}
                            @else
                                <span class="fst-italic">Conversation unavailable</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-end small text-muted flex-shrink-0">
                    <div>{{ $last?->created_at?->diffForHumans() }}</div>
                    @if($unread > 0)
                        <span class="badge bg-danger rounded-pill mt-1">{{ $unread }} new</span>
                    @endif
                </div>
            </a>
        @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-chat-square-dots fs-1 d-block mb-2"></i>
                No customer conversations yet.
            </div>
        @endforelse
    </div>

    @if($conversations->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $conversations->links() }}
        </div>
    @endif
</div>
@endsection

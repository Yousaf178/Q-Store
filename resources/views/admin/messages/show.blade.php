@extends('layouts.app')

@section('title', 'Chat with ' . $user->name . ' - Admin Panel')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-chat-dots text-primary me-2"></i> {{ $user->name }}
                </h2>
                <p class="text-muted mb-0">
                    {{ $user->email }}
                    &bull;
                    <span class="badge bg-secondary text-uppercase">{{ $user->role }}</span>
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> All chats
                </a>
                <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-bag-check me-1"></i> Their orders
                </a>
                <a href="{{ route('admin.users.index', ['search' => $user->email]) }}" class="btn btn-outline-dark btn-sm">
                    <i class="bi bi-person me-1"></i> User record
                </a>
            </div>
        </div>

        @include('chat.panel', [
            'messages' => $messages,
            'panelTitle' => 'Conversation with ' . $user->name,
            'feedUrl' => route('admin.messages.feed', $user),
            'sendUrl' => route('admin.messages.store', $user),
        ])
    </div>
</div>
@endsection

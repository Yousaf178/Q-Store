@extends('layouts.app')

@section('title', 'Support Chat - QShop')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-4">
            <h2 class="fw-bold mb-1">
                <i class="bi bi-headset text-primary me-2"></i> Support Chat
            </h2>
            <p class="text-muted mb-0">
                Ask us about an order, a product or a delivery. Our team replies in this window.
            </p>
        </div>

        @include('chat.panel', [
            'messages' => $messages,
            'panelTitle' => 'Chat with Support',
            'feedUrl' => route('messages.feed'),
            'sendUrl' => route('messages.store'),
        ])
    </div>
</div>
@endsection

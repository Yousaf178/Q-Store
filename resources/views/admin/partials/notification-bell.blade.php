@php
    // Rendered for admins only; feeds the bell badge and the dropdown list.
    $bellNotifications = Auth::user()->notifications()->latest()->limit(10)->get();
    $bellUnreadCount = Auth::user()->unreadNotifications()->count();
@endphp

<li class="nav-item me-3 dropdown">
    <button
        class="btn btn-outline-light btn-sm position-relative"
        type="button"
        id="adminNotificationBell"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-expanded="false"
        title="Notifications"
    >
        <i class="bi bi-bell"></i>
        <span class="d-none d-lg-inline ms-1">Alerts</span>
        <span
            id="adminNotificationBadge"
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $bellUnreadCount > 0 ? '' : 'd-none' }}"
        >
            {{ $bellUnreadCount > 99 ? '99+' : $bellUnreadCount }}
        </span>
    </button>

    <div class="dropdown-menu dropdown-menu-end shadow p-0" style="width: 22rem;">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
            <span class="fw-bold small text-uppercase text-muted">
                <i class="bi bi-bell me-1"></i> Notifications
            </span>
            <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn btn-link btn-sm p-0 small text-decoration-none">
                    Mark all read
                </button>
            </form>
        </div>

        <div id="adminNotificationList" style="max-height: 24rem; overflow-y: auto;">
            @include('admin.partials.notification-list', ['notifications' => $bellNotifications])
        </div>

        <div class="border-top px-3 py-2 text-center bg-light">
            <a href="{{ route('admin.orders.index') }}" class="small text-decoration-none">
                View all orders
            </a>
        </div>
    </div>
</li>

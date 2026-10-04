<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QShop - Laravel Store')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-content {
            flex: 1;
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: 1px;
        }
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border-radius: 1rem;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand text-warning" href="{{ Auth::check() ? (Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard')) : route('login') }}">
                <i class="bi bi-shop me-1"></i> QShop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Admin Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                    <i class="bi bi-box-seam me-1"></i> Manage Products
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                                    <i class="bi bi-tags me-1"></i> Manage Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <i class="bi bi-people me-1"></i> Manage Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                                    <i class="bi bi-card-checklist me-1"></i> Manage Orders
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-house-door me-1"></i> Products
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                                    <i class="bi bi-bag-check me-1"></i> My Orders
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        @if(Auth::user()->isAdmin())
                            @include('admin.partials.notification-bell')
                        @endif

                        @php
                            $cartCount = 0;
                            if (session()->has('cart')) {
                                foreach (session('cart') as $cItem) {
                                    $cartCount += $cItem['quantity'];
                                }
                            }
                        @endphp
                        <li class="nav-item me-3">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-warning btn-sm position-relative">
                                <i class="bi bi-cart3 me-1"></i> Cart
                                @if($cartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item me-3 text-light">
                            <span class="badge {{ Auth::user()->isAdmin() ? 'bg-danger' : 'bg-primary' }} text-uppercase me-1">
                                {{ Auth::user()->role }}
                            </span>
                            <span class="fw-semibold">{{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Sign In / Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content Area -->
    <main class="main-content py-4">
        <div class="container">
            <!-- Flash Alerts are now handled by SweetAlert2 Toasts at the bottom of the page -->

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-3 text-center text-muted small">
        <div class="container">
            &copy; {{ date('Y') }} QShop. All rights reserved.
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{!! addslashes(session('success')) !!}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{!! addslashes(session('error')) !!}'
            });
        @endif

        @if(Auth::check() && Auth::user()->isAdmin())
            let currentDbMaxOrder = {{ \App\Models\Order::max('id') ?? 0 }};
            let currentDbMaxUser = {{ \App\Models\User::max('id') ?? 0 }};
            
            let lastOrderId = localStorage.getItem('admin_last_order_id');
            let lastUserId = localStorage.getItem('admin_last_user_id');

            // Initialize localStorage with current max if empty (e.g. first time admin logs in)
            if (lastOrderId === null) {
                lastOrderId = currentDbMaxOrder;
                localStorage.setItem('admin_last_order_id', lastOrderId);
            }
            if (lastUserId === null) {
                lastUserId = currentDbMaxUser;
                localStorage.setItem('admin_last_user_id', lastUserId);
            }
            
            function checkNotifications() {
                fetch(`/admin/notifications/check?last_order_id=${lastOrderId}&last_user_id=${lastUserId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.new_orders > 0) {
                            Toast.fire({
                                icon: 'info',
                                title: `🔔 You have ${data.new_orders} new order(s)!`
                            });
                            lastOrderId = data.latest_order_id;
                            localStorage.setItem('admin_last_order_id', lastOrderId);
                        }
                        if (data.new_users > 0) {
                            Toast.fire({
                                icon: 'info',
                                title: `👋 ${data.new_users} new user(s) just registered!`
                            });
                            lastUserId = data.latest_user_id;
                            localStorage.setItem('admin_last_user_id', lastUserId);
                        }
                    })
                    .catch(err => console.error('Error fetching notifications:', err));
            }

            // Bell dropdown: refresh the badge and the list without reloading the page.
            const notificationList = document.getElementById('adminNotificationList');
            const notificationBadge = document.getElementById('adminNotificationBadge');

            function refreshAdminNotifications() {
                if (!notificationList || !notificationBadge) {
                    return;
                }

                fetch(@json(route('admin.notifications.feed')), { headers: { 'Accept': 'application/json' } })
                    .then(res => res.json())
                    .then(data => {
                        notificationList.innerHTML = data.html;

                        if (data.unread_count > 0) {
                            notificationBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                            notificationBadge.classList.remove('d-none');
                        } else {
                            notificationBadge.classList.add('d-none');
                        }
                    })
                    .catch(err => console.error('Error fetching notifications:', err));
            }

            // Run check immediately on page load to catch events that happened while navigating
            checkNotifications();
            refreshAdminNotifications();

            // Then continuously poll every 10 seconds. The bell refreshes on every third
            // tick (30s) so each open admin tab stays at a small number of queries.
            let notificationTick = 0;
            setInterval(function () {
                checkNotifications();

                if (++notificationTick % 3 === 0) {
                    refreshAdminNotifications();
                }
            }, 10000);
        @endif
    </script>
</body>
</html>

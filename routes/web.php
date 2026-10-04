<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminUserController;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Guest Routes (Authentication)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes (All Logged-in Users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User Products Catalog / Dashboard
    Route::get('/dashboard', function (Request $request) {
        $search = $request->search;
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;
        $brand = $request->brand;

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when(!is_null($minPrice), function ($query) use ($minPrice) {
                $query->where('price', '>=', $minPrice);
            })
            ->when(!is_null($maxPrice), function ($query) use ($maxPrice) {
                $query->where('price', '<=', $maxPrice);
            })
            ->when($brand, function ($query, $brand) {
                $query->where('brand', 'like', '%' . $brand . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dashboard', compact('products'));
    })->name('dashboard');

    // Product Details
    Route::get('/products/{product}/details', [ProductController::class, 'details'])->name('products.details');

    // Shopping Cart Routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
        Route::post('/update/{product}', [CartController::class, 'update'])->name('update');
        Route::post('/remove/{product}', [CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    });

    // Checkout & Online Payment Routes
    Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'processPayment'])->name('checkout.process');
    Route::get('/order/{order}/success', [CheckoutController::class, 'orderSuccess'])->name('order.success');
    Route::get('/orders', [CheckoutController::class, 'myOrders'])->name('orders.index');

    // Admin Protected Routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            $productsCount = Product::count();
            $categoriesCount = Category::count();
            $usersCount = User::count();
            $ordersCount = \App\Models\Order::count();

            return view('admin.dashboard', compact('productsCount', 'categoriesCount', 'usersCount', 'ordersCount'));
        })->name('admin.dashboard');

        Route::get('/admin/notifications/check', function (Request $request) {
            $lastOrderId = (int) $request->query('last_order_id', 0);
            $lastUserId = (int) $request->query('last_user_id', 0);
            
            $newOrders = \App\Models\Order::where('id', '>', $lastOrderId)->count();
            $newUsers = User::where('id', '>', $lastUserId)->count();
            
            $latestOrder = \App\Models\Order::latest('id')->first();
            $latestUser = User::latest('id')->first();

            return response()->json([
                'new_orders' => $newOrders,
                'new_users' => $newUsers,
                'latest_order_id' => $latestOrder ? $latestOrder->id : 0,
                'latest_user_id' => $latestUser ? $latestUser->id : 0,
            ]);
        })->name('admin.notifications.check');

        // Admin notification bell (database notifications)
        Route::get('/admin/notifications/feed', [AdminNotificationController::class, 'feed'])
            ->name('admin.notifications.feed');
        Route::post('/admin/notifications/read-all', [AdminNotificationController::class, 'readAll'])
            ->name('admin.notifications.read-all');
        Route::post('/admin/notifications/{notification}/read', [AdminNotificationController::class, 'read'])
            ->name('admin.notifications.read');

        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');

        // Admin Order Routes
        Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/admin/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('admin.orders.cancel');

        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
    });
});
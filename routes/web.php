<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
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

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(6)
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

            return view('admin.dashboard', compact('productsCount', 'categoriesCount', 'usersCount'));
        })->name('admin.dashboard');

        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');

        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
    });
});
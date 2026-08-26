<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show the checkout form with order summary.
     */
    public function showCheckout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before proceeding to checkout.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $total = $subtotal;

        return view('checkout.index', compact('cart', 'subtotal', 'total'));
    }

    /**
     * Process the online payment and place the order.
     */
    public function processPayment(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate basic checkout fields
        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
        ];

        // Conditional validation for Card payments
        if ($request->payment_method === 'credit_card') {
            $rules['card_name'] = 'required|string|max:255';
            $rules['card_number'] = 'required|string|min:12|max:20';
            $rules['card_expiry'] = 'required|string|max:7';
            $rules['card_cvv'] = 'required|string|min:3|max:4';
        }

        $request->validate($rules);

        // Verify stock availability for all cart items
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product || $product->quantity < $item['quantity']) {
                $available = $product ? $product->quantity : 0;
                return redirect()->route('cart.index')
                    ->with('error', "Sorry, '{$item['name']}' only has {$available} unit(s) left in stock. Please adjust your cart.");
            }
        }

        // Process transaction in database
        try {
            $order = DB::transaction(function () use ($request, $cart) {
                $subtotal = 0;
                foreach ($cart as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }

                $total = $subtotal;

                // Create Order record
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                    'total_amount' => $total,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'completed',
                    'transaction_id' => 'TXN-' . date('YmdHis') . '-' . strtoupper(Str::random(8)),
                    'shipping_address' => $request->shipping_address,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                ]);

                // Create Order Items and decrement product stock
                foreach ($cart as $productId => $item) {
                    $product = Product::lockForUpdate()->find($productId);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product ? $product->id : null,
                        'product_name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    if ($product) {
                        $product->decrement('quantity', $item['quantity']);
                    }
                }

                return $order;
            });

            // Clear session cart
            session()->forget('cart');

            return redirect()->route('order.success', $order)
                ->with('success', '🎉 Payment received! Your order has been placed successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Payment processing failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display order confirmation and receipt.
     */
    public function orderSuccess(Order $order)
    {
        // Guard so users only see their own orders unless admin
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access to this order receipt.');
        }

        $order->load('items.product');

        return view('checkout.success', compact('order'));
    }

    /**
     * Display all orders placed by the current user.
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}

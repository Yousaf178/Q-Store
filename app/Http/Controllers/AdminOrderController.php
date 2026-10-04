<?php

namespace App\Http\Controllers;

use App\Mail\OrderCancelledAdminMail;
use App\Mail\OrderCancelledMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of all customer orders.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $paymentMethod = $request->payment_method;
        $userId = $request->user_id;

        $selectedUser = null;
        if ($userId) {
            $selectedUser = User::find($userId);
        }

        $orders = Order::with(['items.product', 'user'])
            ->when($userId, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when($paymentMethod, function ($query, $paymentMethod) {
                $query->where('payment_method', $paymentMethod);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', '%' . $search . '%')
                      ->orWhere('customer_name', 'like', '%' . $search . '%')
                      ->orWhere('customer_email', 'like', '%' . $search . '%')
                      ->orWhere('transaction_id', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalOrdersCount = Order::count();
        $totalRevenue = Order::sum('total_amount');
        $cardOrdersCount = Order::where('payment_method', 'credit_card')->count();
        $paypalOrdersCount = Order::where('payment_method', 'paypal')->count();

        return view('admin.orders.index', compact(
            'orders',
            'search',
            'paymentMethod',
            'userId',
            'selectedUser',
            'totalOrdersCount',
            'totalRevenue',
            'cardOrdersCount',
            'paypalOrdersCount'
        ));
    }

    /**
     * Display specific order details for admin.
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cancel an order and notify the customer and admin.
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'cancelled') {
            return back()->with('error', 'This order is already cancelled.');
        }

        $order->update(['status' => 'cancelled']);
        $order->load('items');

        // Notify the customer
        Mail::to($order->customer_email)->send(new OrderCancelledMail($order));

        // Notify the admin
        $adminEmail = env('ADMIN_EMAIL');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new OrderCancelledAdminMail($order));
        }

        return back()->with('success', "Order {$order->order_number} has been cancelled and the customer has been notified.");
    }
}

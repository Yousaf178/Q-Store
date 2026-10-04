<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderPlaced extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Payload stored in the notifications table and rendered in the admin bell.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_order',
            'message' => 'New order received from ' . $this->order->customer_name
                . '. Order #' . $this->order->order_number,
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->customer_name,
            'customer_email' => $this->order->customer_email,
            'payment_method' => $this->order->payment_method,
            'total_amount' => (float) $this->order->total_amount,
            'items_count' => $this->order->relationLoaded('items')
                ? $this->order->items->count()
                : $this->order->items()->count(),
            // Relative path so it survives host changes (localhost vs 127.0.0.1).
            'url' => route('admin.orders.show', $this->order, false),
        ];
    }
}

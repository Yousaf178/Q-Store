<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewChatMessage extends Notification
{
    use Queueable;

    public function __construct(public Message $message)
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
        $customer = $this->message->user;
        $senderName = optional($this->message->sender)->name
            ?? optional($customer)->name
            ?? 'A customer';

        return [
            'type' => 'new_chat_message',
            'message' => 'New message from ' . $senderName . ': '
                . Str::limit($this->message->body, 60),
            'user_id' => $this->message->user_id,
            'user_name' => optional($customer)->name,
            'user_email' => optional($customer)->email,
            'user_role' => optional($customer)->role,
            'message_id' => $this->message->id,
            // Relative path so it survives host changes (localhost vs 127.0.0.1).
            'url' => route('admin.messages.show', $this->message->user_id, false),
        ];
    }
}

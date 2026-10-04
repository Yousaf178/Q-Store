<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    use Queueable;

    public function __construct(public User $user)
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
            'type' => 'new_user',
            'message' => 'New user registered: ' . $this->user->name,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'user_role' => $this->user->role,
            // No admin user-details page exists yet, so this lands on the users
            // list filtered down to this member (search matches name or email).
            // Stored as a relative path so it survives host changes (localhost vs 127.0.0.1).
            'url' => route('admin.users.index', ['search' => $this->user->email], false),
        ];
    }
}

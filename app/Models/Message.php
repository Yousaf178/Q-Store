<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'sender_id',
        'is_from_admin',
        'body',
        'read_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_from_admin' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    /**
     * The customer whose conversation this message belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Who actually wrote the message (customer or admin).
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Restrict to a single customer's conversation.
     */
    public function scopeConversation(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Mark everything the viewer has not written in this thread as read.
     *
     * Works for both sides: a customer reading admin replies, and an admin
     * reading customer messages.
     */
    public static function markThreadRead(int $threadUserId, int $viewerId): int
    {
        return static::query()
            ->where('user_id', $threadUserId)
            ->where('sender_id', '!=', $viewerId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * The viewer's newest message in a thread, used for the "Seen" indicator.
     */
    public static function lastOwnMessage(int $threadUserId, int $viewerId): ?self
    {
        return static::query()
            ->where('user_id', $threadUserId)
            ->where('sender_id', $viewerId)
            ->latest('id')
            ->first();
    }

    /**
     * Unread chat messages for a user.
     *
     * Customers count unread replies in their own thread; admins count every
     * unread message sent by a customer, across all conversations.
     */
    public static function unreadCountFor(User $user): int
    {
        return static::query()
            ->whereNull('read_at')
            ->when(
                $user->isAdmin(),
                fn (Builder $query) => $query->where('is_from_admin', false),
                fn (Builder $query) => $query->where('user_id', $user->id)->where('is_from_admin', true),
            )
            ->count();
    }
}

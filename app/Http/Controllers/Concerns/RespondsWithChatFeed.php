<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Message;
use App\Models\User;

trait RespondsWithChatFeed
{
    /**
     * Build the JSON payload the chat panel polls for.
     *
     * @return array<string, mixed>
     */
    protected function chatFeedPayload(int $threadUserId, User $viewer, int $after): array
    {
        $messages = Message::conversation($threadUserId)
            ->with('sender')
            ->where('id', '>', $after)
            ->orderBy('id')
            ->get();

        // Whatever the other side sent is on screen now, so it counts as read.
        Message::markThreadRead($threadUserId, $viewer->id);

        $own = Message::lastOwnMessage($threadUserId, $viewer->id);
        $lastId = $messages->isNotEmpty() ? $messages->last()->id : $after;

        return [
            'html' => $messages->isEmpty()
                ? ''
                : view('chat.messages', ['messages' => $messages])->render(),
            'last_id' => $lastId,
            'unread_count' => Message::unreadCountFor($viewer),
            'seen' => $own ? [
                'id' => $own->id,
                'read_at' => optional($own->read_at)->toIso8601String(),
            ] : null,
        ];
    }
}

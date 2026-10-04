<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsWithChatFeed;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMessageController extends Controller
{
    use RespondsWithChatFeed;

    /**
     * Shared inbox: every customer conversation, newest activity first.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $conversations = Message::query()
            ->select('user_id')
            ->selectRaw('MAX(id) as last_message_id')
            ->selectRaw('SUM(CASE WHEN is_from_admin = 0 AND read_at IS NULL THEN 1 ELSE 0 END) as unread_count')
            // Admins chat with customers; an admin's own customer-side thread
            // stays on their personal /messages page instead of this inbox.
            ->whereHas('user', fn ($query) => $query->where('role', '!=', 'admin'))
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->groupBy('user_id')
            ->orderByDesc('last_message_id')
            ->paginate(15)
            ->withQueryString();

        $lastMessageIds = $conversations->getCollection()->pluck('last_message_id')->filter()->all();

        $lastMessages = empty($lastMessageIds)
            ? collect()
            : Message::with(['sender', 'user'])
                ->whereIn('id', $lastMessageIds)
                ->get()
                ->keyBy('id');

        $totalUnread = Message::unreadCountFor($request->user());

        return view('admin.messages.index', compact('conversations', 'lastMessages', 'search', 'totalUnread'));
    }

    /**
     * One customer's conversation.
     */
    public function show(User $user)
    {
        $messages = Message::conversation($user->id)
            ->with('sender')
            ->orderBy('id')
            ->get();

        Message::markThreadRead($user->id, Auth::id());

        return view('admin.messages.show', compact('user', 'messages'));
    }

    /**
     * Reply to a customer.
     */
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $admin = $request->user();

        $message = Message::create([
            'user_id' => $user->id,
            'sender_id' => $admin->id,
            'is_from_admin' => true,
            'body' => trim($validated['body']),
        ]);

        $message->setRelation('user', $user);
        $message->setRelation('sender', $admin);

        // Replying implies the customer's messages have been read.
        Message::markThreadRead($user->id, $admin->id);

        if ($request->expectsJson()) {
            // The panel sends the last id it already has, so the response carries the
            // new reply plus anything that arrived in between.
            return response()->json($this->chatFeedPayload(
                $user->id,
                $admin,
                (int) $request->input('after', 0)
            ));
        }

        return back()->with('success', 'Reply sent.');
    }

    /**
     * New messages for the open conversation (polled by the chat panel).
     */
    public function feed(Request $request, User $user)
    {
        $after = (int) $request->query('after', 0);

        return response()->json($this->chatFeedPayload($user->id, $request->user(), $after));
    }
}

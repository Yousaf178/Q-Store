<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsWithChatFeed;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    use RespondsWithChatFeed;

    /**
     * Show the signed-in user's support conversation.
     */
    public function index()
    {
        $user = Auth::user();

        // Admins answer from the shared inbox, so the customer view would be a
        // dead end for them (their own thread has no admin counterpart).
        if ($user->isAdmin()) {
            return redirect()->route('admin.messages.index');
        }

        $messages = Message::conversation($user->id)
            ->with('sender')
            ->orderBy('id')
            ->get();

        Message::markThreadRead($user->id, $user->id);

        return view('messages.index', compact('messages'));
    }

    /**
     * Send a message to the support team.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        $message = Message::create([
            'user_id' => $user->id,
            'sender_id' => $user->id,
            'is_from_admin' => $user->isAdmin(),
            'body' => trim($validated['body']),
        ]);

        // Reuse the account we already loaded instead of re-querying it.
        $message->setRelation('user', $user);
        $message->setRelation('sender', $user);

        if (! $message->is_from_admin) {
            $this->notifyAdminsOncePerUnreadBurst($message);
        }

        if ($request->expectsJson()) {
            // The panel sends the last id it already has, so the response carries the
            // new message plus anything that arrived in between.
            return response()->json($this->chatFeedPayload(
                $user->id,
                $user,
                (int) $request->input('after', 0)
            ));
        }

        return back();
    }

    /**
     * New messages for the open conversation (polled by the chat panel).
     */
    public function feed(Request $request)
    {
        $user = Auth::user();
        $after = (int) $request->query('after', 0);

        return response()->json($this->chatFeedPayload($user->id, $user, $after));
    }

    /**
     * Raise a bell notification for admins, but only for the first unread
     * message of a burst so a rapid series of messages cannot flood the bell.
     */
    private function notifyAdminsOncePerUnreadBurst(Message $message): void
    {
        $alreadyWaiting = Message::query()
            ->where('user_id', $message->user_id)
            ->where('is_from_admin', false)
            ->whereNull('read_at')
            ->whereKeyNot($message->id)
            ->exists();

        if ($alreadyWaiting) {
            return;
        }

        User::where('role', 'admin')
            ->get()
            ->each(fn (User $admin) => $admin->notify(new NewChatMessage($message)));
    }
}

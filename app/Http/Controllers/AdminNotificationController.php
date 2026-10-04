<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class AdminNotificationController extends Controller
{
    /**
     * How many notifications the bell dropdown shows.
     */
    private const FEED_LIMIT = 10;

    /**
     * JSON feed used by the bell to refresh its badge and list.
     */
    public function feed(Request $request)
    {
        $admin = $request->user();

        $notifications = $admin->notifications()
            ->latest()
            ->limit(self::FEED_LIMIT)
            ->get();

        return response()->json([
            'unread_count' => $admin->unreadNotifications()->count(),
            'latest_id' => $notifications->first()->id ?? null,
            'html' => view('admin.partials.notification-list', [
                'notifications' => $notifications,
            ])->render(),
        ]);
    }

    /**
     * Mark one notification as read, then send the admin to what it refers to.
     */
    public function read(Request $request, DatabaseNotification $notification)
    {
        $this->authorizeOwnership($request, $notification);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return redirect()->to($this->targetUrl($notification));
    }

    /**
     * Mark every unread notification of the current admin as read.
     */
    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * An admin may only open notifications addressed to them.
     */
    private function authorizeOwnership(Request $request, DatabaseNotification $notification): void
    {
        $admin = $request->user();

        abort_unless(
            $notification->notifiable_type === $admin->getMorphClass()
                && (string) $notification->notifiable_id === (string) $admin->getKey(),
            403,
            'This notification does not belong to you.'
        );
    }

    /**
     * Resolve the notification target, refusing anything outside this application.
     *
     * Targets are stored as root-relative paths, so a leading "//" (protocol
     * relative, i.e. another host) is rejected along with absolute URLs.
     */
    private function targetUrl(DatabaseNotification $notification): string
    {
        $url = $notification->data['url'] ?? null;

        if (is_string($url) && str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return $url;
        }

        return route('admin.dashboard');
    }
}

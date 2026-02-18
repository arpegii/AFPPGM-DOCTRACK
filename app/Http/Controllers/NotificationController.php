<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user
     */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function unreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);
            
        $notification->markAsRead();

        if ($request->boolean('stay')) {
            return back()->with('success', 'Notification marked as read!');
        }

        // Redirect to the URL in the notification data
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read!');
    }

    /**
     * Mark selected notifications as read.
     */
    public function markSelectedAsRead(Request $request)
    {
        $data = $request->validate([
            'apply_to_all' => ['nullable', 'boolean'],
            'notification_ids' => ['required_without:apply_to_all', 'array', 'min:1'],
            'notification_ids.*' => ['required', 'string'],
        ]);

        $query = Auth::user()->notifications()->whereNull('read_at');

        if (empty($data['apply_to_all'])) {
            $query->whereIn('id', $data['notification_ids']);
        }

        $updatedCount = $query->update(['read_at' => now()]);

        return back()->with('success', "{$updatedCount} notification(s) marked as read.");
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return back()->with('success', 'Notification deleted!');
    }

    /**
     * Delete selected notifications.
     */
    public function destroySelected(Request $request)
    {
        $data = $request->validate([
            'apply_to_all' => ['nullable', 'boolean'],
            'notification_ids' => ['required_without:apply_to_all', 'array', 'min:1'],
            'notification_ids.*' => ['required', 'string'],
        ]);

        $query = Auth::user()->notifications();

        if (empty($data['apply_to_all'])) {
            $query->whereIn('id', $data['notification_ids']);
        }

        $deletedCount = $query->delete();

        return back()->with('success', "{$deletedCount} notification(s) deleted.");
    }
}

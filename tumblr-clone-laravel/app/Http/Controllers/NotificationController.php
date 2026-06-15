<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $request->validate(['id' => 'required|string']);

        auth()->user()
            ->notifications()
            ->where('id', $request->id)
            ->first()
            ?->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserNotificationController extends Controller
{
    public function getUnreadNotifications()
    {
        $user = Auth::user();
        if (!$user) {
            Log::warning('UserNotificationController: User not authenticated.');
            return response()->json([], 401);
        }

        // Fetch only unread notifications for the badge count
        $unreadNotifications = $user->unreadNotifications;

        Log::info('UserNotificationController: Fetched unread notifications count: ' . $unreadNotifications->count());
        Log::info('UserNotificationController: Unread notifications data:', $unreadNotifications->toArray());

        return response()->json($unreadNotifications);
    }

    // public function getAllNotifications()
    // {
    //     $user = Auth::user();
    //     if (!$user) {
    //         Log::warning('UserNotificationController: User not authenticated.');
    //         return response()->json([], 401);
    //     }

    //     // Fetch all notifications for the dropdown list
    //     $notifications = $user->notifications()->orderByDesc('created_at')->get();

    //     Log::info('UserNotificationController: Fetched all notifications for list: ' . $notifications->count());
    //     // Note: Logging full data might be verbose for many notifications
    //     // Log::info('UserNotificationController: All notifications data for list:', $notifications->toArray());

    //     return response()->json($notifications);
    // }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();
         if (!$user) {
            Log::warning('UserNotificationController: User not authenticated for marking as read.');
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $user->unreadNotifications->markAsRead();

        Log::info('UserNotificationController: Marked all unread notifications as read for user ' . $user->id);

        return response()->json(['message' => 'All notifications marked as read']);
    }
} 
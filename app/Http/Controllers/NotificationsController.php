<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function get_notifications(Request $request)
    {
        $auth_user = auth()->user();
        $user = User::where('user_code', $auth_user->user_code)->first();

        $notifications = $user->notifications()->get();

        return response()->json([
            'api_code' => 'SUCCESS',
            'api_msg' => 'Notifications retrieved successfully.',
            'api_status' => true,
            'data' => [
                'notifications' => $notifications,
            ]
        ], 200);
    }

    public function mark_all_as_read(Request $request)
    {
        $auth_user = auth()->user();
        $user = User::where('user_code', $auth_user->user_code)->first();

        $user->unreadNotifications->markAsRead();

        return response()->json([
            'api_code' => 'SUCCESS',
            'api_msg' => 'Notifications marked as read successfully.',
            'api_status' => true,
            'data' => [
                'notifications' => $user->notifications()->get(),
            ]
        ], 200);
    }

    public function mark_as_read(Request $request)
    {
        $auth_user = auth()->user();
        $user = User::where('user_code', $auth_user->user_code)->first();

        $notification = $user->notifications()->where('id', $request->notification_id)->first();

        if($notification)
        {
            $notification->markAsRead();

            return response()->json([
                'api_code' => 'SUCCESS',
                'api_msg' => 'Notification marked as read successfully.',
                'api_status' => true,
                'data' => [
                    'notifications' => $user->notifications()->get(),
                ]
            ], 200);
        }

        return response()->json([
            'api_code' => 'ERROR',
            'api_msg' => 'Notification not found.',
            'api_status' => false,
            'data' => [
                'notifications' => $user->notifications()->get(),
            ]
        ], 404);
    }
}

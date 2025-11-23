<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve notifications for the authenticated user
        $notifications = $request->user()->notifications;

        // Return the notifications as JSON response
        return ResponseHelper::success($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
            $notification->markAsRead();

        return ResponseHelper::success();
        
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return ResponseHelper::success();
    }

    public function getUnreadNotifications()
    {
        $unreadNotifications = Auth::user()->unreadNotifications;

        return ResponseHelper::success($unreadNotifications);
    }


    public function receiveLmsNotification(Request $request)
    {
        $notificationData = $request->validate([
            'notification.id'       => 'required',
            'notification.user_id'  => 'required',
            'notification.title'    => 'nullable|string',  
            'notification.message'  => 'nullable|string',
            'notification.url'      => 'nullable|string',
            'notification.date'     => 'nullable|date',
            'notification.note'     => 'nullable|array',
        ]);
        
        try {
            broadcast(new \App\Events\NotificationBroadcasted($notificationData['notification']));

            return response()->json([
                'message' => 'Notification dispatched via Reverb',
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Failed to dispatch event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}

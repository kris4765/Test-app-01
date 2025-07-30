<?php

namespace App\Http\Controllers;
use App\Models\Notification;


use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userEmail = $request->user()->email;

        $notifications = Notification::where('send_to', $userEmail)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    public function store(Request $request)
    {
        $request->validate([
            'send_to' => 'required|string',
            'data' => 'required|string',
        ]);

        $notification = Notification::create([
            'send_from' => $request->user()->email,
            'send_to' => $request->send_to,
            'data' => $request->data,
        ]);

        return response()->json($notification);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json(['status' => 'marked_as_read']);
    }

}

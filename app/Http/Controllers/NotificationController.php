<?php

namespace App\Http\Controllers;
use App\Models\Notification;
use App\Models\User;

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
        // $notification->is_read = true;
        // $notification->save();

        $notification->delete(); // Assuming you want to delete the notification after marking it as read

        return response()->json(['status' => 'marked_as_read']);
    }





    public function notifyAdminsOnLogin(Request $request)
    {
        $sender = User::where('email', $request->email)->first();

        if (!$sender) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($sender->role !== 'employee') {
            return response()->json(['message' => 'Only employees trigger admin notifications'], 403);
        }

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'send_from' => $sender->email,
                'send_to' => $admin->email,
                'data' => "{$sender->email} has logged in.",
                'is_read' => false,
            ]);
        }

        return response()->json(['message' => 'Admins notified']);
    }




    public function nnotifytodo(Request $request)
    {

        

        $sender = User::where('email', $request->email)->first();
        if (!$sender) {
            return response()->json(['error' => 'User not found', "email" => $request->email], 404);
        }


        // Assuming you want to notify admins about a todo action
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'send_from' => $sender->email,
                'send_to' => $admin->email,
                'data' => "{$sender->email} has a new todo action ss.",
                'is_read' => false,
            ]);
        }

        return response()->json(['message' => 'Admins notified about todo action']);

        
        // This function seems to be incomplete or not defined in the original code.
        // You can implement the logic here as needed.
    }

}

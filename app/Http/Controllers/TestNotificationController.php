<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class TestNotificationController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/TestNotification', [
            'notifications' => auth()->user()->notifications()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($n) => [
                    'id' => $n->id,
                    'notification' => $n->notification,
                    'type' => $n->type,
                    'created_at' => $n->created_at->format('Y-m-d H:i:s')
                ])
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:limit,message,monthlyexpense',
            'message' => 'required|string'
        ]);

        Notification::create([
            'user_id' => auth()->id(),
            'notification' => $request->message,
            'type' => $request->type
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy()
    {
        auth()->user()->notifications()->delete();
        return response()->json(['success' => true]);
    }
}
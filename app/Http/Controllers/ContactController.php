<?php

namespace App\Http\Controllers;

use App\Models\AdminMessage;
use App\Models\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    // Display contact page with paginated messages
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = 10; // Number of messages per page

        // Received messages (from admin)
        $receivedMessages = AdminMessage::where('user_id', $user->id)
            ->orderBy('is_read')
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'received_page');

        // Sent messages (to admin)
        $sentMessages = UserMessage::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'sent_page');

        return inertia('Dashboard/Contact', [
            'receivedMessages' => $receivedMessages,
            'sentMessages' => $sentMessages,
            
        ]);
    }

    // Store support request (message to admin)
    public function support(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        UserMessage::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Message sent successfully');
    }

    // Mark message as read
    public function read(AdminMessage $message)
    {
        $message->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    // Send reply to admin
    public function reply(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'original_message_id' => 'required|exists:admin_messages,id',
        ]);

        $user = Auth::user();

        UserMessage::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Reply sent successfully');
    }

    // Delete sent message
    public function destroy(UserMessage $message)
    {
        $message->delete();
        return redirect()->back()->with('success', 'Message deleted successfully');
    }
}
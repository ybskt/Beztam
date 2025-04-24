<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserMessage;
use App\Models\AdminMessage;
use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserMessagesController extends Controller
{
    public function index()
    {
        $reclamations = UserMessage::with('user')
            ->orderBy('is_read')
            ->orderByDesc('created_at')
            ->paginate(10); // 10 items per page

        return Inertia::render('Admin/Reclamations', [
            'reclamations' => $reclamations->items(),
            'pagination' => [
                'current_page' => $reclamations->currentPage(),
                'last_page' => $reclamations->lastPage(),
                'per_page' => $reclamations->perPage(),
                'total' => $reclamations->total(),
            ]
        ]);
    }

    public function markAsRead(UserMessage $message)
    {
        $message->update(['is_read' => 1]);
        return response()->json(['success' => true]);
    }

    public function reply(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        AdminMessage::create([
            'user_id' => $request->user_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => 0
        ]);
        
        Notification::create([
            'user_id' => $request->user_id,
            'notification' => 'Nouveau message disponible',
            'type' => 'message',
            'created_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(UserMessage $message)
    {
        $message->delete();
        return back()->with('success', 'Message utilisateur supprimé avec succès');
    }
}
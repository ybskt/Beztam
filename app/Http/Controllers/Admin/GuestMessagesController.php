<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuestMessagesController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $currentPage = $request->input('page', 1);
        
        $messages = GuestMessage::orderBy('is_read')
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $currentPage);

        return Inertia::render('Admin/Messages', [
            'messages' => $messages->items(),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total()
            ]
        ]);
    }

    public function markAsRead(GuestMessage $message)
    {
        $message->markAsRead();
        return response()->json(['success' => true]);
    }

    public function destroy(GuestMessage $message)
    {
        $message->delete();
        return back()->with('success', 'Message supprimé avec succès');
    }
}
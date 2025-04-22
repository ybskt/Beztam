<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        
        $clients = User::select('id', 'first_name', 'last_name', 'email', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $currentPage);

        return Inertia::render('Admin/Clients', [
            'clients' => $clients->items(),
            'pagination' => [
                'currentPage' => $clients->currentPage(),
                'lastPage' => $clients->lastPage(),
                'perPage' => $perPage,
                'total' => $clients->total(),
                'from' => $clients->firstItem(),
                'to' => $clients->lastItem()
            ]
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Client supprimé avec succès');
    }

    public function sendMessage(Request $request)
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
            'is_read' => 0 // 0 = unread
        ]);

        return redirect()->back()->with('success', 'Message envoyé avec succès');
    }
}
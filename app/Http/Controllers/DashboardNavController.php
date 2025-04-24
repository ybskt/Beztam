<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardNavController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'notification' => $notification->notification,
                    'type' => $notification->type,
                    'created_at' => $notification->created_at->toDateTimeString()
                ];
            });

        return Inertia::render('Dashboard/Nav', [
            'user' => $user,
            'notifications' => $notifications
        ]);
    }
}
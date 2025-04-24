<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Inertia::share([
            'notifications' => function () {
                // Only share notifications for regular web users
                if (Auth::guard('web')->check()) {
                    return Auth::user()->notifications()
                        ->where('created_at', '>=', now()->subDays(30))
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->map(fn ($n) => [
                            'id' => $n->id,
                            'notification' => $n->notification,
                            'type' => $n->type,
                            'created_at' => $n->created_at->toDateTimeString()
                        ]);
                }
                return [];
            }
        ]);
    }
}
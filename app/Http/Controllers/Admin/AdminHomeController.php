<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminHomeController extends Controller
{
    public function index()
    {
        // Get user registration data for the last 30 days
        $userRegistrations = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count
                ];
            });

        // Fill in missing dates with 0 counts
        $dates = [];
        for ($i = 29; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }
        $filledRegistrations = [];
        foreach ($dates as $date) {
            $found = $userRegistrations->firstWhere('date', $date);
            $filledRegistrations[] = [
                'date' => $date,
                'count' => $found ? $found['count'] : 0
            ];
        }

        // Calculate ratings
        $ratingStats = User::select(
            DB::raw('AVG(rating) as average_rating'),
            DB::raw('COUNT(rating) as total_ratings')
        )->whereNotNull('rating')->first();

        // Get page view statistics
        $pageViews = PageView::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $filledPageViews = [];
        foreach ($dates as $date) {
            $found = $pageViews->firstWhere('date', $date);
            $filledPageViews[] = [
                'date' => $date,
                'count' => $found ? $found['count'] : 0
            ];
        }

        // Get most visited pages
        $popularPages = PageView::select(
            'url',
            DB::raw('COUNT(*) as views')
        )
        ->groupBy('url')
        ->orderByDesc('views')
        ->limit(5)
        ->get();

        return Inertia::render('Admin/Index', [
            'stats' => [
                'total_users' => User::count(),
                'monthly_traffic' => PageView::where('created_at', '>=', now()->subDays(30))->count(),
                'average_rating' => $ratingStats->average_rating ?? 0,
                'total_ratings' => $ratingStats->total_ratings ?? 0,
                'user_registrations' => $filledRegistrations,
                'page_views' => $filledPageViews,
                'popular_pages' => $popularPages
            ]
        ]);
    }
}
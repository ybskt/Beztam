<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\Saving;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Calculate main stats
        $totalBudgetFreeAmount = Budget::where('user_id', $user->id)->sum('free_amount');
        $totalSavings = Saving::where('user_id', $user->id)->sum('amount');
        $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');
        $freeMargin = $totalBudgetFreeAmount - $totalExpenses;
        $totalBalance = $freeMargin + $totalSavings;
    
        // Current month data for bar chart
        $now = Carbon::now();
        $monthlyBudgets = Budget::where('user_id', $user->id)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');
        
        $monthlyExpenses = Expense::where('user_id', $user->id)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');
        
        $monthlySavings = Saving::where('user_id', $user->id)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');
    
        return Inertia::render('Dashboard/Dashboard', [
            'totalBalance' => (float)$totalBalance,
            'freeMargin' => (float)$freeMargin,
            'savings' => (float)$totalSavings,
            'budgetDistributionData' => [
                'Marge Libre' => $freeMargin,
                'Épargne' => $totalSavings
            ],
            'monthlySummaryData' => [
                'budgets' => $monthlyBudgets,
                'expenses' => $monthlyExpenses,
                'savings' => $monthlySavings
            ]
        ]);
    }

    public function getDailyData()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $currentDay = $now->day;
        $daysInMonth = $now->daysInMonth;
        
        $dailyData = [];
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($now->year, $now->month, $day);
            
            // Calculate daily values
            $dailyBudget = Budget::where('user_id', $user->id)
                ->whereDate('created_at', '<=', $date)
                ->sum('free_amount');
                
            $dailySavings = Saving::where('user_id', $user->id)
                ->whereDate('created_at', '<=', $date)
                ->sum('amount');
                
            $dailyExpenses = Expense::where('user_id', $user->id)
                ->whereDate('created_at', '<=', $date)
                ->sum('amount');
                
            $dailyFreeMargin = $dailyBudget - $dailyExpenses;
            $dailyBalance = $dailyFreeMargin + $dailySavings;
            
            $dailyData[$day] = [
                'balance' => $dailyBalance,
                'free_margin' => $dailyFreeMargin,
                'savings' => $dailySavings,
                'budgets' => $dailyBudget,
                'expenses' => $dailyExpenses
            ];
        }

        return response()->json([
            'labels' => array_keys($dailyData),
            'data' => array_values($dailyData),
            'currentDay' => $currentDay
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\MonthlyBudget;
use App\Models\Expense;
use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Calculate total balance (free amounts from budgets + savings - expenses)
        $totalBudgetFreeAmount = Budget::where('user_id', $user->id)->sum('free_amount');
        $totalMonthlyBudgetFreeAmount = MonthlyBudget::where('user_id', $user->id)
            ->sum('amount') * MonthlyBudget::where('user_id', $user->id)->sum('occurrences');
        $totalSavings = Saving::where('user_id', $user->id)->sum('amount');
        $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');
        
        $totalBalance = ($totalBudgetFreeAmount + $totalMonthlyBudgetFreeAmount + $totalSavings) - $totalExpenses;
        
        // Calculate free margin (free amounts - expenses)
        $freeMargin = ($totalBudgetFreeAmount + $totalMonthlyBudgetFreeAmount) - $totalExpenses;
        
        // Recent budgets (last 7) with formatted dates
        $recentBudgets = Budget::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get()
            ->map(function ($budget) {
                $budget->date = Carbon::parse($budget->date)->format('d-m-Y');
                return $budget;
            });
            
        // Monthly budgets with day of month handling
        $monthlyBudgets = MonthlyBudget::where('user_id', $user->id)->get();

        return inertia('Dashboard/Budgets', [
            'totalBalance' => $totalBalance,
            'totalSavings' => $totalSavings,
            'savingsRate' => $user->savings_rate,
            'recentBudgets' => $recentBudgets,
            'monthlyBudgets' => $monthlyBudgets,
            'freeMargin' => $freeMargin > 0 ? $freeMargin : 0,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'nullable|date',
            'description' => 'nullable|string',
            'is_monthly' => 'boolean',
            'apply_saving' => 'boolean',
        ]);
        
        $date = $request->date ? Carbon::parse($request->date)->format('Y-m-d') : now()->format('Y-m-d');
        $amount = $request->amount;
        $savingAmount = $request->apply_saving ? ($amount * ($user->savings_rate / 100)) : 0;
        $freeAmount = $amount - $savingAmount;
        
        if ($request->is_monthly) {
            // Handle day of month for monthly budgets (including February 28/29)
            $dayOfMonth = now()->day;
            if ($dayOfMonth > 28) {
                $dayOfMonth = 28; // Handle February case
            }
            
            // Add to monthly budgets
            $monthlyBudget = MonthlyBudget::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'amount' => $amount,
                'day_of_month' => $dayOfMonth,
                'description' => $request->description,
                'occurrences' => 1,
            ]);
            
            // Add to regular budgets as the first occurrence
            Budget::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'amount' => $amount,
                'free_amount' => $freeAmount,
                'date' => $date,
                'description' => $request->description,
            ]);
        } else {
            // Add to regular budgets
            Budget::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'amount' => $amount,
                'free_amount' => $freeAmount,
                'date' => $date,
                'description' => $request->description,
            ]);
        }
        
        // If apply_saving is checked, add to savings
        if ($request->apply_saving && $savingAmount > 0) {
            Saving::create([
                'user_id' => $user->id,
                'name' => $request->name . ' (Épargne)',
                'amount' => $savingAmount,
                'date' => $date,
            ]);
        }
        
        return redirect()->route('budgets')->with('success', 'Budget ajouté avec succès');
    }

    public function destroyMonthly($id)
    {
        $monthlyBudget = MonthlyBudget::findOrFail($id);
        
        if ($monthlyBudget->user_id !== Auth::id()) {
            abort(403);
        }
        
        $monthlyBudget->delete();
        
        return redirect()->route('budgets')->with('success', 'Budget mensuel supprimé');
    }
    
    public function processMonthlyBudgets()
    {
        $user = Auth::user();
        $today = now();
        $monthlyBudgets = MonthlyBudget::where('user_id', $user->id)->get();
        
        foreach ($monthlyBudgets as $monthlyBudget) {
            // Handle February case (28 or 29 days)
            $dayToProcess = $monthlyBudget->day_of_month;
            $lastDayOfMonth = $today->copy()->endOfMonth()->day;
            
            if ($dayToProcess > $lastDayOfMonth) {
                $dayToProcess = $lastDayOfMonth;
            }
            
            if ($today->day == $dayToProcess) {
                $savingAmount = $monthlyBudget->amount * ($user->savings_rate / 100);
                $freeAmount = $monthlyBudget->amount - $savingAmount;
                
                // Add to regular budgets
                Budget::create([
                    'user_id' => $user->id,
                    'name' => $monthlyBudget->name,
                    'amount' => $monthlyBudget->amount,
                    'free_amount' => $freeAmount,
                    'date' => $today->format('Y-m-d'),
                    'description' => $monthlyBudget->description,
                ]);
                
                // Add to savings
                Saving::create([
                    'user_id' => $user->id,
                    'name' => $monthlyBudget->name . ' (Épargne mensuelle)',
                    'amount' => $savingAmount,
                    'date' => $today->format('Y-m-d'),
                ]);
                
                // Increment occurrences
                $monthlyBudget->increment('occurrences');
            }
        }
        
        return redirect()->route('budgets')->with('success', 'Budgets mensuels traités');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\MonthlyExpense;
use App\Models\Category;
use App\Models\Budget;
use App\Models\MonthlyBudget;
use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

         // Calculate total balance (free amounts from budgets + savings - expenses)
         $totalBudgetFreeAmount = Budget::where('user_id', $user->id)->sum('free_amount');
         $totalSavings = Saving::where('user_id', $user->id)->sum('amount');
         $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');
         
         $totalBalance = ($totalBudgetFreeAmount + $totalSavings) - $totalExpenses;
         
         // Calculate free margin (free amounts - expenses)
         $freeMargin = $totalBudgetFreeAmount;

        return inertia('Dashboard/Expenses', [
            'categories' => Category::where('user_id', $user->id)->get(),
            'recentExpenses' => Expense::with('category')
                ->where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->limit(8)
                ->get(),
            'monthlyExpenses' => MonthlyExpense::with('category')
                ->where('user_id', $user->id)
                ->get(),
            'totalBalance' => (float)$totalBalance,
            'freeMargin' => (float)$freeMargin,
            'totalSavings' => (float)$totalSavings
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|exists:categories,id,user_id,'.$user->id,
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'is_monthly' => 'boolean',
            'description' => 'nullable|string'
        ]);

        // Check if amount exceeds free margin
        $totalBudgetFreeAmount = Budget::where('user_id', $user->id)->sum('free_amount');
        $totalMonthlyBudgetFreeAmount = MonthlyBudget::where('user_id', $user->id)
            ->sum('amount') * MonthlyBudget::where('user_id', $user->id)->sum('occurrences');
        $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');
        $freeMargin = ($totalBudgetFreeAmount + $totalMonthlyBudgetFreeAmount) - $totalExpenses;

        if ($request->amount > $freeMargin) {
            return back()->withErrors(['amount' => 'Solde indisponible']);
        }

        $date = $request->date;
        $dayOfMonth = Carbon::parse($date)->day;

        if ($request->is_monthly) {
            $monthlyExpense = MonthlyExpense::create([
                'user_id' => $user->id,
                'category_id' => $request->category,
                'name' => $request->name,
                'amount' => $request->amount,
                'day_of_month' => $dayOfMonth,
                'description' => $request->description,
                'occurrences' => 1
            ]);

            Expense::create([
                'user_id' => $user->id,
                'category_id' => $request->category,
                'name' => $request->name,
                'amount' => $request->amount,
                'date' => $date,
                'description' => $request->description
            ]);
        } else {
            Expense::create([
                'user_id' => $user->id,
                'category_id' => $request->category,
                'name' => $request->name,
                'amount' => $request->amount,
                'date' => $date,
                'description' => $request->description
            ]);
        }

        return redirect()->route('expenses')->with('success', 'Dépense ajoutée avec succès');
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id,user_id,'.Auth::id(),
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $expense->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Dépense mise à jour');
    }

    public function updateMonthly(Request $request, MonthlyExpense $monthlyExpense)
    {
        if ($monthlyExpense->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id,user_id,'.Auth::id(),
            'amount' => 'required|numeric|min:0',
            'day_of_month' => 'required|numeric|min:1|max:31',
            'description' => 'nullable|string'
        ]);

        $monthlyExpense->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'day_of_month' => $request->day_of_month,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Dépense mensuelle mise à jour');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $expense->delete();

        return redirect()->back()->with('success', 'Dépense supprimée');
    }

    public function destroyMonthly(MonthlyExpense $monthlyExpense)
    {
        if ($monthlyExpense->user_id !== Auth::id()) {
            abort(403);
        }

        $monthlyExpense->delete();

        return redirect()->back()->with('success', 'Dépense mensuelle supprimée');
    }
}
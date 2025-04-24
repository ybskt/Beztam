<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\MonthlyExpense;
use App\Models\Category;
use App\Models\Budget;
use App\Models\Saving;
use App\Models\Notification;
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
        $freeMargin = $totalBudgetFreeAmount - $totalExpenses;
        $totalBalance = $freeMargin + $totalSavings;
        
        return inertia('Dashboard/Expenses', [
            'categories' => Category::where('user_id', $user->id)->get()->toArray(), // Ensure it's converted to array
            'recentExpenses' => Expense::with('category')
                ->where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
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
            'date' => 'nullable|date|before_or_equal:today',
            'is_monthly' => 'boolean',
            'description' => 'nullable|string'
        ]);

        // Calculate free margin
        $totalBudgetFreeAmount = Budget::where('user_id', $user->id)->sum('free_amount');
        $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');
        $freeMargin = $totalBudgetFreeAmount - $totalExpenses;

        // Check if amount exceeds free margin
        if ($request->amount > $freeMargin) {
            return back()->withErrors(['amount' => 'Solde indisponible']);
        }

        $date = $request->date ? Carbon::parse($request->date)->format('Y-m-d') : now()->format('Y-m-d');
        $dayOfMonth = Carbon::parse($date)->day;

        // Check category limit
        $category = Category::where('id', $request->category)
            ->where('user_id', $user->id)
            ->withSum(['expenses as current_month_expenses' => function($query) {
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
            }], 'amount')
            ->first();

        if ($category && $category->limit_amount !== null) {
            $projectedTotal = ($category->current_month_expenses ?? 0) + $request->amount;
            if ($projectedTotal > $category->limit_amount) {
                $overAmount = $projectedTotal - $category->limit_amount;
                Notification::create([
                    'user_id' => $user->id,
                    'notification' => "Catégorie {$category->name}: limite mensuelle dépassée de ".number_format($overAmount, 2)." DH",
                    'type' => 'categoryLimitExceeded',
                    'created_at' => now()
                ]);
            }
        }

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

            $expense = Expense::create([
                'user_id' => $user->id,
                'category_id' => $request->category,
                'name' => $request->name,
                'amount' => $request->amount,
                'date' => $date,
                'description' => $request->description
            ]);

           
        } else {
            $expense = Expense::create([
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

    public function getDailyExpensesData()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $currentDay = $now->day;
        $daysInMonth = $now->daysInMonth;
        
        $dailyData = [];
        
        // Initialize all days with null (will be treated as missing data in Chart.js)
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dailyData[$day] = null;
        }
        
        // Fill actual data up to current day
        for ($day = 1; $day <= $currentDay; $day++) {
            $date = Carbon::create($now->year, $now->month, $day);
            
            $dailyData[$day] = Expense::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->sum('amount');
        }
        
        return response()->json([
            'labels' => array_keys($dailyData),
            'data' => array_values($dailyData),
            'currentDay' => $currentDay
        ]);
    }
    public function processMonthlyExpenses()
    {
        $user = Auth::user();
        $today = now();
        $monthlyExpenses = MonthlyExpense::where('user_id', $user->id)->get();

        // Calculate free margin
        $totalBudgetFreeAmount = Budget::where('user_id', $user->id)->sum('free_amount');
        $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');
        $freeMargin = $totalBudgetFreeAmount - $totalExpenses;

        foreach ($monthlyExpenses as $monthlyExpense) {
            // Handle February case (28 or 29 days)
            $dayToProcess = $monthlyExpense->day_of_month;
            $lastDayOfMonth = $today->copy()->endOfMonth()->day;

            if ($dayToProcess > $lastDayOfMonth) {
                $dayToProcess = $lastDayOfMonth;
            }

            if ($today->day == $dayToProcess) {
                // Check if amount exceeds free margin
                if ($monthlyExpense->amount > $freeMargin) {
                    Notification::create([
                        'user_id' => $user->id,
                        'notification' => "Échec de la dépense mensuelle '{$monthlyExpense->name}', solde insuffisant",
                        'type' => 'monthlyExpenseFailed',
                        'created_at' => now()
                    ]);
                    continue;
                }

                // Check category limit
                $category = Category::where('id', $monthlyExpense->category_id)
                    ->where('user_id', $user->id)
                    ->withSum(['expenses as current_month_expenses' => function($query) use ($today) {
                        $query->whereMonth('date', $today->month)
                              ->whereYear('date', $today->year);
                    }], 'amount')
                    ->first();

                if ($category && $category->limit_amount !== null) {
                    $projectedTotal = ($category->current_month_expenses ?? 0) + $monthlyExpense->amount;
                    if ($projectedTotal > $category->limit_amount) {
                        $overAmount = $projectedTotal - $category->limit_amount;
                        Notification::create([
                            'user_id' => $user->id,
                            'notification' => "Catégorie {$category->name}: limite mensuelle dépassée de ".number_format($overAmount, 2)." DH",
                            'type' => 'categoryLimitExceeded',
                            'created_at' => now()
                        ]);
                    }
                }

                // Add the expense
                Expense::create([
                    'user_id' => $user->id,
                    'category_id' => $monthlyExpense->category_id,
                    'name' => $monthlyExpense->name,
                    'amount' => $monthlyExpense->amount,
                    'date' => $today->format('Y-m-d'),
                    'description' => $monthlyExpense->description,
                ]);

                Notification::create([
                    'user_id' => $user->id,
                    'notification' => "Dépense mensuelle '{$monthlyExpense->name}' ajoutée avec succès",
                    'type' => 'monthlyExpenseSuccess',
                    'created_at' => now()
                ]);

                // Increment occurrences
                $monthlyExpense->increment('occurrences');

                // Update free margin for next iteration
                $freeMargin -= $monthlyExpense->amount;
            }
        }

        return redirect()->route('expenses')->with('success', 'Dépenses mensuelles traitées');
    }
}
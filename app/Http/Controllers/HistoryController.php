<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Saving;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'all');
        $perPage = 10;
        $transactions = [];
        $categories = [];
        
        // Always load categories if type is expense or all
        if ($type === 'expense' || $type === 'all') {
            $categories = Category::where('user_id', Auth::id())->get();
        }

        switch ($type) {
            case 'budget':
                $transactions = $this->getBudgetTransactions($perPage);
                break;
            case 'expense':
                $transactions = $this->getExpenseTransactions($perPage);
                break;
            case 'saving':
                $transactions = $this->getSavingTransactions($perPage);
                break;
            default:
                $transactions = $this->getAllTransactions($perPage);
                break;
        }

        return Inertia::render('Dashboard/History', [
            'transactions' => $transactions,
            'initialType' => $type,
            'categories' => $categories,
        ]);
    }

    private function getAllTransactions($perPage)
    {
        $budgets = Budget::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($budget) {
                return $this->formatTransaction($budget);
            });

        $expenses = Expense::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($expense) {
                return $this->formatTransaction($expense);
            });

        $savings = Saving::where('user_id', Auth::id())
            ->where('amount', '>', 0)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($saving) {
                return $this->formatTransaction($saving);
            });

        $allTransactions = $budgets
            ->merge($expenses)
            ->merge($savings)
            ->sortByDesc(function ($transaction) {
                return Carbon::parse($transaction['date'])->timestamp;
            })
            ->values()
            ->all();

        return array_slice($allTransactions, 0, $perPage);
    }

    private function getBudgetTransactions($perPage)
    {
        return Budget::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate($perPage)
            ->through(function ($budget) {
                return $this->formatTransaction($budget);
            })
            ->items();
    }

    private function getExpenseTransactions($perPage)
    {
        return Expense::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('date', 'desc')
            ->paginate($perPage)
            ->through(function ($expense) {
                return $this->formatTransaction($expense);
            })
            ->items();
    }

    private function getSavingTransactions($perPage)
    {
        return Saving::where('user_id', Auth::id())
            ->where('amount', '>', 0)
            ->orderBy('date', 'desc')
            ->paginate($perPage)
            ->through(function ($saving) {
                return $this->formatTransaction($saving);
            })
            ->items();
    }

    private function formatTransaction($transaction)
    {
        $type = strtolower(class_basename($transaction));
        
        $formatted = [
            'id' => $transaction->id,
            'name' => $transaction->name,
            'amount' => (float) $transaction->amount,
            'date' => $transaction->date,
            'description' => $transaction->description,
            'type' => $type,
            'type_label' => ucfirst($type),
            'created_at' => $transaction->created_at->toDateTimeString(),
        ];

        // Add category information for expenses
        if ($type === 'expense' && isset($transaction->category)) {
            $formatted['category'] = $transaction->category->name;
            $formatted['category_id'] = $transaction->category_id;
        }

        return $formatted;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SavingsController extends Controller
{
    public function index()
    {
    $user = Auth::user();
    
    // Calculate totals with proper number casting
    $totalBudgets = (float)$user->budgets()->sum('free_amount');
    $totalSavings = (float)$user->savings()->sum('amount');
    $totalExpenses = (float)$user->expenses()->sum('amount');
    
    $totalBalance = $totalBudgets + $totalSavings - $totalExpenses;
    
    return Inertia::render('Dashboard/Savings', [
        'totalBalance' => (float)$totalBalance,
        'totalSavings' => (float)$totalSavings,
        'savingsRate' => (int)$user->savings_rate, // Ensure integer type
        'recentSavings' => $user->savings()
            ->where('amount', '>=', 0)
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($saving) {
                return [
                    'id' => $saving->id,
                    'name' => $saving->name,
                    'date' => $saving->date ? date('Y-m-d', strtotime($saving->date)) : $saving->created_at->format('Y-m-d'),
                    'amount' => (float)$saving->amount,
                ];
            }),
    ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();

        Saving::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'amount' => $request->amount,
            'date' => $request->date ?? now()->format('Y-m-d'),
        ]);

        return redirect()->back()->with('success', 'Épargne ajoutée avec succès!');
    }

    public function update(Request $request, Saving $saving)
    {
        if ($saving->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $saving->update([
            'name' => $request->name,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Épargne mise à jour avec succès!');
    }

    public function destroy(Saving $saving)
    {
        if ($saving->user_id !== Auth::id()) {
            abort(403);
        }

        $saving->delete();

        return redirect()->back()->with('success', 'Épargne supprimée avec succès!');
    }

    
    public function updateRate(Request $request)
    {
        $request->validate([
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $user = Auth::user();
        $user->savings_rate = $request->percentage;
        $user->save();

        return redirect()->back()->with('success', 'Taux d\'épargne mis à jour!');
    }

    public function transferToMargin(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $totalSavings = $user->savings()->sum('amount');

        if ($request->amount > $totalSavings) {
            return redirect()->back()->with('error', 'Le montant du transfert dépasse l\'épargne totale!');
        }

        // Create budget entry
        Budget::create([
            'user_id' => $user->id,
            'name' => 'Epargne_Envoye',
            'amount' => $request->amount,
            'free_amount' => $request->amount,
            'date' => now()->format('Y-m-d'),
        ]);

        // Create negative saving entry
        Saving::create([
            'user_id' => $user->id,
            'name' => 'Epargne_Envoye',
            'amount' => -$request->amount,
            'date' => now()->format('Y-m-d'),
        ]);

        return redirect()->back()->with('success', 'Montant transféré à la marge libre!');
    }
}
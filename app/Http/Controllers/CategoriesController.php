<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Create default "Autre" category if it doesn't exist
        $this->createDefaultCategory($user);

        // Get all categories with their expenses
        $categories = Category::where('user_id', $user->id)
            ->withSum('expenses as total_expenses', 'amount')
            ->withSum(['expenses as monthly_expenses' => function($query) {
                $query->whereMonth('date', now()->month);
            }], 'amount')
            ->orderByRaw("CASE WHEN name = 'Autre' THEN 1 ELSE 0 END")
            ->orderBy('name')
            ->get();

        return inertia('Dashboard/Categories', [
            'categories' => $categories,
            'consumptionData' => []
        ]);
    }

    protected function createDefaultCategory($user)
    {
        if (!Category::where('user_id', $user->id)->where('name', 'Autre')->exists()) {
            Category::create([
                'user_id' => $user->id,
                'name' => 'Autre',
                'limit_amount' => null
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,user_id,'.Auth::id(),
            'limit' => 'nullable|numeric|min:0'
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'limit_amount' => $request->limit
        ]);

        return redirect()->back()->with('success', 'Catégorie ajoutée avec succès');
    }

    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        // Prevent editing the default category
        if ($category->name === 'Autre') {
            return redirect()->back()->with('error', 'La catégorie par défaut ne peut pas être modifiée');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id.',id,user_id,'.Auth::id(),
            'limit' => 'nullable|numeric|min:0'
        ]);

        $category->update([
            'name' => $request->name,
            'limit_amount' => $request->limit
        ]);

        return redirect()->back()->with('success', 'Catégorie mise à jour');
    }

    public function destroy(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        // Prevent deleting the default category
        if ($category->name === 'Autre') {
            return redirect()->back()->with('error', 'La catégorie par défaut ne peut pas être supprimée');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Catégorie supprimée');
    }
}
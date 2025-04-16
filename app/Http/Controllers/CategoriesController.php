<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


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
    public function getCategoryChartData()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $currentDay = $now->day;
        $daysInMonth = $now->daysInMonth;

        // 1. Monthly Consumption by Category (Bar Chart)
        $monthlyData = Category::withSum([
            'expenses' => function($query) use ($now) {
                $query->whereMonth('date', $now->month)
                    ->whereYear('date', $now->year);
            }
        ], 'amount')
        ->where('user_id', $user->id)
        ->get()
        ->map(function($category) {
            return [
                'name' => $category->name,
                'amount' => (float)$category->expenses_sum_amount
            ];
        });

        // 2. Daily Expenses by Category (Line Chart)
        $dailyData = [];
        $categories = Category::where('user_id', $user->id)->get();

        foreach ($categories as $category) {
            $categoryData = [];
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($now->year, $now->month, $day);
                
                $categoryData[$day] = $day <= $currentDay
                    ? $category->expenses()
                        ->whereDate('date', $date)
                        ->sum('amount')
                    : null;
            }

            $dailyData[] = [
                'name' => $category->name,
                'data' => array_values($categoryData),
                'color' => $this->generateColor($category->id) // Generate unique color
            ];
        }

        return response()->json([
            'monthlyConsumption' => $monthlyData,
            'dailyExpenses' => [
                'labels' => range(1, $daysInMonth),
                'datasets' => $dailyData,
                'currentDay' => $currentDay
            ]
        ]);
    }

    // Helper function to generate consistent colors
    private function generateColor($id)
    {
        $colors = [
            '#7E22CE', '#0D9488', '#2563EB', '#D946EF', 
            '#EA580C', '#16A34A', '#9333EA', '#0891B2'
        ];
        return $colors[$id % count($colors)];
    }
}
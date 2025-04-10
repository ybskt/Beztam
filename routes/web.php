<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Public Routes
Route::get('/', function () {
    return Inertia::render('Public/Home');
})->name('home');

Route::get('/features', function () {
    return Inertia::render('Public/Features');
})->name('features');

Route::get('/about', function () {
    return Inertia::render('Public/About');
})->name('about');

Route::get('/contact', function () {
    return Inertia::render('Public/Contact');
})->name('contact');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Email Verification Routes
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('verification.notice');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Authenticated Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Dashboard');
    })->name('dashboard');

    // Budget Management
    Route::get('/dashboard/budgets', [BudgetController::class, 'index'])->name('budgets');
    Route::post('/budgets', [BudgetController::class, 'store']);
    Route::post('/budgets/process-monthly', [BudgetController::class, 'processMonthlyBudgets']);
    Route::delete('/monthly-budgets/{id}', [BudgetController::class, 'destroyMonthly']);

    // Expense Tracking
    Route::get('/dashboard/expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::post('/dashboard/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/dashboard/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::put('/dashboard/expenses/monthly/{monthlyExpense}', [ExpenseController::class, 'updateMonthly'])->name('expenses.monthly.update');
    Route::delete('/dashboard/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::delete('/dashboard/expenses/monthly/{monthlyExpense}', [ExpenseController::class, 'destroyMonthly'])->name('expenses.monthly.destroy');
    // Savings Goals
    Route::get('/dashboard/savings', [SavingsController::class, 'index'])->name('savings');
    Route::post('/savings', [SavingsController::class, 'store']);
    Route::post('/savings/rate', [SavingsController::class, 'updateRate']);
    Route::post('/savings/transfer', [SavingsController::class, 'transferToMargin']);

    // Categories Management
    Route::get('/dashboard/categories', [CategoriesController::class, 'index'])->name('categories');
    Route::post('/dashboard/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::put('/dashboard/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/dashboard/categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

    // Transaction History
    Route::get('/dashboard/history', function () {
        return Inertia::render('Dashboard/History');
    })->name('history');

    // Settings
    Route::get('/dashboard/settings', function () {
        return Inertia::render('Dashboard/Settings');
    })->name('settings');
});
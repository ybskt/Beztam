<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// Auth Controllers
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LogoutController;
// App Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DashboardNavController;
// Requests
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
   
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/daily-data', [DashboardController::class, 'getDailyData'])->name('dashboard.daily-data');

    // Budget Management
    Route::get('/dashboard/budgets', [BudgetController::class, 'index'])->name('budgets');
    Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::put('/budgets/{budget}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/budgets/{budget}', [BudgetController::class, 'destroy'])->name('budgets.destroy');
    Route::post('/budgets/process-monthly', [BudgetController::class, 'processMonthlyBudgets'])->name('budgets.process-monthly');
    Route::put('/monthly-budgets/{monthlyBudget}', [BudgetController::class, 'updateMonthly'])->name('budgets.monthly.update');
    Route::delete('/monthly-budgets/{id}', [BudgetController::class, 'destroyMonthly'])->name('budgets.monthly.destroy');
    Route::get('/budgets/daily-data', [BudgetController::class, 'getDailyBudgetData'])->name('budgets.daily-data');

    // Expense Tracking
    Route::get('/dashboard/expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::post('/dashboard/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/dashboard/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/dashboard/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::put('/dashboard/expenses/monthly/{monthlyExpense}', [ExpenseController::class, 'updateMonthly'])->name('expenses.monthly.update');
    Route::delete('/dashboard/expenses/monthly/{monthlyExpense}', [ExpenseController::class, 'destroyMonthly'])->name('expenses.monthly.destroy');
    Route::get('/expenses/daily-data', [ExpenseController::class, 'getDailyExpensesData'])->name('expenses.daily-data');    
    
    // Savings Goals
    Route::get('/dashboard/savings', [SavingsController::class, 'index'])->name('savings');
    Route::post('/savings', [SavingsController::class, 'store'])->name('savings.store');
    Route::put('/savings/{saving}', [SavingsController::class, 'update'])->name('savings.update');
    Route::delete('/savings/{saving}', [SavingsController::class, 'destroy'])->name('savings.destroy');
    Route::post('/savings/rate', [SavingsController::class, 'updateRate'])->name('savings.rate');
    Route::post('/savings/transfer', [SavingsController::class, 'transferToMargin'])->name('savings.transfer');
    Route::get('/savings/daily-data', [SavingsController::class, 'getDailySavingsData'])->name('savings.daily-data');

    // Categories Management
    Route::get('/dashboard/categories', [CategoriesController::class, 'index'])->name('categories');
    Route::post('/dashboard/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::put('/dashboard/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/dashboard/categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/chart-data', [CategoriesController::class, 'getCategoryChartData'])->name('categories.chart-data');
    
    // Transaction History
    Route::get('/dashboard/history', [HistoryController::class, 'index'])->name('history');

    // Settings
    Route::get('/dashboard/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::delete('/settings/photo', [SettingsController::class, 'destroyPhoto'])->name('settings.photo.destroy');
    Route::delete('/settings/account', [SettingsController::class, 'destroyAccount'])->name('settings.account.destroy');
    Route::post('/settings/photo', [SettingsController::class, 'uploadPhoto'])->name('settings.photo.upload');

    Route::get('/dashboard/nav', [DashboardNavController::class, 'index'])->name('dashboard.nav');

    Route::post('/logout', \App\Http\Controllers\Auth\LogoutController::class)
    ->middleware('auth')
    ->name('logout');

});
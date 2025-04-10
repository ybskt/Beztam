<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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
    // Registration Routes
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    // Login Routes
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Email Verification Routes (no visual pages needed)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// This route is needed for Laravel's verification system but won't be displayed
Route::get('/email/verify', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('verification.notice');

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    // Add other protected routes here
});

// Authenticated Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Home - no email verification required
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Dashboard');
    })->name('dashboard');

    // Budget Management
    Route::get('/dashboard/budgets', function () {
        return Inertia::render('Dashboard/Budgets');
    })->name('budgets');

    // Expense Tracking
    Route::get('/dashboard/expenses', function () {
        return Inertia::render('Dashboard/Expenses');
    })->name('expenses');

    // Savings Goals
    Route::get('/dashboard/savings', function () {
        return Inertia::render('Dashboard/Savings');
    })->name('savings');

    // Categories Management
    Route::get('/dashboard/categories', function () {
        return Inertia::render('Dashboard/Categories');
    })->name('categories');

    // Transaction History
    Route::get('/dashboard/history', function () {
        return Inertia::render('Dashboard/History');
    })->name('history');

     // Transaction History
     Route::get('/dashboard/settings', function () {
        return Inertia::render('Dashboard/Settings');
    })->name('settings');

    // Logout Route
    Route::post('/logout', function () {
        auth()->logout();
        return redirect()->route('home');
    })->name('logout');
});
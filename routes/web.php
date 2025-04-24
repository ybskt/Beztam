<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Auth Controllers
use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    RegisteredUserController
};

// App Controllers
use App\Http\Controllers\{
    BudgetController,
    CategoriesController,
    DashboardController,
    DashboardNavController,
    ExpenseController,
    HistoryController,
    SavingsController,
    SettingsController,
    ContactController,
    GuestContactController,
    TestNotificationController
};

// Admin Controllers
use App\Http\Controllers\Admin\{
    AuthController,
    ClientController,
    UserMessagesController,
    GuestMessagesController,
    AdminHomeController
};

/*--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| These routes are available to all visitors and are automatically tracked
| by the TrackViews middleware (except admin routes which are excluded)
*/

Route::get('/', function () {
    return Inertia::render('Public/Home');
})->name('home');

Route::get('/features', function () {
    return Inertia::render('Public/Features');
})->name('features');

Route::get('/about', function () {
    return Inertia::render('Public/About');
})->name('about');

Route::get('/contact', function () { return Inertia::render('Public/Contact');})->name('contact');
Route::post('/contact', [GuestContactController::class, 'store'])->name('guest-contact.store');
/*--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| These routes are for guest users only (login/registration)
*/

Route::middleware('guest')->group(function () {
    // User Registration
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // User Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Admin Login
    Route::prefix('admin')->group(function () {
        Route::get('/', [AuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/', [AuthController::class, 'login'])->name('admin.login.attempt');
    });
});

/*--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

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

/*--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
| These routes require authentication and are automatically tracked
*/

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/daily-data', [DashboardController::class, 'getDailyData'])->name('dashboard.daily-data');


    // Budget Management
    Route::controller(BudgetController::class)->group(function () {
        Route::get('/dashboard/budgets', 'index')->name('budgets');
        Route::post('/budgets', 'store')->name('budgets.store');
        Route::put('/budgets/{budget}', 'update')->name('budgets.update');
        Route::delete('/budgets/{budget}', 'destroy')->name('budgets.destroy');
        Route::post('/budgets/process-monthly', 'processMonthlyBudgets')->name('budgets.process-monthly');
        Route::put('/monthly-budgets/{monthlyBudget}', 'updateMonthly')->name('budgets.monthly.update');
        Route::delete('/monthly-budgets/{id}', 'destroyMonthly')->name('budgets.monthly.destroy');
        Route::get('/budgets/daily-data', 'getDailyBudgetData')->name('budgets.daily-data');
    });

    // Expense Tracking
    Route::controller(ExpenseController::class)->group(function () {
        Route::get('/dashboard/expenses', 'index')->name('expenses');
        Route::post('/dashboard/expenses', 'store')->name('expenses.store');
        Route::put('/dashboard/expenses/{expense}', 'update')->name('expenses.update');
        Route::delete('/dashboard/expenses/{expense}', 'destroy')->name('expenses.destroy');
        Route::put('/dashboard/expenses/monthly/{monthlyExpense}', 'updateMonthly')->name('expenses.monthly.update');
        Route::delete('/dashboard/expenses/monthly/{monthlyExpense}', 'destroyMonthly')->name('expenses.monthly.destroy');
        Route::get('/expenses/daily-data', 'getDailyExpensesData')->name('expenses.daily-data');
        Route::post('/expenses/process-monthly', 'processMonthlyExpenses')->name('expenses.process-monthly');
    });

    // Savings Goals
    Route::controller(SavingsController::class)->group(function () {
        Route::get('/dashboard/savings', 'index')->name('savings');
        Route::post('/savings', 'store')->name('savings.store');
        Route::put('/savings/{saving}', 'update')->name('savings.update');
        Route::delete('/savings/{saving}', 'destroy')->name('savings.destroy');
        Route::post('/savings/rate', 'updateRate')->name('savings.rate');
        Route::post('/savings/transfer', 'transferToMargin')->name('savings.transfer');
        Route::get('/savings/daily-data', 'getDailySavingsData')->name('savings.daily-data');
    });

    // Categories Management
    Route::controller(CategoriesController::class)->group(function () {
        Route::get('/dashboard/categories', 'index')->name('categories');
        Route::post('/dashboard/categories', 'store')->name('categories.store');
        Route::put('/dashboard/categories/{category}', 'update')->name('categories.update');
        Route::delete('/dashboard/categories/{category}', 'destroy')->name('categories.destroy');
        Route::get('/categories/chart-data', 'getCategoryChartData')->name('categories.chart-data');
    });

    // Transaction History
    Route::get('/dashboard/history', [HistoryController::class, 'index'])->name('history');

    // Settings
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/dashboard/settings', 'index')->name('settings');
        Route::put('/settings/profile', 'updateProfile')->name('settings.profile.update');
        Route::put('/settings/password', 'updatePassword')->name('settings.password.update');
        Route::delete('/settings/photo', 'destroyPhoto')->name('settings.photo.destroy');
        Route::delete('/settings/account', 'destroyAccount')->name('settings.account.destroy');
        Route::post('/settings/photo', 'uploadPhoto')->name('settings.photo.upload');
        
    });



    Route::controller(ContactController::class)->group(function () {
        Route::get('/dashboard/contact/{tab?}', 'index')->name('dashContact');
        Route::post('/dashboard/messages/support', 'support')->name('messages.support');
        Route::patch('/dashboard/messages/{message}/read', 'read')->name('messages.read');
        Route::post('/dashboard/messages/reply', 'reply')->name('messages.reply');
        Route::delete('/dashboard/messages/{message}', 'destroy')->name('messages.destroy');
    });

    Route::controller(TestNotificationController::class)->group(function () {
        Route::get('/test-notifications', 'index')->name('test.notifications');
        Route::post('/test-notifications', 'store');
        Route::delete('/test-notifications', 'destroy');
    })->middleware('auth');

    Route::get('/dashboard/nav', [DashboardNavController::class, 'index'])->name('dashboard.nav');
   
});

/*--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| These routes are for admin users only and are NOT tracked
*/

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    // Dashboard
    Route::get('/home', [AdminHomeController::class, 'index'])->name('home');

    // Client Management
    Route::controller(ClientController::class)->group(function () {
        Route::get('/clients', 'index')->name('clients');
        Route::delete('/clients/{user}', 'destroy')->name('clients.destroy');
        Route::post('/send-message', 'sendMessage')->name('send-message');
    });

    // User Messages
    Route::controller(UserMessagesController::class)->group(function () {
        Route::get('/reclamations', 'index')->name('reclamations');
        Route::patch('/user-messages/{message}/read', 'markAsRead')->name('user-messages.read');
        Route::post('/user-messages/reply', 'reply')->name('user-messages.reply');
        Route::delete('/user-messages/{message}', 'destroy')->name('user-messages.destroy');
    });

    // Guest Messages
    Route::controller(GuestMessagesController::class)->group(function () {
        Route::get('/messages', 'index')->name('messages');
        Route::patch('/guest-messages/{message}/read', 'markAsRead')->name('guest-messages.read');
        Route::delete('/guest-messages/{message}', 'destroy')->name('guest-messages.destroy');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
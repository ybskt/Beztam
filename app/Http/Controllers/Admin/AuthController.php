<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return inertia('Admin/Login');
    }

    /**
     * Handle an admin login attempt.
     */
    public function login(AdminLoginRequest $request)
    {
        $admin = Admin::first();
        
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors([
                'password' => 'Mot de passe incorrect',
            ]);
        }

        Auth::guard('admin')->login($admin, $request->remember ?? false);

        return redirect()->route('admin.home');
    }

    /**
     * Log the admin out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location('/admin');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardNavController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Nav', [
            'user' => auth()->user()
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Add any data you want to pass to the dashboard view
        return view('dashboard', [
            'user' => Auth::user()
        ]);
    }
} 
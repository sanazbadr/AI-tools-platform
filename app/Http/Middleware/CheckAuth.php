<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('is_logged_in') || !Session::has('user_id')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please log in.',
                    'redirect' => '/login'
                ], 401);
            }
            return redirect('/login');
        }

        // Check if user has valid plan (1, 2, or 3)
        $user = Session::get('user');
        if (!isset($user['plan']) || !in_array($user['plan'], [1, 2, 3])) {
            Session::flush();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your plan does not grant access to this service.',
                    'redirect' => '/login'
                ], 403);
            }
            return redirect('/login')->with('error', 'Your plan does not grant access to this service.');
        }

        return $next($request);
    }
} 
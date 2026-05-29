<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in via session
        if (!Session::has('user_id')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            // Preserve query parameters (e.g., message, create_conversation) and add intended redirect
            $query = $request->getQueryString();
            $intended = $request->fullUrl();
            // Build login redirect with existing query and explicit redirect target
            $params = [];
            if (!empty($query)) {
                parse_str($query, $params);
            }
            $params['redirect'] = $intended;
            $redirectUrl = '/login' . '?' . http_build_query($params);
            return redirect($redirectUrl);
        }

        // Format user data properly - handle both Google and Archeoam users
        $userData = [
            'id' => Session::get('user_id'),
            'name' => Session::get('name', 'User'),
            'email' => Session::get('email'),
            'avatar' => Session::get('avatar'),
            'plan' => Session::get('plan', 1),
            'plan_name' => Session::get('plan_name', 'Basic'),
            'auth_type' => Session::get('auth_type', 'archeoam') // Default to archeoam for backward compatibility
        ];

        // Add formatted user data to view for all routes
        view()->share('user', $userData);
        
        return $next($request);
    }
} 
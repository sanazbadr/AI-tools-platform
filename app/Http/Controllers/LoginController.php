<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    private $apiKey;
    private $apiBaseUrl;

    protected $redirectTo = '/chatbot';

    public function __construct()
    {
        $this->apiKey = env('ARCHEOAM_API_KEY', 'sldfjsdzxcr34l5jlj$jsd#%$@#');
        $this->apiBaseUrl = env('ARCHEOAM_API_URL', 'https://archeoam.com/api/');
    }

    public function showLoginForm(Request $request)
    {
        // Persist intended redirect if provided so it survives navigation/OAuth bounces
        $incomingRedirect = $request->query('redirect');
        if (!empty($incomingRedirect)) {
            Session::put('intended_redirect', $incomingRedirect);
        }

        if (Session::has('user_id')) {
            // If already logged in, prefer explicit redirect target if provided
            $redirectUrl = $request->query('redirect');
            if (!empty($redirectUrl)) {
                return redirect($redirectUrl);
            }
            // Next, honor any session-persisted intended redirect
            $sessionIntended = Session::get('intended_redirect');
            if (!empty($sessionIntended)) {
                Session::forget('intended_redirect');
                return redirect($sessionIntended);
            }
            // Otherwise preserve chatbot prompt params behavior
            $message = $request->query('message');
            $create = $request->query('create_conversation');
            if (!empty($message)) {
                $encoded = urlencode($message);
                $suffix = "?message={$encoded}&create_conversation=" . ($create === 'false' ? 'false' : 'true');
                return redirect('/chatbot' . $suffix);
            }
            return redirect('/chatbot');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiBaseUrl . 'ssdfsdjlkj435lj2312/gpt/auth', [
                'email' => strtolower(trim($request->email)),
                'password' => $request->password
            ]);

            if (!$response->successful()) {
                Log::error('API Error: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication failed. Please try again.'
                ], 401);
            }

            $data = $response->json();

            if (!isset($data['success']) || !$data['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Authentication failed'
                ], 401);
            }

            // Verify user has access to chatbot (plans 1, 2, or 3)
            if (!isset($data['data']['plan']) || !in_array($data['data']['plan'], [1, 2, 3])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your plan does not grant access to this service.'
                ], 403);
            }

            // Create or update local user record
            $userId = $data['data']['user_id'];
            $existingUser = DB::table('users')->where('id', $userId)->first();
            
            if (!$existingUser) {
                // Create new user record
                DB::table('users')->insert([
                    'id' => $userId,
                    'name' => $data['data']['name'] ?? 'User',
                    'email' => strtolower(trim($request->email)),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                // Update existing user
                DB::table('users')->where('id', $userId)->update([
                    'email' => strtolower(trim($request->email)),
                    'updated_at' => now()
                ]);
            }

            // Store user data in session
            Session::put('user_id', $userId);
            Session::put('name', $data['data']['name'] ?? 'User');
            Session::put('email', strtolower(trim($request->email)));
            Session::put('plan', $data['data']['plan']);
            Session::put('plan_name', $this->getPlanName($data['data']['plan']));

            // Determine intended redirect target
            $redirectTarget = $request->input('redirect') ?: $request->query('redirect') ?: Session::get('intended_redirect');
            if (empty($redirectTarget)) {
                // Fallback to chatbot behavior with optional message/create_conversation
                $message = $request->query('message');
                $create = $request->query('create_conversation');
                if (!empty($message)) {
                    $encoded = urlencode($message);
                    $redirectTarget = "/chatbot?message={$encoded}&create_conversation=" . ($create === 'false' ? 'false' : 'true');
                } else {
                    $redirectTarget = '/chatbot';
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => $redirectTarget,
                'data' => [
                    'user_id' => $userId,
                    'name' => $data['data']['name'] ?? 'User',
                    'email' => strtolower(trim($request->email)),
                    'plan' => $data['data']['plan'],
                    'plan_name' => $this->getPlanName($data['data']['plan'])
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login. Please try again.'
            ], 500);
        }
    }

    private function getPlanName($planId)
    {
        $plans = [
            1 => 'Basic',
            2 => 'Pro',
            3 => 'Enterprise'
        ];
        
        return $plans[$planId] ?? 'Unknown';
    }

    public function logout(Request $request)
    {
        try {
            Session::flush();
            Session::invalidate();
            Session::regenerateToken();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully',
                    'redirect' => '/'
                ]);
            }
            
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to logout. Please try again.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to logout. Please try again.');
        }
    }
} 
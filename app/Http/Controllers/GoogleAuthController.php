<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        try {
            // Store the pending message in session if provided
            // Support both GET and POST, and allow propagation from login URL
            $message = $request->input('message');
            if (!empty($message)) {
                Log::info('Google redirect - received message: ' . $message);
                session(['pending_message' => $message]);
                Log::info('Google redirect - stored in session: ' . session('pending_message'));
            } else {
                Log::info('Google redirect - no message provided');
            }
            // Carry intended redirect target through the OAuth flow
            $redirectTarget = $request->input('redirect') ?: $request->query('redirect');
            if (!empty($redirectTarget)) {
                session(['intended_redirect' => $redirectTarget]);
            }
            
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            Log::error('Google redirect error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Unable to connect to Google. Please try again.');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists in database
            $existingUser = DB::table('users')->where('google_id', $googleUser->getId())->first();
            
            if (!$existingUser) {
                // Check if user exists by email
                $existingUser = DB::table('users')->where('email', $googleUser->getEmail())->first();
                
                if ($existingUser) {
                    // Update existing user with Google ID
                    DB::table('users')->where('id', $existingUser->id)->update([
                        'google_id' => $googleUser->getId(),
                        'updated_at' => now()
                    ]);
                } else {
                    // Create new user
                    $userId = DB::table('users')->insertGetId([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(), // Google emails are verified
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    $existingUser = (object) [
                        'id' => $userId,
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar()
                    ];
                }
            }

            // Store user data in session
            Session::put('user_id', $existingUser->id);
            Session::put('name', $existingUser->name);
            Session::put('email', $existingUser->email);
            Session::put('avatar', $existingUser->avatar ?? null);
            Session::put('auth_type', 'google');
            Session::put('plan', 1); // Default plan for Google users
            Session::put('plan_name', 'Basic');

            // If an intended redirect was provided, use it after login
            $intended = session('intended_redirect') ?: request()->query('redirect');
            if (!empty($intended)) {
                session()->forget('intended_redirect');
                return redirect($intended)->with('success', 'Successfully logged in with Google!');
            }

            // Check if there's a pending message from the home page
            $pendingMessage = session('pending_message');
            Log::info('Google callback - checking for pending message: ' . ($pendingMessage ?: 'null'));
            if ($pendingMessage) {
                // Clear the session message
                session()->forget('pending_message');
                // Redirect to chatbot with the message as URL parameter
                $encodedMessage = urlencode($pendingMessage);
                Log::info('Google callback - redirecting to chatbot with message: ' . $encodedMessage);
                return redirect("/chatbot?message={$encodedMessage}&create_conversation=true")->with('success', 'Successfully logged in with Google!');
            }
            
            return redirect('/chatbot')->with('success', 'Successfully logged in with Google!');

        } catch (Exception $e) {
            Log::error('Google callback error: ' . $e->getMessage());
            Log::error('Google callback error trace: ' . $e->getTraceAsString());
            Log::error('Google callback error file: ' . $e->getFile() . ' line: ' . $e->getLine());
            return redirect('/login')->with('error', 'Authentication failed. Please try again.');
        }
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
        } catch (Exception $e) {
            Log::error('Google logout error: ' . $e->getMessage());
            
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
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\DallEController;
use App\Http\Controllers\TTScontroller;
use App\Http\Controllers\STTcontroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArcheoController;

// Domain-based routing
Route::domain('archeo.ai')->group(function () {
    Route::get('/', [ArcheoController::class, 'index'])->name('archeo.home');
    Route::get('/about', [ArcheoController::class, 'about'])->name('archeo.about');
    Route::get('/services', [ArcheoController::class, 'services'])->name('archeo.services');
    Route::get('/contact', [ArcheoController::class, 'contact'])->name('archeo.contact');
});

// Default domain routes (ai.archeoam.com)
Route::get('/', function () {
    return view('welcome');
});

// Development Routes (bypass authentication for testing)
if (app()->environment('local') || app()->environment('development')) {
    Route::get('/dev/dalle', [DallEController::class, 'index'])->name('dev.dalle');
    Route::post('/dev/dalle/generate-image', [DallEController::class, 'generateImage']);
    
    // Test route for debugging
    Route::get('/dev/dalle/test', function() {
        return response()->json([
            'status' => 'DALL-E test endpoint working',
            'environment' => app()->environment(),
            'storage_path' => storage_path(),
            'public_path' => public_path(),
            'images_dir' => public_path('images')
        ]);
    });
}

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Google Authentication Routes
Route::match(['get', 'post'], '/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');
Route::post('/auth/google/logout', [GoogleAuthController::class, 'logout'])->name('google.logout');

// Protected Routes
Route::middleware(['check.session'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::get('/dalle', [DallEController::class, 'index'])->name('dalle');
    Route::get('/tts', [TTScontroller::class, 'index'])->name('tts');
    Route::get('/stt', [STTcontroller::class, 'index'])->name('stt');
    Route::get('/upgrade-plan', function () {
        return view('upgrade-plan');
    })->name('upgrade-plan');

    // API Routes
    Route::prefix('api')->group(function () {
        // Chatbot API Routes
        Route::prefix('chatbot')->group(function () {
            Route::get('/get-conversations', [ChatbotController::class, 'getConversations']);
            Route::get('/get-messages', [ChatbotController::class, 'getMessages']);
            Route::post('/generate-url', [ChatbotController::class, 'generateUrl']);
            Route::post('/message', [ChatbotController::class, 'handleMessage']);
            Route::post('/stream-message', [ChatbotController::class, 'streamMessage']);
            Route::post('/save-message', [ChatbotController::class, 'saveMessage']);
            Route::delete('/delete-conversation', [ChatbotController::class, 'deleteConversation']);
        });

        // DALL-E API Routes
        Route::post('/dalle/generate-image', [DallEController::class, 'generateImage']);

        // TTS API Routes
        Route::prefix('tts')->group(function () {
            Route::post('/convert', [TTScontroller::class, 'convert']);
            Route::get('/voices', [TTScontroller::class, 'getVoices']);
        });

        // STT API Routes
        Route::prefix('stt')->group(function () {
            Route::post('/convert', [STTcontroller::class, 'convert']);
        });
    });
});

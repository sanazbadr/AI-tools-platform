<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ChatbotController,
    LoginController,
    GoogleAuthController,
    DallEController,
    TTScontroller,
    STTcontroller,
    DashboardController,
    ArcheoController
};

/*
|--------------------------------------------------------------------------
| Public Website Routes (Marketing / Landing)
|--------------------------------------------------------------------------
*/

// Main domain landing
Route::get('/', fn () => view('welcome'));

// Domain-based marketing site
Route::domain('archeo.ai')->group(function () {
    Route::get('/', [ArcheoController::class, 'index'])->name('archeo.home');
    Route::get('/about', [ArcheoController::class, 'about'])->name('archeo.about');
    Route::get('/services', [ArcheoController::class, 'services'])->name('archeo.services');
    Route::get('/contact', [ArcheoController::class, 'contact'])->name('archeo.contact');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Social Auth (Google)
|--------------------------------------------------------------------------
*/

Route::prefix('auth/google')->group(function () {
    Route::match(['get', 'post'], '/', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('google.login');

    Route::get('/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('google.callback');

    Route::post('/logout', [GoogleAuthController::class, 'logout'])
        ->middleware('auth')
        ->name('google.logout');
});

/*
|--------------------------------------------------------------------------
| Protected App Routes (Dashboard / SaaS UI)
|--------------------------------------------------------------------------
*/

Route::middleware(['check.session'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::view('/upgrade-plan', 'upgrade-plan')->name('upgrade-plan');

    /*
    |--------------------------------------------------------------------------
    | UI Pages (Blade Views)
    |--------------------------------------------------------------------------
    */
    Route::prefix('app')->group(function () {
        Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
        Route::get('/dalle', [DallEController::class, 'index'])->name('dalle');
        Route::get('/tts', [TTScontroller::class, 'index'])->name('tts');
        Route::get('/stt', [STTcontroller::class, 'sttPage'])->name('stt');
    });

    /*
    |--------------------------------------------------------------------------
    | API Layer (Should ideally move to routes/api.php)
    |--------------------------------------------------------------------------
    */

    Route::prefix('api')->middleware(['throttle:api'])->group(function () {

        /*
        |-------------------------
        | Chatbot APIs
        |-------------------------
        */
        Route::prefix('chatbot')->group(function () {
            Route::get('/conversations', [ChatbotController::class, 'getConversations']);
            Route::get('/messages', [ChatbotController::class, 'getMessages']);
            Route::post('/message', [ChatbotController::class, 'handleMessage']);
            Route::post('/stream', [ChatbotController::class, 'streamMessage']);
            Route::post('/save-message', [ChatbotController::class, 'saveMessage']);
            Route::delete('/conversation', [ChatbotController::class, 'deleteConversation']);
        });

        /*
        |-------------------------
        | DALL·E APIs
        |-------------------------
        */
        Route::post('/dalle/generate', [DallEController::class, 'generateImage']);

        /*
        |-------------------------
        | TTS APIs
        |-------------------------
        */
        Route::prefix('tts')->group(function () {
            Route::post('/convert', [TTScontroller::class, 'convert']);
            Route::get('/voices', [TTScontroller::class, 'getVoices']);
        });

        /*
        |-------------------------
        | STT APIs
        |-------------------------
        */
        Route::prefix('stt')->group(function () {
            Route::post('/convert', [STTcontroller::class, 'convert']);
        });
    });
});

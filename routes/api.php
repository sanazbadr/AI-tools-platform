<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ChatbotController,
    DallEController,
    TTScontroller,
    STTcontroller
};

/*
|--------------------------------------------------------------------------
| API Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {

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

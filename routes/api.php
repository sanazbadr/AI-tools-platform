<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ChatbotController,
    DallEController,
    TTSController,
    STTController
};

/*
|--------------------------------------------------------------------------
| API Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:api'])->name('api.v1.')->group(function () {

    /*
    |-------------------------
    | Chatbot APIs
    |-------------------------
    */
    Route::prefix('chatbot')->name('chatbot.')->group(function () {
        Route::get('/conversations', [ChatbotController::class, 'getConversations'])->name('conversations');
        Route::get('/messages', [ChatbotController::class, 'getMessages'])->name('messages');
        Route::post('/message', [ChatbotController::class, 'handleMessage'])->name('message');
        Route::post('/stream', [ChatbotController::class, 'streamMessage'])->name('stream');
        Route::post('/save-message', [ChatbotController::class, 'saveMessage'])->name('save-message');
        Route::delete('/conversation', [ChatbotController::class, 'deleteConversation'])->name('conversation.delete');
    });

    /*
    |-------------------------
    | DALL·E APIs
    |-------------------------
    */
    Route::post('/dalle/generate', [DallEController::class, 'generateImage'])->name('dalle.generate');

    /*
    |-------------------------
    | TTS APIs
    |-------------------------
    */
    Route::prefix('tts')->name('tts.')->group(function () {
        Route::post('/convert', [TTSController::class, 'convert'])->name('convert');
        Route::get('/voices', [TTSController::class, 'getVoices'])->name('voices');
    });

    /*
    |-------------------------
    | STT APIs
    |-------------------------
    */
    Route::prefix('stt')->name('stt.')->group(function () {
        Route::post('/convert', [STTController::class, 'convert'])->name('convert');
    });
});

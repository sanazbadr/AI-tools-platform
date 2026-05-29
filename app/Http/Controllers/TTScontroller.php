<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class TTSController extends Controller
{
    private $voices = [
        'alloy' => 'Alloy - Neutral and balanced',
        'echo' => 'Echo - Clear and expressive',
        'fable' => 'Fable - British accent, warm',
        'onyx' => 'Onyx - Deep and authoritative',
        'nova' => 'Nova - Energetic and bright',
        'shimmer' => 'Shimmer - Clear and engaging'
    ];

    public function index()
    {
        $voices = $this->voices;
        return view('tts', compact('voices'));
    }

    public function convert(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string|max:4096',
                'voice' => 'required|string|in:' . implode(',', array_keys($this->voices))
            ]);

            $apiKey = env('OPENAI_API_KEY');
            
            if (empty($apiKey)) {
                Log::error('OpenAI API key is not set');
                return response()->json([
                    'success' => false,
                    'error' => 'API key configuration error'
                ], 500);
            }

            $text = $request->input('text');
            $voice = $request->input('voice');

            // Log request details
            Log::info('TTS Request:', [
                'text_length' => strlen($text),
                'voice' => $voice
            ]);

            // Generate unique filename
            $fileName = 'tts_' . Str::random(10) . '.mp3';
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('storage/audio'))) {
                mkdir(public_path('storage/audio'), 0775, true);
            }
            
            // Set the file path
            $filePath = public_path('storage/audio/' . $fileName);

            // Make request to OpenAI
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->post('https://api.openai.com/v1/audio/speech', [
                'input' => $text,
                'voice' => $voice,
                'model' => 'tts-1'
            ]);

            // Log API response status
            Log::info('OpenAI API Response:', [
                'status' => $response->status(),
                'success' => $response->successful()
            ]);

            if ($response->successful()) {
                // Save the audio content directly to the public directory
                file_put_contents($filePath, $response->body());

                // Verify file exists and is readable
                if (!file_exists($filePath)) {
                    Log::error('Failed to save audio file');
                    return response()->json([
                        'success' => false,
                        'error' => 'Failed to save audio file'
                    ], 500);
                }

                // Generate the URL using the asset helper
                $audioUrl = asset('storage/audio/' . $fileName);

                // Log the file details
                Log::info('Audio file saved:', [
                    'path' => $filePath,
                    'url' => $audioUrl,
                    'exists' => file_exists($filePath),
                    'permissions' => substr(sprintf('%o', fileperms($filePath)), -4)
                ]);

                return response()->json([
                    'success' => true,
                    'audio_url' => $audioUrl,
                    'message' => 'Audio generated successfully'
                ]);
            } else {
                Log::error('OpenAI TTS Error:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'response' => $response->json()
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to generate audio: ' . ($response->json()['error']['message'] ?? 'Unknown error')
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('TTS Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate audio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getVoices()
    {
        return response()->json([
            'success' => true,
            'voices' => $this->voices
        ]);
    }
}
?>

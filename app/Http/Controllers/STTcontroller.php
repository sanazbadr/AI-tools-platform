<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class STTController extends Controller
{
    public function index()
    {
        return view('stt');
    }

    public function convert(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'audio' => 'required|file|mimes:wav,mp3,m4a,ogg,flac|max:25000'
            ]);

            // Check API key
            $apiKey = env('OPENAI_API_KEY');
            if (empty($apiKey)) {
                Log::error('OpenAI API key is not set');
                return response()->json([
                    'success' => false,
                    'error' => 'API configuration error'
                ], 500);
            }

            // Get and store the file
            $audioFile = $request->file('audio');
            $fileName = 'stt_' . Str::random(10) . '.' . $audioFile->getClientOriginalExtension();
            
            // Create temp directory if it doesn't exist
            if (!Storage::exists('temp')) {
                Storage::makeDirectory('temp');
            }

            // Store the file
            $filePath = $audioFile->storeAs('temp', $fileName);
            $fullPath = Storage::path($filePath);

            Log::info('Processing audio file:', [
                'original_name' => $audioFile->getClientOriginalName(),
                'stored_path' => $fullPath,
                'size' => $audioFile->getSize(),
                'mime_type' => $audioFile->getMimeType()
            ]);

            // Make sure file exists and is readable
            if (!file_exists($fullPath)) {
                throw new \Exception('Failed to store audio file');
            }

            // Prepare and send request to OpenAI
            $file = fopen($fullPath, 'r');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->attach(
                'file', $file, 'audio.mp3'
            )->post('https://api.openai.com/v1/audio/transcriptions', [
                'model' => 'whisper-1',
            ]);

            // Clean up the temporary file
            Storage::delete($filePath);
            fclose($file);

            // Handle the response
            if ($response->successful()) {
                $result = $response->json();
                Log::info('Successfully converted audio to text', [
                    'text_length' => strlen($result['text'])
                ]);

                return response()->json([
                    'success' => true,
                    'text' => $result['text']
                ]);
            } else {
                Log::error('OpenAI API Error:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers()
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to convert audio: ' . ($response->json()['error']['message'] ?? 'Unknown error')
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('STT Error:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to process audio: ' . $e->getMessage()
            ], 500);
        }
    }
}
?>

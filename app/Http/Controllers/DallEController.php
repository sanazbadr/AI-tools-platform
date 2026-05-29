<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Conversation;
use App\Models\Message;
use Exception;

class DallEController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    private function isDevelopmentRequest()
    {
        return app()->environment('local') || app()->environment('development');
    }

    private function translateToEnglish($text)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl' => 'fa', // Source language: Persian
                'tl' => 'en', // Target language: English
                'dt' => 't',
                'q' => $text
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $translatedText = $result[0][0][0] ?? $text;
                Log::info("Translation result: " . $translatedText);
                return $translatedText;
            }
        } catch (\Exception $e) {
            Log::error('Translation error: ' . $e->getMessage());
        }
        return $text;
    }

    private function enhancePersianPrompt($prompt)
    {
        // Common Persian objects and their English equivalents for better context
        $contextMap = [
            'انگشتر' => 'jewelry ring',
            'یاقوت' => 'ruby gemstone',
            'گردنبند' => 'necklace',
            'دستبند' => 'bracelet',
            'گوشواره' => 'earring',
            'طلا' => 'gold',
            'نقره' => 'silver',
            'الماس' => 'diamond',
            'زمرد' => 'emerald',
            'فیروزه' => 'turquoise',
            'عقیق' => 'agate',
            'مروارید' => 'pearl',
            'جواهر' => 'jewel',
            'سنگ' => 'stone',
            'گل' => 'flower',
            'درخت' => 'tree',
            'آسمان' => 'sky',
            'دریا' => 'sea',
            'کوه' => 'mountain',
            'خورشید' => 'sun',
            'ماه' => 'moon',
            'ستاره' => 'star'
        ];

        // Add descriptive context based on Persian words
        foreach ($contextMap as $persian => $english) {
            if (stripos($prompt, $persian) !== false) {
                $prompt .= ", $english";
            }
        }

        // Add quality and style descriptors for better results
        $prompt = "professional high quality detailed photo of " . $prompt . 
                 ", 4K, high resolution, realistic, detailed";

        return $prompt;
    }

    public function generateImage(Request $request)
    {
        try {
            $request->validate([
                'prompt' => 'required|string'
            ]);

            // Check authentication for production routes
            if (!$this->isDevelopmentRequest()) {
                if (!Session::has('user_id')) {
                    return response()->json([
                        'success' => false,
                        'error' => 'User not authenticated'
                    ], 401);
                }
            }

            if (empty($this->apiKey)) {
                throw new Exception('OpenAI API key is not configured');
            }

            $originalPrompt = $request->prompt;
            $processedPrompt = $originalPrompt;

            // Check if the prompt contains Persian text
            if (preg_match('/\p{Arabic}/u', $originalPrompt)) {
                // First enhance the Persian prompt with context
                $processedPrompt = $this->enhancePersianPrompt($originalPrompt);
                // Then translate to English
                $processedPrompt = $this->translateToEnglish($processedPrompt);
                Log::info("Enhanced and translated prompt: " . $processedPrompt);
            }

            Log::info("Sending request to DALL-E API with prompt: " . $processedPrompt);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/images/generations', [
                'prompt' => $processedPrompt,
                'n' => 1,
                'size' => '1024x1024',
                'response_format' => 'url'
            ]);

            if (!$response->successful()) {
                Log::error('DALL-E API Error: ' . $response->body());
                throw new Exception('Failed to generate image: ' . $response->body());
            }

            $responseData = $response->json();
            Log::info("DALL-E API Response: " . json_encode($responseData));

            $imageUrl = $responseData['data'][0]['url'] ?? null;
            
            if (!$imageUrl) {
                throw new Exception('No image URL in response');
            }

            Log::info("Downloading image from: " . $imageUrl);

            $imageName = $this->saveImageLocally($imageUrl);
            
            if (!$imageName) {
                throw new Exception('Failed to save image locally');
            }

            $finalImageUrl = '/images/' . $imageName;
            Log::info("Image saved successfully. Final URL: " . $finalImageUrl);

            return response()->json([
                'success' => true,
                'image' => $finalImageUrl,
                'original_prompt' => $originalPrompt,
                'processed_prompt' => $processedPrompt
            ]);

        } catch (Exception $e) {
            Log::error('Image generation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function saveImageLocally($imageUrl)
    {
        try {
            // Get the image content
            $imageContent = Http::get($imageUrl)->body();
            
            if (!$imageContent) {
                throw new Exception('Failed to download image');
            }

            // Generate a unique filename
            $imageName = 'dalle_' . time() . '_' . uniqid() . '.jpg';
            
            // Save to public/images directory for direct access
            $publicPath = public_path('images');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            // Save the file directly to public/images
            $filePath = $publicPath . '/' . $imageName;
            if (!file_put_contents($filePath, $imageContent)) {
                throw new Exception('Failed to save image to public directory');
            }

            // Return the direct URL to the public image
            return $imageName;

        } catch (Exception $e) {
            Log::error('Error saving image locally: ' . $e->getMessage());
            return null;
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store('images', 'public');

        // Save the path to the database if needed
        // Image::create(['path' => $path]);

        $url = Storage::url('images/your-image.jpg');

        return response()->json(['path' => $path], 200);
    }

    function encryptImage($imagePath, $key)
    {
        $data = file_get_contents($imagePath);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    function saveEncryptedImage($encryptedData, $outputPath)
    {
        file_put_contents($outputPath, $encryptedData);
    }

    function decryptImage($encryptedData, $key)
    {
        $data = base64_decode($encryptedData);
        $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = substr($data, openssl_cipher_iv_length('aes-256-cbc'));
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }

    public function show($id)
    {
        // Authenticate and authorize user
        // Retrieve encrypted image data from storage or database
        $encryptedData = Storage::get("images/{$id}.enc");
        $key = 'your-encryption-key';

        $decryptedImage = decryptImage($encryptedData, $key);

        return response($decryptedImage)->header('Content-Type', 'image/jpeg');
    }

    public function showChatbot()
    {
        // Logic to determine the image name
        $imageName = 'your-image.jpg'; // Replace with actual logic to get the image name

        // Log the image name for debugging
        Log::info('Image name being passed to view: ' . $imageName);

        // Pass the imageName to the view
        return view('chatbot', ['imageName' => $imageName]);
    }

    public function message(Request $request)
    {
        $isPersian = $request->input('is_persian', false);
        
        // If the message is in Persian, provide Persian responses
        if ($isPersian) {
            if ($request->input('message') === 'سلام') {
                return response()->json([
                    'success' => true,
                    'response' => 'سلام! خوش آمدید. چطور می‌توانم به شما کمک کنم؟'
                ]);
            }
            
            // Add more Persian responses as needed
            return response()->json([
                'success' => true,
                'response' => 'ممنون از پیام شما. چطور می‌توانم کمکتان کنم؟'
            ]);
        }
        
        // Original English response logic
        // ... rest of your code
    }

    public function index()
    {
        return view('dalle');
    }
} 
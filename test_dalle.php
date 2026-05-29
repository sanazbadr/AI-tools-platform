<?php
/**
 * DALL-E Test Script
 * Run this to test image generation functionality
 */

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\DallEController;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing DALL-E Image Generation...\n";

// Create a mock request
$request = new Request();
$request->merge(['prompt' => 'a beautiful sunset over mountains']);

// Test the controller
$controller = new DallEController();

try {
    echo "Generating image with prompt: 'a beautiful sunset over mountains'\n";
    
    $response = $controller->generateImage($request);
    $data = $response->getData(true);
    
    if ($data['success']) {
        echo "✅ Success! Image generated successfully.\n";
        echo "Image URL: " . $data['image'] . "\n";
        echo "Original prompt: " . $data['original_prompt'] . "\n";
        echo "Processed prompt: " . $data['processed_prompt'] . "\n";
        
        // Check if the image file exists
        $imagePath = public_path($data['image']);
        if (file_exists($imagePath)) {
            echo "✅ Image file exists at: " . $imagePath . "\n";
            echo "File size: " . filesize($imagePath) . " bytes\n";
        } else {
            echo "❌ Image file not found at: " . $imagePath . "\n";
        }
    } else {
        echo "❌ Failed to generate image: " . $data['error'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n"; 
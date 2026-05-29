<?php
/**
 * Storage Fix Script
 * Run this to fix storage and permissions issues
 */

echo "Fixing storage and permissions...\n";

// Create public/images directory if it doesn't exist
$imagesDir = __DIR__ . '/public/images';
if (!file_exists($imagesDir)) {
    echo "Creating public/images directory...\n";
    mkdir($imagesDir, 0755, true);
    echo "✅ Directory created: $imagesDir\n";
} else {
    echo "✅ Directory already exists: $imagesDir\n";
}

// Check permissions
$perms = substr(sprintf('%o', fileperms($imagesDir)), -4);
echo "Directory permissions: $perms\n";

// Make sure it's writable
if (is_writable($imagesDir)) {
    echo "✅ Directory is writable\n";
} else {
    echo "❌ Directory is not writable. Attempting to fix...\n";
    chmod($imagesDir, 0755);
    if (is_writable($imagesDir)) {
        echo "✅ Permissions fixed\n";
    } else {
        echo "❌ Could not fix permissions\n";
    }
}

// Create a test file to verify write access
$testFile = $imagesDir . '/test.txt';
if (file_put_contents($testFile, 'test')) {
    echo "✅ Write test successful\n";
    unlink($testFile);
    echo "✅ Cleanup successful\n";
} else {
    echo "❌ Write test failed\n";
}

// Check storage link
$storageLink = __DIR__ . '/public/storage';
if (is_link($storageLink)) {
    echo "✅ Storage link exists\n";
} else {
    echo "❌ Storage link missing. Creating...\n";
    if (symlink(__DIR__ . '/storage/app/public', $storageLink)) {
        echo "✅ Storage link created\n";
    } else {
        echo "❌ Failed to create storage link\n";
    }
}

echo "\nStorage fix completed.\n"; 
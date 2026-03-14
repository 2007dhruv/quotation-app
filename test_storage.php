<?php
// Test if storage route is working
// Save as: test_storage.php in root
// Access: https://alfamachine.designclub.asia/test_storage.php

echo "<h2>🔍 Storage Route Test</h2>";

// Test 1: Check if storage folder exists
$storage_path = __DIR__ . '/storage/app/public/products';
echo "<h3>1. Storage Folder</h3>";
if (file_exists($storage_path)) {
    echo "✅ Folder exists: $storage_path<br>";
    $files = array_diff(scandir($storage_path), ['.', '..']);
    echo "Files: " . count($files) . "<br>";
    if (count($files) > 0) {
        $first_file = reset($files);
        echo "First file: $first_file<br>";
    }
} else {
    echo "❌ Folder not found<br>";
}

// Test 2: Try accessing via route
echo "<h3>2. Test Image URL</h3>";
$test_url = "https://alfamachine.designclub.asia/storage/products/FAHXFS1sbuESxRkyJko0pQV7n09TlBAiBEh0eznS.png";
echo "Try accessing:<br>";
echo "<a href='$test_url' target='_blank'>$test_url</a><br>";
echo "<img src='$test_url' alt='test' style='max-width: 200px; border: 1px solid r  ed;' onerror='console.log(\"Image failed to load\")'><br>";

// Test 3: Check routes
echo "<h3>3. Current Routes</h3>";
echo "Storage route should be at: /storage/{path}<br>";
echo "Test: <a href='/storage/products/FAHXFS1sbuESxRkyJko0pQV7n09TlBAiBEh0eznS.png' target='_blank'>Test /storage/products/filename.png</a><br>";

echo "<hr>";
echo "<p><strong>Delete this file after testing:</strong> delete test_storage.php</p>";
?>

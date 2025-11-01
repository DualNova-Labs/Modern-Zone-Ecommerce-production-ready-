<?php
/**
 * Test Image Processing
 * Check if GD extension and image functions are working
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Image Processing Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        .success { background: #d5f4e6; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🖼️ Image Processing Test</h1>";

// Check GD Extension
echo "<h2>GD Extension Check</h2>";
if (extension_loaded('gd')) {
    echo "<div class='success'>✅ GD Extension is loaded</div>";
    
    $gdInfo = gd_info();
    echo "<div class='info'>";
    echo "<strong>GD Version:</strong> " . $gdInfo['GD Version'] . "<br>";
    echo "<strong>JPEG Support:</strong> " . ($gdInfo['JPEG Support'] ? 'Yes' : 'No') . "<br>";
    echo "<strong>PNG Support:</strong> " . ($gdInfo['PNG Support'] ? 'Yes' : 'No') . "<br>";
    echo "<strong>GIF Read Support:</strong> " . ($gdInfo['GIF Read Support'] ? 'Yes' : 'No') . "<br>";
    echo "<strong>GIF Create Support:</strong> " . ($gdInfo['GIF Create Support'] ? 'Yes' : 'No') . "<br>";
    echo "<strong>WebP Support:</strong> " . (function_exists('imagecreatefromwebp') ? 'Yes' : 'No') . "<br>";
    echo "</div>";
} else {
    echo "<div class='error'>❌ GD Extension is NOT loaded</div>";
    echo "<div class='info'>To enable GD extension in XAMPP:
        <ol>
            <li>Open php.ini file (usually in C:\\xampp\\php\\php.ini)</li>
            <li>Find the line: ;extension=gd</li>
            <li>Remove the semicolon: extension=gd</li>
            <li>Restart Apache</li>
        </ol>
    </div>";
}

// Check Image Functions
echo "<h2>Image Function Check</h2>";
$functions = [
    'imagecreatetruecolor',
    'imagecreatefromjpeg',
    'imagecreatefrompng',
    'imagecreatefromgif',
    'imagecopyresampled',
    'imagejpeg',
    'getimagesize'
];

foreach ($functions as $func) {
    if (function_exists($func)) {
        echo "<div class='success'>✅ {$func}() is available</div>";
    } else {
        echo "<div class='error'>❌ {$func}() is NOT available</div>";
    }
}

// Check Upload Directory
echo "<h2>Upload Directory Check</h2>";
$uploadDir = __DIR__ . '/public/assets/images/banners/';

if (!file_exists($uploadDir)) {
    echo "<div class='info'>Creating banner directory...</div>";
    if (mkdir($uploadDir, 0755, true)) {
        echo "<div class='success'>✅ Created directory: {$uploadDir}</div>";
    } else {
        echo "<div class='error'>❌ Failed to create directory</div>";
    }
} else {
    echo "<div class='success'>✅ Banner directory exists: {$uploadDir}</div>";
}

if (is_writable($uploadDir)) {
    echo "<div class='success'>✅ Directory is writable</div>";
} else {
    echo "<div class='error'>❌ Directory is not writable</div>";
}

// Test Image Creation
echo "<h2>Test Image Creation</h2>";
if (extension_loaded('gd')) {
    $testImage = @imagecreatetruecolor(100, 100);
    if ($testImage) {
        echo "<div class='success'>✅ Can create test image</div>";
        
        // Fill with blue color
        $blue = imagecolorallocate($testImage, 0, 100, 200);
        imagefill($testImage, 0, 0, $blue);
        
        // Try to save as JPEG
        $testPath = $uploadDir . 'test_image.jpg';
        if (@imagejpeg($testImage, $testPath, 90)) {
            echo "<div class='success'>✅ Can save JPEG image</div>";
            echo "<div class='info'>Test image saved: {$testPath}</div>";
            
            // Check file size
            if (file_exists($testPath)) {
                $fileSize = filesize($testPath);
                echo "<div class='info'>File size: {$fileSize} bytes</div>";
                
                // Clean up test file
                unlink($testPath);
                echo "<div class='info'>Test file cleaned up</div>";
            }
        } else {
            echo "<div class='error'>❌ Cannot save JPEG image</div>";
        }
        
        imagedestroy($testImage);
    } else {
        echo "<div class='error'>❌ Cannot create test image</div>";
    }
}

// PHP Configuration
echo "<h2>PHP Configuration</h2>";
echo "<div class='info'>";
echo "<strong>PHP Version:</strong> " . PHP_VERSION . "<br>";
echo "<strong>Upload Max Filesize:</strong> " . ini_get('upload_max_filesize') . "<br>";
echo "<strong>Post Max Size:</strong> " . ini_get('post_max_size') . "<br>";
echo "<strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . " seconds<br>";
echo "<strong>Memory Limit:</strong> " . ini_get('memory_limit') . "<br>";
echo "</div>";

echo "<h2>🔗 Quick Actions</h2>";
echo "<p>
    <a href='/host/mod/admin/banners' target='_blank'>Go to Banner Management</a><br>
    <a href='/host/mod/' target='_blank'>View Homepage</a>
</p>";

echo "</body></html>";
?>

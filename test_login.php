<?php
/**
 * Test Login Script
 * Tests if the admin credentials work
 */

// Define paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');

// Load environment configuration
require_once APP_PATH . '/core/Environment.php';
Environment::load(ROOT_PATH);

// Load required classes
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/models/User.php';

echo "=================================\n";
echo "Testing Admin Login\n";
echo "=================================\n\n";

$email = 'admin@modernzonetrading.com';
$password = 'Admin@123';

echo "Testing credentials:\n";
echo "Email: {$email}\n";
echo "Password: {$password}\n\n";

try {
    // Find user
    echo "[1/3] Finding user... ";
    $user = User::findBy('email', $email);
    
    if (!$user) {
        echo "✗ User not found!\n";
        echo "      Run: c:\\xampp\\php\\php.exe setup_admin.php\n";
        exit(1);
    }
    echo "✓ Found\n";
    echo "      ID: {$user->id}\n";
    echo "      Name: {$user->name}\n";
    echo "      Role: {$user->role}\n";
    echo "      Status: {$user->status}\n\n";
    
    // Test password
    echo "[2/3] Verifying password... ";
    if ($user->verifyPassword($password)) {
        echo "✓ Password correct!\n\n";
    } else {
        echo "✗ Password incorrect!\n";
        echo "      The password hash in database doesn't match '{$password}'\n";
        echo "      Run: c:\\xampp\\php\\php.exe setup_admin.php\n";
        exit(1);
    }
    
    // Test authentication
    echo "[3/3] Testing full authentication... ";
    $result = User::authenticate($email, $password);
    
    if ($result['success']) {
        echo "✓ Success!\n\n";
        echo "=================================\n";
        echo "✅ Login should work!\n";
        echo "=================================\n\n";
        echo "If you still get 'Invalid email or password' error:\n";
        echo "1. Clear browser cookies and cache\n";
        echo "2. Try in incognito/private mode\n";
        echo "3. Make sure you're typing the password exactly: Admin@123\n";
    } else {
        echo "✗ Failed\n";
        echo "      Error: {$result['error']}\n";
        
        if ($user->status !== 'active') {
            echo "\n⚠ User status is '{$user->status}' (should be 'active')\n";
            echo "   Run: c:\\xampp\\php\\php.exe setup_admin.php\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

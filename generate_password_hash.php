<?php
/**
 * Password Hash Generator
 * Run this script to generate a password hash for the admin user
 */

// Password to hash
$password = 'Admin@123';

// Generate hash using BCrypt with cost factor 10 (same as User model)
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

echo "Password: {$password}\n";
echo "Hash: {$hash}\n\n";

// Verify the hash works
if (password_verify($password, $hash)) {
    echo "✓ Hash verification successful!\n";
} else {
    echo "✗ Hash verification failed!\n";
}

echo "\nUse this SQL to update the admin user:\n";
echo "UPDATE users SET password = '{$hash}' WHERE email = 'admin@modernzonetrading.com';\n";

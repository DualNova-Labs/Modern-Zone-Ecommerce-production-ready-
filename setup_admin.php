<?php
/**
 * Admin User Setup Script
 * This script creates/updates the admin user with the correct password
 */

// Define paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');

// Load environment configuration
require_once APP_PATH . '/core/Environment.php';
Environment::load(ROOT_PATH);

// Load database class
require_once APP_PATH . '/core/Database.php';

try {
    $db = Database::getInstance();
    
    // Admin credentials
    $email = 'admin@modernzonetrading.com';
    $password = 'Admin@123';
    $name = 'Admin';
    $role = 'admin';
    
    // Generate password hash
    $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    
    // Check if admin user exists
    $existingUser = $db->selectOne(
        "SELECT * FROM users WHERE email = :email",
        ['email' => $email]
    );
    
    if ($existingUser) {
        // Update existing admin user
        $db->update(
            'users',
            [
                'password' => $passwordHash,
                'role' => $role,
                'status' => 'active',
                'email_verified_at' => date('Y-m-d H:i:s')
            ],
            'email = :email',
            ['email' => $email]
        );
        echo "✓ Admin user updated successfully!\n";
    } else {
        // Create new admin user
        $db->insert('users', [
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role,
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);
        echo "✓ Admin user created successfully!\n";
    }
    
    echo "\n";
    echo "=================================\n";
    echo "Admin Login Credentials:\n";
    echo "=================================\n";
    echo "Email: {$email}\n";
    echo "Password: {$password}\n";
    echo "=================================\n";
    echo "\n";
    echo "You can now login at: http://localhost/host/Modern-Zone-Ecommerce-production-ready-/login\n";
    echo "After login, admins will be redirected to: /admin\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nPlease ensure:\n";
    echo "1. MySQL/MariaDB is running\n";
    echo "2. Database 'modernzone_db' exists\n";
    echo "3. Database credentials in .env are correct\n";
    echo "\nTo create the database, run:\n";
    echo "mysql -u root -p -e \"CREATE DATABASE modernzone_db;\"\n";
    echo "mysql -u root -p modernzone_db < database/schema.sql\n";
}

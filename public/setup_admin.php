<?php
/**
 * Admin User Setup Script
 * 
 * This script creates or updates the admin user with the correct password
 * Run this from the browser: http://localhost/host/Modern-Zone-Ecommerce-production-ready-/public/setup_admin.php
 */

// Include configuration
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

// Define BASE_URL if not already defined (for direct script access)
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $script_name = str_replace('/public/setup_admin.php', '', $_SERVER['SCRIPT_NAME']);
    define('BASE_URL', $protocol . $host . '/host/Modern-Zone-Ecommerce-production-ready-/public');
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Admin Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            color: #333;
        }
        p {
            line-height: 1.6;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ddd;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .warning {
            color: orange;
        }
    </style>
</head>
<body>";

echo "<h1>Admin User Setup</h1>";

try {
    // Get database instance
    $db = Database::getInstance();
    
    // Admin credentials
    $admin_email = 'admin@modernzonetrading.com';
    $admin_password = 'Admin@123';
    $admin_name = 'Admin';
    
    // Hash the password
    $hashed_password = password_hash($admin_password, PASSWORD_BCRYPT, ['cost' => 10]);
    
    echo "<p><strong>Attempting to create/update admin user...</strong></p>";
    
    // Check if admin user exists
    $existing_user = $db->selectOne(
        "SELECT * FROM users WHERE email = :email",
        ['email' => $admin_email]
    );
    
    if ($existing_user) {
        // Update existing admin user
        $db->query(
            "UPDATE users SET 
                password = :password, 
                role = 'admin', 
                status = 'active',
                email_verified_at = NOW()
            WHERE email = :email",
            [
                'email' => $admin_email,
                'password' => $hashed_password
            ]
        );
        echo "<p class='success'>✓ Admin user updated successfully!</p>";
    } else {
        // Create new admin user
        $db->insert('users', [
            'name' => $admin_name,
            'email' => $admin_email,
            'password' => $hashed_password,
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);
        echo "<p class='success'>✓ Admin user created successfully!</p>";
    }
    
    echo "<hr>";
    echo "<h2>Admin Login Credentials:</h2>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($admin_email) . "</p>";
    echo "<p><strong>Password:</strong> " . htmlspecialchars($admin_password) . "</p>";
    echo "<hr>";
    
    // Verify the password works
    $verify_user = $db->selectOne(
        "SELECT * FROM users WHERE email = :email",
        ['email' => $admin_email]
    );
    
    if ($verify_user && password_verify($admin_password, $verify_user['password'])) {
        echo "<p class='success'>✓ Password verification successful!</p>";
        echo "<p><strong>User Details:</strong></p>";
        echo "<ul>";
        echo "<li>ID: " . $verify_user['id'] . "</li>";
        echo "<li>Name: " . htmlspecialchars($verify_user['name']) . "</li>";
        echo "<li>Email: " . htmlspecialchars($verify_user['email']) . "</li>";
        echo "<li>Role: " . htmlspecialchars($verify_user['role']) . "</li>";
        echo "<li>Status: " . htmlspecialchars($verify_user['status']) . "</li>";
        echo "</ul>";
    } else {
        echo "<p class='error'>✗ Password verification failed!</p>";
    }
    
    echo "<hr>";
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Go to login page: <a href='" . BASE_URL . "/login'>" . BASE_URL . "/login</a></li>";
    echo "<li>Login with the credentials above</li>";
    echo "<li>You will be redirected to admin dashboard: <a href='" . BASE_URL . "/admin'>" . BASE_URL . "/admin</a></li>";
    echo "</ol>";
    
    echo "<p class='warning'><strong>Security Note:</strong> Delete this file after use!</p>";
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
?>

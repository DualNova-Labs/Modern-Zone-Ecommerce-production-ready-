<?php
/**
 * Quick Admin User Fix Script
 * Creates/updates admin user with correct password
 */

// Define constants before including config
define('APP_PATH', dirname(__DIR__) . '/app');

// Include only what we need
require_once APP_PATH . '/core/Database.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Admin User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 { color: #333; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0; }
        hr { border: none; border-top: 2px solid #ddd; margin: 20px 0; }
        .credentials { background: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>

<h1>🔧 Admin User Fix</h1>

<?php
try {
    // Get database instance
    $db = Database::getInstance();
    
    echo "<p>✓ Database connection successful!</p>";
    
    // Admin credentials
    $admin_email = 'admin@modernzonetrading.com';
    $admin_password = 'Admin@123';
    $admin_name = 'Admin';
    
    // Generate a fresh password hash
    $hashed_password = password_hash($admin_password, PASSWORD_BCRYPT, ['cost' => 10]);
    
    echo "<div class='info'>";
    echo "<h3>Step 1: Checking for existing admin user...</h3>";
    
    // Check if admin user exists
    $existing_user = $db->selectOne(
        "SELECT id, name, email, role, status FROM users WHERE email = :email",
        ['email' => $admin_email]
    );
    
    if ($existing_user) {
        echo "<p>Found existing user with email: <strong>{$admin_email}</strong></p>";
        echo "<p>Current Role: {$existing_user['role']}, Status: {$existing_user['status']}</p>";
        
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
        echo "<p>No existing user found. Creating new admin user...</p>";
        
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
    echo "</div>";
    
    echo "<hr>";
    
    // Verify the password works
    echo "<div class='info'>";
    echo "<h3>Step 2: Verifying credentials...</h3>";
    
    $verify_user = $db->selectOne(
        "SELECT * FROM users WHERE email = :email",
        ['email' => $admin_email]
    );
    
    if ($verify_user && password_verify($admin_password, $verify_user['password'])) {
        echo "<p class='success'>✓ Password verification successful!</p>";
        echo "<p><strong>User Details:</strong></p>";
        echo "<ul>";
        echo "<li><strong>ID:</strong> {$verify_user['id']}</li>";
        echo "<li><strong>Name:</strong> {$verify_user['name']}</li>";
        echo "<li><strong>Email:</strong> {$verify_user['email']}</li>";
        echo "<li><strong>Role:</strong> {$verify_user['role']}</li>";
        echo "<li><strong>Status:</strong> {$verify_user['status']}</li>";
        echo "</ul>";
    } else {
        echo "<p class='error'>✗ Password verification failed!</p>";
        echo "<p>Something went wrong. Please try running this script again.</p>";
    }
    echo "</div>";
    
    echo "<hr>";
    
    // Show credentials
    echo "<div class='credentials'>";
    echo "<h2>📝 Admin Login Credentials</h2>";
    echo "<p><strong>Email:</strong> <code>{$admin_email}</code></p>";
    echo "<p><strong>Password:</strong> <code>{$admin_password}</code></p>";
    echo "</div>";
    
    // Next steps
    echo "<div class='info'>";
    echo "<h3>✨ Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Go to the login page: <a href='/host/Modern-Zone-Ecommerce-production-ready-/public/login' class='btn'>Go to Login</a></li>";
    echo "<li>Use the credentials shown above</li>";
    echo "<li>You will be automatically redirected to the admin dashboard</li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<hr>";
    echo "<p style='color: #ff6b6b;'><strong>⚠️ IMPORTANT:</strong> Delete this file (fix_admin.php) after use for security!</p>";
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ Error Occurred:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h4>Debug Information:</h4>";
    echo "<pre style='background: #f8f9fa; padding: 15px; overflow: auto;'>";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
    echo "</div>";
    
    echo "<hr>";
    echo "<h3>Troubleshooting Steps:</h3>";
    echo "<ol>";
    echo "<li>Make sure XAMPP MySQL is running</li>";
    echo "<li>Check if database 'modernzone_db' exists</li>";
    echo "<li>Verify database credentials in app/config/database.php</li>";
    echo "<li>Make sure the 'users' table exists (import complete_schema.sql)</li>";
    echo "</ol>";
}
?>

</body>
</html>

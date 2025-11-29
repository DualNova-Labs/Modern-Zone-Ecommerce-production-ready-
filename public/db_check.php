<?php
/**
 * Database Diagnostic Tool
 * Shows what's actually in the database
 */

define('APP_PATH', dirname(__DIR__) . '/app');
require_once APP_PATH . '/core/Database.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Diagnostic</title>
    <style>
        body { font-family: monospace; margin: 20px; background: #1e1e1e; color: #d4d4d4; }
        h1, h2 { color: #4ec9b0; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; background: #252526; }
        th, td { border: 1px solid #3e3e42; padding: 10px; text-align: left; }
        th { background: #2d2d30; color: #569cd6; }
        pre { background: #252526; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .box { background: #252526; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #007acc; }
    </style>
</head>
<body>

<h1>🔍 Database Diagnostic Tool</h1>

<?php
try {
    $db = Database::getInstance();
    echo "<p class='success'>✓ Database connection successful!</p>";
    
    // Check if users table exists
    echo "<div class='box'>";
    echo "<h2>1. Checking if 'users' table exists...</h2>";
    try {
        $tables = $db->select("SHOW TABLES LIKE 'users'");
        if (count($tables) > 0) {
            echo "<p class='success'>✓ Users table exists</p>";
            
            // Count users
            $count = $db->selectOne("SELECT COUNT(*) as count FROM users");
            echo "<p><strong>Total users in database:</strong> {$count['count']}</p>";
            
            // Show all users
            echo "<h3>All Users:</h3>";
            $users = $db->select("SELECT id, name, email, role, status, created_at FROM users ORDER BY id");
            if (count($users) > 0) {
                echo "<table>";
                echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Created</th></tr>";
                foreach ($users as $user) {
                    $roleColor = ($user['role'] === 'admin') ? '#4ec9b0' : '#ce9178';
                    $statusColor = ($user['status'] === 'active') ? '#4ec9b0' : '#f48771';
                    echo "<tr>";
                    echo "<td>{$user['id']}</td>";
                    echo "<td>{$user['name']}</td>";
                    echo "<td>{$user['email']}</td>";
                    echo "<td style='color: {$roleColor}; font-weight: bold;'>{$user['role']}</td>";
                    echo "<td style='color: {$statusColor};'>{$user['status']}</td>";
                    echo "<td>{$user['created_at']}</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='warning'>⚠ No users found in the database!</p>";
            }
            
            // Check specifically for admin@modernzonetrading.com
            echo "<h3>Checking for admin user...</h3>";
            $admin = $db->selectOne("SELECT * FROM users WHERE email = 'admin@modernzonetrading.com'");
            if ($admin) {
                echo "<p class='success'>✓ Admin user found!</p>";
                echo "<pre>";
                echo "ID: " . $admin['id'] . "\n";
                echo "Name: " . $admin['name'] . "\n";
                echo "Email: " . $admin['email'] . "\n";
                echo "Role: " . $admin['role'] . "\n";
                echo "Status: " . $admin['status'] . "\n";
                echo "Password Hash: " . substr($admin['password'], 0, 30) . "...\n";
                echo "Created: " . $admin['created_at'] . "\n";
                echo "</pre>";
                
                // Test password verification
                echo "<h3>Testing Password...</h3>";
                $testPassword = 'Admin@123';
                if (password_verify($testPassword, $admin['password'])) {
                    echo "<p class='success'>✓ Password 'Admin@123' VERIFIED successfully!</p>";
                    echo "<p style='color: #4ec9b0; font-size: 18px;'>✅ <strong>The admin user is correctly configured!</strong></p>";
                } else {
                    echo "<p class='error'>✗ Password 'Admin@123' does NOT match!</p>";
                    echo "<p class='warning'>⚠ The password hash in the database is incorrect.</p>";
                    
                    // Offer to fix it
                    echo "<h4>Fix Password:</h4>";
                    echo "<form method='POST' action='?fix=1'>";
                    echo "<button type='submit' style='padding: 10px 20px; background: #007acc; color: white; border: none; cursor: pointer; border-radius: 3px;'>Fix Admin Password Now</button>";
                    echo "</form>";
                }
            } else {
                echo "<p class='error'>✗ Admin user (admin@modernzonetrading.com) NOT found!</p>";
                echo "<p class='warning'>⚠ Need to create the admin user.</p>";
                
                // Offer to create it
                echo "<h4>Create Admin User:</h4>";
                echo "<form method='POST' action='?create=1'>";
                echo "<button type='submit' style='padding: 10px 20px; background: #007acc; color: white; border: none; cursor: pointer; border-radius: 3px;'>Create Admin User Now</button>";
                echo "</form>";
            }
            
        } else {
            echo "<p class='error'>✗ Users table does NOT exist!</p>";
            echo "<p>You need to import the database schema first:</p>";
            echo "<pre>mysql -u root -p modernzone_db < database/complete_schema.sql</pre>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error checking users table: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    echo "</div>";
    
    // Handle fix request
    if (isset($_POST) && isset($_GET['fix'])) {
        echo "<div class='box'>";
        echo "<h2>Fixing Admin Password...</h2>";
        $newHash = password_hash('Admin@123', PASSWORD_BCRYPT, ['cost' => 10]);
        $db->query(
            "UPDATE users SET password = :password WHERE email = 'admin@modernzonetrading.com'",
            ['password' => $newHash]
        );
        echo "<p class='success'>✓ Password updated! Please refresh this page to verify.</p>";
        echo "</div>";
    }
    
    // Handle create request
    if (isset($_POST) && isset($_GET['create'])) {
        echo "<div class='box'>";
        echo "<h2>Creating Admin User...</h2>";
        $hash = password_hash('Admin@123', PASSWORD_BCRYPT, ['cost' => 10]);
        $db->insert('users', [
            'name' => 'Admin',
            'email' => 'admin@modernzonetrading.com',
            'password' => $hash,
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);
        echo "<p class='success'>✓ Admin user created! Please refresh this page to verify.</p>";
        echo "</div>";
    }
    
    // Show login link
    echo "<div class='box'>";
    echo "<h2>✨ Ready to Login?</h2>";
    echo "<p><strong>Email:</strong> <code>admin@modernzonetrading.com</code></p>";
    echo "<p><strong>Password:</strong> <code>Admin@123</code></p>";
    echo "<p><a href='/host/Modern-Zone-Ecommerce-production-ready-/public/login' style='padding: 10px 20px; background: #007acc; color: white; text-decoration: none; border-radius: 3px; display: inline-block; margin-top: 10px;'>Go to Login Page</a></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='box'>";
    echo "<h2 class='error'>❌ Database Connection Failed!</h2>";
    echo "<p class='error'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h3>Troubleshooting:</h3>";
    echo "<ol>";
    echo "<li>Make sure XAMPP MySQL is running</li>";
    echo "<li>Check database name is 'modernzone_db'</li>";
    echo "<li>Verify database credentials in app/config/database.php</li>";
    echo "<li>Try importing the database schema</li>";
    echo "</ol>";
    echo "</div>";
}
?>

</body>
</html>

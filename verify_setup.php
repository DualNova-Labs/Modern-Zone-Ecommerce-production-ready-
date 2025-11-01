<?php
/**
 * Setup Verification Script
 * Checks if the system is properly configured for admin login
 */

// Define paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');

echo "=================================\n";
echo "Modern Zone - Setup Verification\n";
echo "=================================\n\n";

// Check 1: Environment file
echo "[1/6] Checking .env file... ";
if (file_exists(ROOT_PATH . '/.env')) {
    echo "✓ Found\n";
} else {
    echo "✗ Missing\n";
    echo "      Action: Copy .env.example to .env\n";
}

// Check 2: Load environment
echo "[2/6] Loading environment... ";
try {
    require_once APP_PATH . '/core/Environment.php';
    Environment::load(ROOT_PATH);
    echo "✓ Success\n";
} catch (Exception $e) {
    echo "✗ Failed: " . $e->getMessage() . "\n";
}

// Check 3: Database configuration
echo "[3/6] Checking database config... ";
$dbHost = Environment::get('DB_HOST', 'localhost');
$dbName = Environment::get('DB_DATABASE', 'modernzone_db');
$dbUser = Environment::get('DB_USERNAME', 'root');
$dbPass = Environment::get('DB_PASSWORD', '');
echo "✓ Loaded\n";
echo "      Host: {$dbHost}\n";
echo "      Database: {$dbName}\n";
echo "      Username: {$dbUser}\n";

// Check 4: Database connection
echo "[4/6] Testing database connection... ";
try {
    require_once APP_PATH . '/core/Database.php';
    $db = Database::getInstance();
    echo "✓ Connected\n";
} catch (Exception $e) {
    echo "✗ Failed\n";
    echo "      Error: " . $e->getMessage() . "\n";
    echo "      Action: Ensure MySQL is running and database exists\n";
    exit(1);
}

// Check 5: Verify users table
echo "[5/6] Checking users table... ";
try {
    $result = $db->query("SHOW TABLES LIKE 'users'");
    if ($result->rowCount() > 0) {
        echo "✓ Exists\n";
    } else {
        echo "✗ Missing\n";
        echo "      Action: Import database/schema.sql\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Check 6: Verify admin user
echo "[6/6] Checking admin user... ";
try {
    $admin = $db->selectOne(
        "SELECT id, name, email, role, status FROM users WHERE email = :email",
        ['email' => 'admin@modernzonetrading.com']
    );
    
    if ($admin) {
        echo "✓ Found\n";
        echo "      ID: {$admin['id']}\n";
        echo "      Name: {$admin['name']}\n";
        echo "      Email: {$admin['email']}\n";
        echo "      Role: {$admin['role']}\n";
        echo "      Status: {$admin['status']}\n";
        
        if ($admin['role'] !== 'admin') {
            echo "      ⚠ Warning: Role is not 'admin'\n";
            echo "      Action: Run setup_admin.php to fix\n";
        }
        if ($admin['status'] !== 'active') {
            echo "      ⚠ Warning: Status is not 'active'\n";
            echo "      Action: Run setup_admin.php to fix\n";
        }
    } else {
        echo "✗ Not found\n";
        echo "      Action: Run setup_admin.php to create admin user\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=================================\n";
echo "Verification Complete\n";
echo "=================================\n\n";

if (isset($admin) && $admin && $admin['role'] === 'admin' && $admin['status'] === 'active') {
    echo "✓ System is ready for admin login!\n\n";
    echo "Login URL: http://localhost/host/Modern-Zone-Ecommerce-production-ready-/login\n";
    echo "Email: admin@modernzonetrading.com\n";
    echo "Password: Admin@123\n\n";
    echo "Note: If login fails, run: c:\\xampp\\php\\php.exe setup_admin.php\n";
} else {
    echo "⚠ Setup incomplete. Please run:\n";
    echo "   c:\\xampp\\php\\php.exe setup_admin.php\n";
}

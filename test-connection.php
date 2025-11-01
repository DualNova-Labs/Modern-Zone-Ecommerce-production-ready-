<?php
/**
 * Production Database Connection Test
 * 
 * This script tests the database connection using production credentials.
 * DELETE THIS FILE after verifying the connection works.
 */

// Security check - only allow in non-production or with secret key
$secretKey = $_GET['key'] ?? '';
if ($secretKey !== 'test2025secure') {
    die('Access denied. Use ?key=test2025secure');
}

// Load environment
require_once __DIR__ . '/app/core/Environment.php';
Environment::load(__DIR__);

// Get database credentials from environment
$dbHost = Environment::get('DB_HOST', 'localhost');
$dbPort = Environment::get('DB_PORT', 3306);
$dbName = Environment::get('DB_DATABASE', '');
$dbUser = Environment::get('DB_USERNAME', '');
$dbPass = Environment::get('DB_PASSWORD', '');
$dbCharset = 'utf8mb4';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: #27ae60; background: #d5f4e6; padding: 15px; border-radius: 5px; }
        .error { color: #c0392b; background: #fadbd8; padding: 15px; border-radius: 5px; }
        .info { color: #2980b9; background: #d6eaf8; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .warning { color: #d68910; background: #fcf3cf; padding: 15px; border-radius: 5px; margin-top: 20px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 3px; overflow-x: auto; }
        h1 { color: #2c3e50; }
    </style>
</head>
<body>
    <h1>🔌 Database Connection Test</h1>";

// Test connection
$pdoOptions = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
    PDO::ATTR_TIMEOUT            => 5,
];

try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset={$dbCharset}";
    $pdo = new PDO($dsn, $dbUser, $dbPass, $pdoOptions);
    
    echo "<div class='success'>
        <h2>✅ Connection Successful!</h2>
        <p>Successfully connected to the production database.</p>
    </div>";
    
    // Get server info
    $serverInfo = $pdo->getAttribute(PDO::ATTR_SERVER_INFO);
    $serverVersion = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    $clientVersion = $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION);
    
    echo "<div class='info'>
        <h3>📊 Database Information</h3>
        <pre>Host: {$dbHost}:{$dbPort}
Database: {$dbName}
Username: {$dbUser}
Charset: {$dbCharset}

Server Version: {$serverVersion}
Client Version: {$clientVersion}
Server Info: {$serverInfo}</pre>
    </div>";
    
    // Test query
    $stmt = $pdo->query("SELECT DATABASE() as current_db, NOW() as current_time");
    $result = $stmt->fetch();
    
    echo "<div class='info'>
        <h3>🔍 Test Query Results</h3>
        <pre>Current Database: {$result['current_db']}
Current Time: {$result['current_time']}</pre>
    </div>";
    
    // List tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<div class='info'>
            <h3>📋 Database Tables (" . count($tables) . ")</h3>
            <pre>" . implode("\n", $tables) . "</pre>
        </div>";
    } else {
        echo "<div class='warning'>
            <h3>⚠️ No Tables Found</h3>
            <p>The database exists but contains no tables. You may need to run migrations.</p>
        </div>";
    }
    
    echo "<div class='warning'>
        <h3>🔒 Security Notice</h3>
        <p><strong>IMPORTANT:</strong> Delete this file (<code>test-connection.php</code>) after verifying the connection works. 
        This file exposes database information and should not be accessible in production.</p>
    </div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>
        <h2>❌ Connection Failed</h2>
        <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
        <p><strong>Error Code:</strong> " . $e->getCode() . "</p>
    </div>";
    
    echo "<div class='info'>
        <h3>🔧 Troubleshooting Steps</h3>
        <ol>
            <li>Verify database credentials in <code>.env</code> file</li>
            <li>Check if database server is accessible from this host</li>
            <li>Verify database user has proper permissions</li>
            <li>Check firewall rules allow connection to database server</li>
            <li>Ensure database exists on the server</li>
        </ol>
    </div>";
    
    echo "<div class='info'>
        <h3>📝 Connection Details (verify these)</h3>
        <pre>Host: {$dbHost}:{$dbPort}
Database: {$dbName}
Username: {$dbUser}
Password: " . (empty($dbPass) ? '[EMPTY]' : '[SET - ' . strlen($dbPass) . ' characters]') . "</pre>
    </div>";
}

echo "</body>
</html>";

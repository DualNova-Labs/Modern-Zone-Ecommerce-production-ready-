<?php
/**
 * Deya PHP Framework - Entry Point
 * APTools Frontend Application
 */

// Start session
session_start();

// Define paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('VIEWS_PATH', APP_PATH . '/views');
define('ASSETS_PATH', PUBLIC_PATH . '/assets');

// Load environment configuration
require_once APP_PATH . '/core/Environment.php';
Environment::load(ROOT_PATH);

// Configure error reporting based on environment
$appEnv = Environment::get('APP_ENV', 'production');
$appDebug = Environment::get('APP_DEBUG', false);

if ($appEnv === 'local' || $appDebug === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/logs/error.log');
}

// Define base URL dynamically
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($scriptName, '/'));

// Autoload classes
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load configuration
require_once APP_PATH . '/config/config.php';

// Initialize router
require_once APP_PATH . '/core/Router.php';
require_once APP_PATH . '/core/View.php';
require_once APP_PATH . '/core/Request.php';

// Load routes
$router = new Router();
require_once APP_PATH . '/routes/web.php';

// Handle request
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);

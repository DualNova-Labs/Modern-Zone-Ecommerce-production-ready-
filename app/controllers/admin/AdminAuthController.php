<?php
/**
 * Admin Authentication Controller
 * Handles admin login and authentication
 */
require_once APP_PATH . '/core/Auth.php';
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/core/View.php';
require_once APP_PATH . '/core/Request.php';

class AdminAuthController
{
    private $auth;
    private $security;
    
    public function __construct()
    {
        $this->auth = Auth::getInstance();
        $this->security = Security::getInstance();
    }
    
    /**
     * Show admin login form - redirects to main login
     */
    public function showLogin()
    {
        // Redirect to main login page
        header('Location: ' . View::url('/login'));
        exit;
    }
    
    /**
     * Process admin login
     */
    public function login()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['login_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/login'));
            exit;
        }
        
        $email = $this->security->cleanInput(Request::post('email'));
        $password = Request::post('password');
        $remember = Request::post('remember') === 'on';
        
        // Validate input
        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Please enter both email and password.';
            header('Location: ' . View::url('/login'));
            exit;
        }
        
        // Attempt login
        $result = $this->auth->attempt($email, $password, $remember);
        
        if ($result['success']) {
            // Check if user is admin
            if (!$this->auth->isAdmin()) {
                $this->auth->logout();
                $_SESSION['login_error'] = 'You do not have admin access.';
                header('Location: ' . View::url('/login'));
                exit;
            }
            
            // Log admin login
            $this->logAdminAccess('login');
            
            // Redirect to admin dashboard
            $redirect = $_SESSION['intended_url'] ?? View::url('/admin');
            unset($_SESSION['intended_url']);
            header('Location: ' . $redirect);
            exit;
        } else {
            $_SESSION['login_error'] = $result['error'];
            header('Location: ' . View::url('/login'));
            exit;
        }
    }
    
    /**
     * Admin logout
     */
    public function logout()
    {
        if ($this->auth->check()) {
            $this->logAdminAccess('logout');
            $this->auth->logout();
        }
        
        // Start new session for flash message
        session_start();
        $_SESSION['login_success'] = 'You have been logged out successfully.';
        
        header('Location: ' . View::url('/login'));
        exit;
    }
    
    /**
     * Log admin access
     */
    private function logAdminAccess($action)
    {
        $user = $this->auth->user();
        if ($user) {
            $log = date('Y-m-d H:i:s') . " Admin {$action}: {$user->email} from IP: " . $_SERVER['REMOTE_ADDR'];
            error_log($log, 3, APP_PATH . '/../storage/logs/admin_access.log');
        }
    }
}

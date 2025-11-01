<?php
/**
 * Authentication Controller
 */
require_once APP_PATH . '/core/Auth.php';
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/models/User.php';

class AuthController
{
    public function showLogin()
    {
        // Redirect if already logged in
        $auth = Auth::getInstance();
        if ($auth->check()) {
            header('Location: ' . View::url('/'));
            exit;
        }
        
        $security = Security::getInstance();
        $data = [
            'title' => 'Login - Modern Zone Trading',
            'description' => 'Login to your Modern Zone Trading account to access your orders, quotes, and account information.',
            'csrf_token' => $security->getCsrfToken(),
            'error' => $_SESSION['login_error'] ?? null,
            'success' => $_SESSION['login_success'] ?? null,
        ];
        
        // Clear flash messages
        unset($_SESSION['login_error']);
        unset($_SESSION['login_success']);
        
        View::render('pages/auth/login', $data);
    }
    
    public function login()
    {
        $security = Security::getInstance();
        
        // Validate CSRF token
        if (!$security->validateCsrfToken()) {
            $_SESSION['login_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/login'));
            exit;
        }
        
        // Get and sanitize input
        $email = $security->cleanInput(Request::post('email'));
        $password = Request::post('password'); // Don't sanitize password
        $remember = Request::post('remember') === 'on';
        
        // Validate input
        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Please enter both email and password.';
            header('Location: ' . View::url('/login'));
            exit;
        }
        
        // Attempt login
        $auth = Auth::getInstance();
        $result = $auth->attempt($email, $password, $remember);
        
        if ($result['success']) {
            // Check if user is admin and redirect accordingly
            $auth = Auth::getInstance();
            if ($auth->isAdmin()) {
                // Redirect admin users to admin dashboard
                $redirect = $_SESSION['intended_url'] ?? View::url('/admin');
            } else {
                // Redirect regular users to homepage or intended URL
                $redirect = $_SESSION['intended_url'] ?? View::url('/');
            }
            unset($_SESSION['intended_url']);
            header('Location: ' . $redirect);
            exit;
        } else {
            $_SESSION['login_error'] = $result['error'];
            header('Location: ' . View::url('/login'));
            exit;
        }
    }
    
    public function showRegister()
    {
        // Redirect if already logged in
        $auth = Auth::getInstance();
        if ($auth->check()) {
            header('Location: ' . View::url('/'));
            exit;
        }
        
        $security = Security::getInstance();
        $data = [
            'title' => 'Register - Modern Zone Trading',
            'description' => 'Create a Modern Zone Trading account to place orders, request quotes, and track your purchases.',
            'csrf_token' => $security->getCsrfToken(),
            'errors' => $_SESSION['register_errors'] ?? [],
            'old' => $_SESSION['register_old'] ?? [],
            'success' => $_SESSION['register_success'] ?? null,
        ];
        
        // Clear flash data
        unset($_SESSION['register_errors']);
        unset($_SESSION['register_old']);
        unset($_SESSION['register_success']);
        
        View::render('pages/auth/register', $data);
    }
    
    public function register()
    {
        $security = Security::getInstance();
        
        // Validate CSRF token
        if (!$security->validateCsrfToken()) {
            $_SESSION['register_errors'] = ['general' => 'Invalid security token. Please try again.'];
            header('Location: ' . View::url('/register'));
            exit;
        }
        
        // Get and sanitize input
        $data = [
            'name' => $security->cleanInput(Request::post('name')),
            'email' => $security->cleanInput(Request::post('email')),
            'phone' => $security->cleanInput(Request::post('phone')),
            'company' => $security->cleanInput(Request::post('company')),
            'password' => Request::post('password'),
            'password_confirm' => Request::post('password_confirm'),
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        } elseif (strlen($data['name']) < 2) {
            $errors['name'] = 'Name must be at least 2 characters';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!$security->validateEmail($data['email'])) {
            $errors['email'] = 'Invalid email format';
        }
        
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        } elseif (!preg_match('/[A-Z]/', $data['password'])) {
            $errors['password'] = 'Password must contain at least one uppercase letter';
        } elseif (!preg_match('/[a-z]/', $data['password'])) {
            $errors['password'] = 'Password must contain at least one lowercase letter';
        } elseif (!preg_match('/[0-9]/', $data['password'])) {
            $errors['password'] = 'Password must contain at least one number';
        }
        
        if ($data['password'] !== $data['password_confirm']) {
            $errors['password_confirm'] = 'Passwords do not match';
        }
        
        // If validation fails, redirect back with errors
        if (!empty($errors)) {
            $_SESSION['register_errors'] = $errors;
            $_SESSION['register_old'] = $data;
            unset($_SESSION['register_old']['password']);
            unset($_SESSION['register_old']['password_confirm']);
            header('Location: ' . View::url('/register'));
            exit;
        }
        
        // Attempt to register user
        $result = User::register($data);
        
        if ($result['success']) {
            // Auto-login the user
            $auth = Auth::getInstance();
            $auth->login($result['user']);
            
            $_SESSION['register_success'] = 'Registration successful! Welcome to Modern Zone Trading.';
            header('Location: ' . View::url('/'));
            exit;
        } else {
            $_SESSION['register_errors'] = ['general' => $result['error']];
            $_SESSION['register_old'] = $data;
            unset($_SESSION['register_old']['password']);
            unset($_SESSION['register_old']['password_confirm']);
            header('Location: ' . View::url('/register'));
            exit;
        }
    }
    
    public function logout()
    {
        $auth = Auth::getInstance();
        $auth->logout();
        
        // Start new session for flash message
        session_start();
        $_SESSION['login_success'] = 'You have been logged out successfully.';
        
        header('Location: ' . View::url('/'));
        exit;
    }
    
    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        $security = Security::getInstance();
        $data = [
            'title' => 'Forgot Password - Modern Zone Trading',
            'csrf_token' => $security->getCsrfToken(),
            'error' => $_SESSION['forgot_error'] ?? null,
            'success' => $_SESSION['forgot_success'] ?? null,
        ];
        
        unset($_SESSION['forgot_error']);
        unset($_SESSION['forgot_success']);
        
        View::render('pages/auth/forgot-password', $data);
    }
    
    /**
     * Handle forgot password request
     */
    public function forgotPassword()
    {
        $security = Security::getInstance();
        
        // Validate CSRF token
        if (!$security->validateCsrfToken()) {
            $_SESSION['forgot_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/forgot-password'));
            exit;
        }
        
        $email = $security->cleanInput(Request::post('email'));
        
        if (empty($email) || !$security->validateEmail($email)) {
            $_SESSION['forgot_error'] = 'Please enter a valid email address.';
            header('Location: ' . View::url('/forgot-password'));
            exit;
        }
        
        // Find user
        $user = User::findBy('email', $email);
        
        if ($user) {
            // Generate reset token
            $token = $user->createPasswordResetToken();
            
            // TODO: Send reset email
            // For now, we'll just show the token (remove in production)
            $_SESSION['forgot_success'] = 'Password reset instructions have been sent to your email.';
            // $_SESSION['debug_token'] = $token; // Remove this in production
        } else {
            // Don't reveal if email exists
            $_SESSION['forgot_success'] = 'If an account exists with this email, you will receive password reset instructions.';
        }
        
        header('Location: ' . View::url('/forgot-password'));
        exit;
    }
    
    /**
     * Show reset password form
     */
    public function showResetPassword()
    {
        $token = Request::get('token');
        $email = Request::get('email');
        
        if (empty($token) || empty($email)) {
            $_SESSION['login_error'] = 'Invalid password reset link.';
            header('Location: ' . View::url('/login'));
            exit;
        }
        
        $security = Security::getInstance();
        $data = [
            'title' => 'Reset Password - Modern Zone Trading',
            'csrf_token' => $security->getCsrfToken(),
            'token' => $token,
            'email' => $email,
            'error' => $_SESSION['reset_error'] ?? null,
        ];
        
        unset($_SESSION['reset_error']);
        
        View::render('pages/auth/reset-password', $data);
    }
    
    /**
     * Handle password reset
     */
    public function resetPassword()
    {
        $security = Security::getInstance();
        
        // Validate CSRF token
        if (!$security->validateCsrfToken()) {
            $_SESSION['reset_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        
        $email = $security->cleanInput(Request::post('email'));
        $token = Request::post('token');
        $password = Request::post('password');
        $passwordConfirm = Request::post('password_confirm');
        
        // Validate password
        if (empty($password) || strlen($password) < 8) {
            $_SESSION['reset_error'] = 'Password must be at least 8 characters.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        
        if ($password !== $passwordConfirm) {
            $_SESSION['reset_error'] = 'Passwords do not match.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        
        // Reset password
        $result = User::resetPassword($email, $token, $password);
        
        if ($result['success']) {
            $_SESSION['login_success'] = 'Password reset successful. You can now login with your new password.';
            header('Location: ' . View::url('/login'));
        } else {
            $_SESSION['reset_error'] = $result['error'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        exit;
    }
}

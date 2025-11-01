<?php
/**
 * Auth Helper Class
 * 
 * Manages user authentication and session
 */
require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/core/Security.php';

class Auth
{
    private static $instance = null;
    private $user = null;
    private $security;
    
    private function __construct()
    {
        $this->security = Security::getInstance();
        $this->checkSession();
    }
    
    /**
     * Get instance (Singleton)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Attempt to login user
     */
    public function attempt($email, $password, $remember = false)
    {
        // Rate limiting
        $identifier = 'login_' . $email . '_' . $_SERVER['REMOTE_ADDR'];
        if (!$this->security->checkRateLimit($identifier, 5, 15)) {
            return [
                'success' => false,
                'error' => 'Too many login attempts. Please try again later.'
            ];
        }
        
        // Validate input
        if (!$this->security->validateEmail($email)) {
            return [
                'success' => false,
                'error' => 'Invalid email format'
            ];
        }
        
        // Authenticate user
        $result = User::authenticate($email, $password);
        
        if ($result['success']) {
            $this->login($result['user'], $remember);
            $this->security->clearRateLimit($identifier);
            return ['success' => true];
        }
        
        return $result;
    }
    
    /**
     * Login user
     */
    public function login($user, $remember = false)
    {
        $this->user = $user;
        
        // Regenerate session ID for security
        $this->security->regenerateSessionId();
        
        // Store user ID in session
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();
        
        // Handle remember me
        if ($remember) {
            $token = $user->generateRememberToken();
            $this->setRememberCookie($user->id, $token);
        }
        
        // Store session in database
        $this->storeSession();
    }
    
    /**
     * Logout user
     */
    public function logout()
    {
        if ($this->user) {
            // Clear remember token
            $this->user->clearRememberToken();
            
            // Delete session from database
            $this->deleteSession();
        }
        
        // Clear remember cookie
        $this->clearRememberCookie();
        
        // Destroy session
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        $this->user = null;
    }
    
    /**
     * Check if user is logged in
     */
    public function check()
    {
        return $this->user !== null;
    }
    
    /**
     * Check if user is guest
     */
    public function guest()
    {
        return $this->user === null;
    }
    
    /**
     * Get current user
     */
    public function user()
    {
        return $this->user;
    }
    
    /**
     * Get user ID
     */
    public function id()
    {
        return $this->user ? $this->user->id : null;
    }
    
    /**
     * Check if user has role
     */
    public function hasRole($role)
    {
        return $this->user && $this->user->hasRole($role);
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->user && $this->user->isAdmin();
    }
    
    /**
     * Check session
     */
    private function checkSession()
    {
        // Check session timeout
        if (isset($_SESSION['last_activity'])) {
            $sessionLifetime = 7200; // 2 hours
            if (time() - $_SESSION['last_activity'] > $sessionLifetime) {
                $this->logout();
                return;
            }
            $_SESSION['last_activity'] = time();
        }
        
        // Check if user is logged in via session
        if (isset($_SESSION['user_id'])) {
            $this->user = User::find($_SESSION['user_id']);
            
            // Verify user still exists and is active
            if (!$this->user || $this->user->status !== 'active') {
                $this->logout();
                return;
            }
        }
        // Check remember cookie
        elseif (isset($_COOKIE['remember_token'])) {
            $this->checkRememberCookie();
        }
    }
    
    /**
     * Check remember cookie
     */
    private function checkRememberCookie()
    {
        if (!isset($_COOKIE['remember_token'])) {
            return;
        }
        
        $cookie = $_COOKIE['remember_token'];
        $parts = explode('|', $cookie);
        
        if (count($parts) !== 2) {
            $this->clearRememberCookie();
            return;
        }
        
        list($userId, $token) = $parts;
        
        // Verify token
        $user = User::find($userId);
        if ($user && $user->remember_token === $token && $user->status === 'active') {
            $this->login($user, false);
        } else {
            $this->clearRememberCookie();
        }
    }
    
    /**
     * Set remember cookie
     */
    private function setRememberCookie($userId, $token)
    {
        $cookie = $userId . '|' . $token;
        $expire = time() + (30 * 24 * 60 * 60); // 30 days
        
        setcookie(
            'remember_token',
            $cookie,
            $expire,
            '/',
            '',
            isset($_SERVER['HTTPS']),
            true
        );
    }
    
    /**
     * Clear remember cookie
     */
    private function clearRememberCookie()
    {
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
    }
    
    /**
     * Store session in database
     */
    private function storeSession()
    {
        if (!$this->user) {
            return;
        }
        
        $db = Database::getInstance();
        
        // Delete old sessions for this user (optional: allow multiple sessions)
        // $db->delete('user_sessions', 'user_id = :user_id', ['user_id' => $this->user->id]);
        
        // Store new session
        $sessionData = [
            'id' => session_id(),
            'user_id' => $this->user->id,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'payload' => serialize($_SESSION),
            'last_activity' => time()
        ];
        
        try {
            $db->query(
                "INSERT INTO user_sessions (id, user_id, ip_address, user_agent, payload, last_activity) 
                 VALUES (:id, :user_id, :ip_address, :user_agent, :payload, :last_activity)
                 ON DUPLICATE KEY UPDATE 
                 payload = VALUES(payload), 
                 last_activity = VALUES(last_activity)",
                $sessionData
            );
        } catch (Exception $e) {
            // Log error but don't break authentication
            error_log("Failed to store session: " . $e->getMessage());
        }
    }
    
    /**
     * Delete session from database
     */
    private function deleteSession()
    {
        $db = Database::getInstance();
        
        try {
            $db->delete('user_sessions', 'id = :id', ['id' => session_id()]);
        } catch (Exception $e) {
            // Log error but don't break logout
            error_log("Failed to delete session: " . $e->getMessage());
        }
    }
    
    /**
     * Clean old sessions
     */
    public static function cleanOldSessions($days = 30)
    {
        $db = Database::getInstance();
        $cutoff = time() - ($days * 24 * 60 * 60);
        
        try {
            $db->delete('user_sessions', 'last_activity < :cutoff', ['cutoff' => $cutoff]);
        } catch (Exception $e) {
            error_log("Failed to clean old sessions: " . $e->getMessage());
        }
    }
}

<?php
/**
 * Security Class
 * 
 * Handles CSRF protection, XSS prevention, and other security features
 */
class Security
{
    private static $instance = null;
    private $csrfTokenName = '_csrf_token';
    private $csrfCookieName = '_csrf_cookie';
    private $csrfExpire = 7200; // 2 hours
    private $csrfRegenerate = true;
    
    private function __construct()
    {
        // Load config if exists
        if (defined('CSRF_TOKEN_NAME')) {
            $this->csrfTokenName = CSRF_TOKEN_NAME;
        }
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
     * Generate CSRF token
     */
    public function generateCsrfToken()
    {
        if (!isset($_SESSION[$this->csrfTokenName]) || $this->csrfRegenerate) {
            $_SESSION[$this->csrfTokenName] = bin2hex(random_bytes(32));
            $_SESSION[$this->csrfTokenName . '_time'] = time();
        }
        
        return $_SESSION[$this->csrfTokenName];
    }
    
    /**
     * Get CSRF token
     */
    public function getCsrfToken()
    {
        if (!isset($_SESSION[$this->csrfTokenName])) {
            return $this->generateCsrfToken();
        }
        
        // Check if token expired
        if (isset($_SESSION[$this->csrfTokenName . '_time'])) {
            if (time() - $_SESSION[$this->csrfTokenName . '_time'] > $this->csrfExpire) {
                return $this->generateCsrfToken();
            }
        }
        
        return $_SESSION[$this->csrfTokenName];
    }
    
    /**
     * Validate CSRF token
     */
    public function validateCsrfToken($token = null)
    {
        if ($token === null) {
            // Try to get token from POST, then headers
            $token = $_POST['csrf_token'] ?? 
                     $_POST[$this->csrfTokenName] ?? 
                     $_SERVER['HTTP_X_CSRF_TOKEN'] ?? 
                     $_SERVER['HTTP_X_XSRF_TOKEN'] ?? 
                     null;
        }
        
        if (empty($token) || !isset($_SESSION[$this->csrfTokenName])) {
            return false;
        }
        
        // Check token expiration
        if (isset($_SESSION[$this->csrfTokenName . '_time'])) {
            if (time() - $_SESSION[$this->csrfTokenName . '_time'] > $this->csrfExpire) {
                return false;
            }
        }
        
        // Validate token
        $valid = hash_equals($_SESSION[$this->csrfTokenName], $token);
        
        // Regenerate token after successful validation if configured
        if ($valid && $this->csrfRegenerate) {
            $this->generateCsrfToken();
        }
        
        return $valid;
    }
    
    /**
     * Get CSRF token field HTML
     */
    public function csrfField()
    {
        $token = $this->getCsrfToken();
        return '<input type="hidden" name="' . $this->csrfTokenName . '" value="' . $token . '">';
    }
    
    /**
     * Get CSRF token meta tag HTML
     */
    public function csrfMetaTag()
    {
        $token = $this->getCsrfToken();
        return '<meta name="csrf-token" content="' . $token . '">';
    }
    
    /**
     * Clean input data (XSS prevention)
     */
    public function cleanInput($data, $encoding = 'UTF-8')
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->cleanInput($value, $encoding);
            }
            return $data;
        }
        
        if (is_string($data)) {
            // Remove NULL characters
            $data = str_replace(chr(0), '', $data);
            
            // Validate UTF-8
            if (!mb_check_encoding($data, $encoding)) {
                $data = mb_convert_encoding($data, $encoding, $encoding);
            }
            
            // Convert special characters to HTML entities
            $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, $encoding);
        }
        
        return $data;
    }
    
    /**
     * Sanitize filename
     */
    public function sanitizeFilename($filename)
    {
        // Remove any path info
        $filename = basename($filename);
        
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        
        // Remove multiple dots
        $filename = preg_replace('/\.+/', '.', $filename);
        
        // Ensure filename is not empty
        if (empty($filename)) {
            $filename = 'file_' . time();
        }
        
        return $filename;
    }
    
    /**
     * Generate secure random string
     */
    public function generateRandomString($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Hash password
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Validate email
     */
    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate URL
     */
    public function validateUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Generate secure session ID
     */
    public function regenerateSessionId()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
    
    /**
     * Set secure session cookie parameters
     */
    public function setSecureSessionParams()
    {
        $params = [
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ];
        
        session_set_cookie_params($params);
    }
    
    /**
     * Encrypt data
     */
    public function encrypt($data, $key = null)
    {
        if ($key === null) {
            $key = $_ENV['APP_KEY'] ?? 'default_encryption_key_change_this';
        }
        
        $cipher = 'AES-256-CBC';
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        
        return base64_encode($encrypted . '::' . $iv);
    }
    
    /**
     * Decrypt data
     */
    public function decrypt($data, $key = null)
    {
        if ($key === null) {
            $key = $_ENV['APP_KEY'] ?? 'default_encryption_key_change_this';
        }
        
        $cipher = 'AES-256-CBC';
        
        list($encryptedData, $iv) = explode('::', base64_decode($data), 2);
        
        return openssl_decrypt($encryptedData, $cipher, $key, 0, $iv);
    }
    
    /**
     * Rate limiting check
     */
    public function checkRateLimit($identifier, $maxAttempts = 5, $decayMinutes = 1)
    {
        $key = 'rate_limit_' . md5($identifier);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'reset_time' => time() + ($decayMinutes * 60)
            ];
        }
        
        // Reset if decay time has passed
        if (time() > $_SESSION[$key]['reset_time']) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'reset_time' => time() + ($decayMinutes * 60)
            ];
        }
        
        $_SESSION[$key]['attempts']++;
        
        if ($_SESSION[$key]['attempts'] > $maxAttempts) {
            return false; // Rate limit exceeded
        }
        
        return true; // Within rate limit
    }
    
    /**
     * Clear rate limit
     */
    public function clearRateLimit($identifier)
    {
        $key = 'rate_limit_' . md5($identifier);
        unset($_SESSION[$key]);
    }
}

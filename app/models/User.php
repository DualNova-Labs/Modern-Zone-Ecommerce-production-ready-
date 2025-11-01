<?php
/**
 * User Model
 * 
 * Handles user authentication and management
 */
require_once APP_PATH . '/core/Model.php';

class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'company', 
        'address', 'city', 'country', 'postal_code', 
        'role', 'status', 'email_verified_at', 'remember_token'
    ];
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * Hash password before saving
     */
    public function setPassword($password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
    
    /**
     * Authenticate user
     */
    public static function authenticate($email, $password)
    {
        $user = self::findBy('email', $email);
        
        if ($user && $user->verifyPassword($password)) {
            if ($user->status !== 'active') {
                return ['success' => false, 'error' => 'Account is not active'];
            }
            
            // Update last login
            $user->updateLastLogin();
            
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'error' => 'Invalid email or password'];
    }
    
    /**
     * Register new user
     */
    public static function register($data)
    {
        // Check if email already exists
        if (self::findBy('email', $data['email'])) {
            return ['success' => false, 'error' => 'Email already registered'];
        }
        
        // Create new user
        $user = new self();
        $user->fill($data);
        $user->setPassword($data['password']);
        $user->status = 'active';
        $user->role = 'customer';
        
        if ($user->save()) {
            // Send verification email (implement later)
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'error' => 'Failed to create user'];
    }
    
    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->db->query(
            "UPDATE {$this->table} SET last_login = NOW() WHERE id = :id",
            ['id' => $this->id]
        );
    }
    
    /**
     * Generate remember token
     */
    public function generateRememberToken()
    {
        $token = bin2hex(random_bytes(32));
        $this->remember_token = $token;
        $this->save();
        return $token;
    }
    
    /**
     * Find user by remember token
     */
    public static function findByRememberToken($token)
    {
        if (empty($token)) {
            return null;
        }
        
        return self::findBy('remember_token', $token);
    }
    
    /**
     * Clear remember token
     */
    public function clearRememberToken()
    {
        $this->remember_token = null;
        $this->save();
    }
    
    /**
     * Check if user has role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'manager']);
    }
    
    /**
     * Get user's orders
     */
    public function orders()
    {
        return $this->hasMany('Order', 'user_id');
    }
    
    /**
     * Get user's cart items
     */
    public function cartItems()
    {
        return $this->hasMany('CartItem', 'user_id');
    }
    
    /**
     * Get user's reviews
     */
    public function reviews()
    {
        return $this->hasMany('ProductReview', 'user_id');
    }
    
    /**
     * Create password reset token
     */
    public function createPasswordResetToken()
    {
        $token = bin2hex(random_bytes(32));
        
        // Delete any existing tokens for this email
        $this->db->delete('password_resets', 'email = :email', ['email' => $this->email]);
        
        // Insert new token
        $this->db->insert('password_resets', [
            'email' => $this->email,
            'token' => hash('sha256', $token),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $token;
    }
    
    /**
     * Reset password with token
     */
    public static function resetPassword($email, $token, $newPassword)
    {
        $db = Database::getInstance();
        
        // Find valid token
        $hashedToken = hash('sha256', $token);
        $reset = $db->selectOne(
            "SELECT * FROM password_resets 
             WHERE email = :email AND token = :token 
             AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)",
            ['email' => $email, 'token' => $hashedToken]
        );
        
        if (!$reset) {
            return ['success' => false, 'error' => 'Invalid or expired token'];
        }
        
        // Find user and update password
        $user = self::findBy('email', $email);
        if ($user) {
            $user->setPassword($newPassword);
            $user->save();
            
            // Delete used token
            $db->delete('password_resets', 'email = :email', ['email' => $email]);
            
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => 'User not found'];
    }
    
    /**
     * Verify email with token
     */
    public function verifyEmail($token)
    {
        // Implement email verification logic
        if ($this->remember_token === $token && !$this->email_verified_at) {
            $this->email_verified_at = date('Y-m-d H:i:s');
            $this->remember_token = null;
            $this->save();
            return true;
        }
        return false;
    }
    
    /**
     * Get full name
     */
    public function getFullName()
    {
        return $this->name;
    }
    
    /**
     * Get avatar URL
     */
    public function getAvatarUrl()
    {
        // Return gravatar or default avatar
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }
}

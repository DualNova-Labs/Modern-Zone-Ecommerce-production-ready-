<?php
/**
 * Account Controller
 * Handles user account dashboard and profile management
 */
require_once APP_PATH . '/core/Auth.php';
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/Order.php';

class AccountController
{
    private $auth;
    private $security;
    
    public function __construct()
    {
        $this->auth = Auth::getInstance();
        $this->security = Security::getInstance();
        
        // Require authentication for all account pages
        if ($this->auth->guest()) {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . View::url('/login'));
            exit;
        }
    }
    
    /**
     * Account dashboard
     */
    public function dashboard()
    {
        $user = $this->auth->user();
        
        // Get user statistics
        $stats = Order::getUserStatistics($user->id);
        
        // Get recent orders
        $recentOrders = Order::getUserOrders($user->id, 5);
        
        $data = [
            'title' => 'My Account - Modern Zone Trading',
            'description' => 'Manage your account, orders, and preferences',
            'user' => $user,
            'stats' => $stats,
            'recent_orders' => $recentOrders
        ];
        
        View::render('pages/account/dashboard', $data);
    }
    
    /**
     * Profile page
     */
    public function profile()
    {
        $user = $this->auth->user();
        
        $data = [
            'title' => 'My Profile - Modern Zone Trading',
            'description' => 'Update your profile information',
            'user' => $user,
            'csrf_token' => $this->security->getCsrfToken(),
            'success' => $_SESSION['profile_success'] ?? null,
            'errors' => $_SESSION['profile_errors'] ?? []
        ];
        
        unset($_SESSION['profile_success']);
        unset($_SESSION['profile_errors']);
        
        View::render('pages/account/profile', $data);
    }
    
    /**
     * Update profile
     */
    public function updateProfile()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['profile_errors'] = ['general' => 'Invalid security token. Please try again.'];
            header('Location: ' . View::url('/account/profile'));
            exit;
        }
        
        $user = $this->auth->user();
        $errors = [];
        
        // Get and validate input
        $name = $this->security->cleanInput(Request::post('name'));
        $email = $this->security->cleanInput(Request::post('email'));
        $phone = $this->security->cleanInput(Request::post('phone'));
        $company = $this->security->cleanInput(Request::post('company'));
        $address = $this->security->cleanInput(Request::post('address'));
        $city = $this->security->cleanInput(Request::post('city'));
        $country = $this->security->cleanInput(Request::post('country'));
        $postalCode = $this->security->cleanInput(Request::post('postal_code'));
        
        // Validate name
        if (empty($name)) {
            $errors['name'] = 'Name is required';
        } elseif (strlen($name) < 2) {
            $errors['name'] = 'Name must be at least 2 characters';
        }
        
        // Validate email
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!$this->security->validateEmail($email)) {
            $errors['email'] = 'Invalid email format';
        } elseif ($email !== $user->email) {
            // Check if email is already taken
            $existingUser = User::findBy('email', $email);
            if ($existingUser) {
                $errors['email'] = 'Email is already registered';
            }
        }
        
        // If validation fails
        if (!empty($errors)) {
            $_SESSION['profile_errors'] = $errors;
            header('Location: ' . View::url('/account/profile'));
            exit;
        }
        
        // Update user
        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->company = $company;
        $user->address = $address;
        $user->city = $city;
        $user->country = $country;
        $user->postal_code = $postalCode;
        
        if ($user->save()) {
            $_SESSION['profile_success'] = 'Profile updated successfully';
        } else {
            $_SESSION['profile_errors'] = ['general' => 'Failed to update profile. Please try again.'];
        }
        
        header('Location: ' . View::url('/account/profile'));
        exit;
    }
    
    /**
     * Orders page
     */
    public function orders()
    {
        $user = $this->auth->user();
        $page = Request::get('page', 1);
        $perPage = 10;
        
        // Get orders with pagination
        $db = Database::getInstance();
        
        // Get total count
        $totalCount = $db->selectOne(
            "SELECT COUNT(*) as total FROM orders WHERE user_id = :user_id",
            ['user_id' => $user->id]
        );
        $total = $totalCount['total'] ?? 0;
        
        // Get orders
        $offset = ($page - 1) * $perPage;
        $orders = $db->select(
            "SELECT * FROM orders 
             WHERE user_id = :user_id 
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset",
            [
                'user_id' => $user->id,
                'limit' => $perPage,
                'offset' => $offset
            ]
        );
        
        $data = [
            'title' => 'My Orders - Modern Zone Trading',
            'description' => 'View and manage your orders',
            'user' => $user,
            'orders' => $orders,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage)
            ]
        ];
        
        View::render('pages/account/orders', $data);
    }
    
    /**
     * Order details page
     */
    public function orderDetails($orderNumber)
    {
        $user = $this->auth->user();
        $order = Order::findByNumber($orderNumber);
        
        // Verify order belongs to user
        if (!$order || $order->user_id != $user->id) {
            header('Location: ' . View::url('/account/orders'));
            exit;
        }
        
        $data = [
            'title' => "Order #{$orderNumber} - Modern Zone Trading",
            'description' => 'Order details and tracking',
            'user' => $user,
            'order' => $order,
            'items' => $order->getItems()
        ];
        
        View::render('pages/account/order-details', $data);
    }
    
    /**
     * Cancel order
     */
    public function cancelOrder()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token']);
            return;
        }
        
        $user = $this->auth->user();
        $orderNumber = Request::post('order_number');
        
        $order = Order::findByNumber($orderNumber);
        
        // Verify order belongs to user and can be cancelled
        if (!$order || $order->user_id != $user->id) {
            $this->jsonResponse(['success' => false, 'error' => 'Order not found']);
            return;
        }
        
        if (!$order->canBeCancelled()) {
            $this->jsonResponse(['success' => false, 'error' => 'This order cannot be cancelled']);
            return;
        }
        
        if ($order->updateStatus('cancelled')) {
            $this->jsonResponse(['success' => true, 'message' => 'Order cancelled successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to cancel order']);
        }
    }
    
    /**
     * Change password page
     */
    public function changePassword()
    {
        $user = $this->auth->user();
        
        $data = [
            'title' => 'Change Password - Modern Zone Trading',
            'description' => 'Update your account password',
            'user' => $user,
            'csrf_token' => $this->security->getCsrfToken(),
            'success' => $_SESSION['password_success'] ?? null,
            'errors' => $_SESSION['password_errors'] ?? []
        ];
        
        unset($_SESSION['password_success']);
        unset($_SESSION['password_errors']);
        
        View::render('pages/account/change-password', $data);
    }
    
    /**
     * Update password
     */
    public function updatePassword()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['password_errors'] = ['general' => 'Invalid security token. Please try again.'];
            header('Location: ' . View::url('/account/change-password'));
            exit;
        }
        
        $user = $this->auth->user();
        $errors = [];
        
        $currentPassword = Request::post('current_password');
        $newPassword = Request::post('new_password');
        $confirmPassword = Request::post('confirm_password');
        
        // Validate current password
        if (empty($currentPassword)) {
            $errors['current_password'] = 'Current password is required';
        } elseif (!$user->verifyPassword($currentPassword)) {
            $errors['current_password'] = 'Current password is incorrect';
        }
        
        // Validate new password
        if (empty($newPassword)) {
            $errors['new_password'] = 'New password is required';
        } elseif (strlen($newPassword) < 8) {
            $errors['new_password'] = 'Password must be at least 8 characters';
        } elseif (!preg_match('/[A-Z]/', $newPassword)) {
            $errors['new_password'] = 'Password must contain at least one uppercase letter';
        } elseif (!preg_match('/[a-z]/', $newPassword)) {
            $errors['new_password'] = 'Password must contain at least one lowercase letter';
        } elseif (!preg_match('/[0-9]/', $newPassword)) {
            $errors['new_password'] = 'Password must contain at least one number';
        }
        
        // Validate password confirmation
        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
        
        // If validation fails
        if (!empty($errors)) {
            $_SESSION['password_errors'] = $errors;
            header('Location: ' . View::url('/account/change-password'));
            exit;
        }
        
        // Update password
        $user->setPassword($newPassword);
        
        if ($user->save()) {
            $_SESSION['password_success'] = 'Password changed successfully';
        } else {
            $_SESSION['password_errors'] = ['general' => 'Failed to update password. Please try again.'];
        }
        
        header('Location: ' . View::url('/account/change-password'));
        exit;
    }
    
    /**
     * Addresses page
     */
    public function addresses()
    {
        $user = $this->auth->user();
        
        $data = [
            'title' => 'My Addresses - Modern Zone Trading',
            'description' => 'Manage your shipping and billing addresses',
            'user' => $user,
            'csrf_token' => $this->security->getCsrfToken()
        ];
        
        View::render('pages/account/addresses', $data);
    }
    
    /**
     * Send JSON response
     */
    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

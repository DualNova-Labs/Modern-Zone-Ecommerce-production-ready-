<?php
/**
 * Admin Middleware
 * Restricts access to admin-only areas
 */
require_once APP_PATH . '/core/Auth.php';
require_once APP_PATH . '/core/View.php';

class AdminMiddleware
{
    /**
     * Check if user is admin
     */
    public static function check()
    {
        $auth = Auth::getInstance();
        
        // Check if user is logged in
        if ($auth->guest()) {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . View::url('/login'));
            exit;
        }
        
        // Check if user has admin role
        if (!$auth->isAdmin()) {
            header('HTTP/1.0 403 Forbidden');
            View::render('errors/403', [
                'title' => 'Access Denied',
                'message' => 'You do not have permission to access this area.'
            ]);
            exit;
        }
        
        return true;
    }
    
    /**
     * Check for specific permission
     */
    public static function checkPermission($permission)
    {
        $auth = Auth::getInstance();
        $user = $auth->user();
        
        if (!$user) {
            return false;
        }
        
        // Super admin has all permissions
        if ($user->role === 'admin') {
            return true;
        }
        
        // Manager has limited permissions
        if ($user->role === 'manager') {
            $managerPermissions = [
                'view_orders',
                'manage_orders',
                'view_products',
                'manage_products',
                'view_users',
                'view_analytics'
            ];
            
            return in_array($permission, $managerPermissions);
        }
        
        return false;
    }
    
    /**
     * Get admin menu based on role
     */
    public static function getAdminMenu()
    {
        $auth = Auth::getInstance();
        $user = $auth->user();
        
        if (!$user || !$auth->isAdmin()) {
            return [];
        }
        
        $menu = [
            [
                'title' => 'Dashboard',
                'icon' => 'dashboard',
                'url' => '/admin',
                'active' => 'dashboard'
            ],
            [
                'title' => 'Products',
                'icon' => 'package',
                'url' => '/admin/products',
                'active' => 'products',
                'submenu' => [
                    ['title' => 'All Products', 'url' => '/admin/products'],
                    ['title' => 'Add Product', 'url' => '/admin/products/create'],
                    ['title' => 'Categories', 'url' => '/admin/categories'],
                    ['title' => 'Brands', 'url' => '/admin/brands']
                ]
            ],
            [
                'title' => 'Orders',
                'icon' => 'shopping-cart',
                'url' => '/admin/orders',
                'active' => 'orders'
            ],
            [
                'title' => 'Users',
                'icon' => 'users',
                'url' => '/admin/users',
                'active' => 'users',
                'permission' => 'manage_users'
            ],
            [
                'title' => 'Analytics',
                'icon' => 'bar-chart',
                'url' => '/admin/analytics',
                'active' => 'analytics'
            ],
            [
                'title' => 'Settings',
                'icon' => 'settings',
                'url' => '/admin/settings',
                'active' => 'settings',
                'permission' => 'manage_settings'
            ]
        ];
        
        // Filter menu items based on permissions
        return array_filter($menu, function($item) {
            if (isset($item['permission'])) {
                return self::checkPermission($item['permission']);
            }
            return true;
        });
    }
}

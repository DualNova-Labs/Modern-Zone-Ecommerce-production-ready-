<?php
/**
 * Admin Dashboard Controller
 * Handles admin dashboard and analytics
 */
require_once APP_PATH . '/middleware/AdminMiddleware.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/View.php';
require_once APP_PATH . '/models/Order.php';
require_once APP_PATH . '/models/Product.php';
require_once APP_PATH . '/models/User.php';

class AdminDashboardController
{
    public function __construct()
    {
        AdminMiddleware::check();
    }
    
    /**
     * Admin dashboard with analytics
     */
    public function index()
    {
        // Get statistics
        $stats = $this->getStatistics();
        
        // Get recent orders
        $recentOrders = $this->getRecentOrders(10);
        
        // Get low stock products
        $lowStockProducts = $this->getLowStockProducts(10);
        
        // Get sales chart data
        $salesData = $this->getSalesChartData();
        
        // Get top selling products
        $topProducts = $this->getTopSellingProducts(5);
        
        $data = [
            'title' => 'Admin Dashboard - Modern Zone Trading',
            'stats' => $stats,
            'recent_orders' => $recentOrders,
            'low_stock_products' => $lowStockProducts,
            'sales_data' => $salesData,
            'top_products' => $topProducts,
            'admin_menu' => AdminMiddleware::getAdminMenu(),
            'active_menu' => 'dashboard'
        ];
        
        View::render('admin/dashboard/index', $data);
    }
    
    /**
     * Get dashboard statistics
     */
    private function getStatistics()
    {
        $db = Database::getInstance();
        
        // Today's stats
        $today = date('Y-m-d');
        $todayStats = $db->selectOne(
            "SELECT 
                COUNT(*) as orders_today,
                COALESCE(SUM(total_amount), 0) as revenue_today
             FROM orders 
             WHERE DATE(created_at) = :today",
            ['today' => $today]
        );
        
        // This month's stats
        $monthStart = date('Y-m-01');
        $monthStats = $db->selectOne(
            "SELECT 
                COUNT(*) as orders_month,
                COALESCE(SUM(total_amount), 0) as revenue_month
             FROM orders 
             WHERE created_at >= :month_start",
            ['month_start' => $monthStart]
        );
        
        // Total stats
        $totalStats = $db->selectOne(
            "SELECT 
                (SELECT COUNT(*) FROM orders) as total_orders,
                (SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE payment_status = 'paid') as total_revenue,
                (SELECT COUNT(*) FROM users WHERE role = 'customer') as total_customers,
                (SELECT COUNT(*) FROM products WHERE status = 'active') as total_products,
                (SELECT COUNT(*) FROM orders WHERE status = 'pending') as pending_orders,
                (SELECT COUNT(*) FROM products WHERE quantity <= 10) as low_stock_count"
        );
        
        return [
            'today' => [
                'orders' => $todayStats['orders_today'] ?? 0,
                'revenue' => $todayStats['revenue_today'] ?? 0
            ],
            'month' => [
                'orders' => $monthStats['orders_month'] ?? 0,
                'revenue' => $monthStats['revenue_month'] ?? 0
            ],
            'total' => [
                'orders' => $totalStats['total_orders'] ?? 0,
                'revenue' => $totalStats['total_revenue'] ?? 0,
                'customers' => $totalStats['total_customers'] ?? 0,
                'products' => $totalStats['total_products'] ?? 0,
                'pending_orders' => $totalStats['pending_orders'] ?? 0,
                'low_stock' => $totalStats['low_stock_count'] ?? 0
            ]
        ];
    }
    
    /**
     * Get recent orders
     */
    private function getRecentOrders($limit = 10)
    {
        $db = Database::getInstance();
        return $db->select(
            "SELECT o.*, u.name as customer_name, u.email as customer_email
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             ORDER BY o.created_at DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
    }
    
    /**
     * Get low stock products
     */
    private function getLowStockProducts($limit = 10)
    {
        $db = Database::getInstance();
        return $db->select(
            "SELECT p.*, c.name as category_name
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.quantity <= 10 AND p.status = 'active'
             ORDER BY p.quantity ASC
             LIMIT :limit",
            ['limit' => $limit]
        );
    }
    
    /**
     * Get sales chart data for last 30 days
     */
    private function getSalesChartData()
    {
        $db = Database::getInstance();
        $days = 30;
        $data = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $result = $db->selectOne(
                "SELECT 
                    COUNT(*) as orders,
                    COALESCE(SUM(total_amount), 0) as revenue
                 FROM orders 
                 WHERE DATE(created_at) = :date",
                ['date' => $date]
            );
            
            $data[] = [
                'date' => $date,
                'label' => date('M d', strtotime($date)),
                'orders' => $result['orders'] ?? 0,
                'revenue' => $result['revenue'] ?? 0
            ];
        }
        
        return $data;
    }
    
    /**
     * Get top selling products
     */
    private function getTopSellingProducts($limit = 5)
    {
        $db = Database::getInstance();
        return $db->select(
            "SELECT 
                p.id, p.name, p.sku, p.price,
                COUNT(oi.id) as order_count,
                SUM(oi.quantity) as total_sold,
                SUM(oi.total) as total_revenue
             FROM products p
             INNER JOIN order_items oi ON p.id = oi.product_id
             INNER JOIN orders o ON oi.order_id = o.id
             WHERE o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY p.id
             ORDER BY total_sold DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
    }
    
    /**
     * Analytics page with detailed reports
     */
    public function analytics()
    {
        // Limit data for performance
        $limit = 10; // Limit results for faster loading
        
        // Get detailed analytics
        $salesAnalytics = $this->getSalesAnalytics();
        $productAnalytics = $this->getProductAnalytics($limit);
        $customerAnalytics = $this->getCustomerAnalytics($limit);
        
        $data = [
            'title' => 'Analytics - Admin Dashboard',
            'sales' => $salesAnalytics,
            'products' => $productAnalytics,
            'customers' => $customerAnalytics,
            'admin_menu' => AdminMiddleware::getAdminMenu(),
            'active_menu' => 'analytics'
        ];
        
        View::render('admin/analytics/index', $data);
    }
    
    /**
     * Get sales analytics
     */
    private function getSalesAnalytics()
    {
        $db = Database::getInstance();
        
        // Sales by status
        $statusBreakdown = $db->select(
            "SELECT status, COUNT(*) as count, SUM(total_amount) as total
             FROM orders
             GROUP BY status"
        );
        
        // Sales by payment method
        $paymentBreakdown = $db->select(
            "SELECT payment_method, COUNT(*) as count, SUM(total_amount) as total
             FROM orders
             WHERE payment_status = 'paid'
             GROUP BY payment_method"
        );
        
        // Monthly sales for the year
        $monthlySales = $db->select(
            "SELECT 
                MONTH(created_at) as month,
                MONTHNAME(created_at) as month_name,
                COUNT(*) as order_count,
                SUM(total_amount) as revenue
             FROM orders
             WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
             GROUP BY MONTH(created_at)
             ORDER BY month"
        );
        
        return [
            'status_breakdown' => $statusBreakdown,
            'payment_breakdown' => $paymentBreakdown,
            'monthly_sales' => $monthlySales
        ];
    }
    
    /**
     * Get product analytics
     */
    private function getProductAnalytics($limit = 10)
    {
        $db = Database::getInstance();
        
        // Category performance (limited for performance)
        $categoryPerformance = $db->select(
            "SELECT 
                c.name as category,
                COUNT(DISTINCT p.id) as product_count,
                COALESCE(SUM(oi.quantity), 0) as units_sold,
                COALESCE(SUM(oi.total), 0) as revenue
             FROM categories c
             LEFT JOIN products p ON c.id = p.category_id
             LEFT JOIN order_items oi ON p.id = oi.product_id
             GROUP BY c.id
             ORDER BY revenue DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
        
        // Most viewed products
        $mostViewed = $db->select(
            "SELECT name, views
             FROM products
             WHERE status = 'active'
             ORDER BY views DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
        
        return [
            'category_performance' => $categoryPerformance,
            'most_viewed' => $mostViewed
        ];
    }
    
    /**
     * Get customer analytics
     */
    private function getCustomerAnalytics($limit = 10)
    {
        $db = Database::getInstance();
        
        // Top customers (limited for performance)
        $topCustomers = $db->select(
            "SELECT 
                u.id, u.name, u.email,
                COUNT(o.id) as order_count,
                SUM(o.total_amount) as total_spent,
                MAX(o.created_at) as last_order
             FROM users u
             INNER JOIN orders o ON u.id = o.user_id
             WHERE u.role = 'customer'
             GROUP BY u.id
             ORDER BY total_spent DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
        
        // New customers this month
        $newCustomers = $db->selectOne(
            "SELECT COUNT(*) as count
             FROM users
             WHERE role = 'customer' 
             AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
        );
        
        return [
            'top_customers' => $topCustomers,
            'new_customers' => $newCustomers['count'] ?? 0
        ];
    }
}

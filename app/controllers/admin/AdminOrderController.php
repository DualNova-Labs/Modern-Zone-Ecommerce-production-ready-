<?php
/**
 * Admin Order Controller
 * Handles order management
 */
require_once APP_PATH . '/middleware/AdminMiddleware.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/View.php';
require_once APP_PATH . '/core/Request.php';
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/core/Auth.php';
require_once APP_PATH . '/models/Order.php';
require_once APP_PATH . '/models/User.php';

class AdminOrderController
{
    private $security;
    
    public function __construct()
    {
        AdminMiddleware::check();
        $this->security = Security::getInstance();
    }
    
    /**
     * List all orders
     */
    public function index()
    {
        $page = Request::get('page', 1);
        $search = Request::get('search', '');
        $status = Request::get('status');
        $paymentStatus = Request::get('payment_status');
        $dateFrom = Request::get('date_from');
        $dateTo = Request::get('date_to');
        $perPage = 20;
        
        $db = Database::getInstance();
        
        // Build query
        $where = "1=1";
        $params = [];
        
        if ($search) {
            $where .= " AND (o.order_number LIKE :search OR u.name LIKE :name OR u.email LIKE :email)";
            $params['search'] = "%{$search}%";
            $params['name'] = "%{$search}%";
            $params['email'] = "%{$search}%";
        }
        
        if ($status) {
            $where .= " AND o.status = :status";
            $params['status'] = $status;
        }
        
        if ($paymentStatus) {
            $where .= " AND o.payment_status = :payment_status";
            $params['payment_status'] = $paymentStatus;
        }
        
        if ($dateFrom) {
            $where .= " AND DATE(o.created_at) >= :date_from";
            $params['date_from'] = $dateFrom;
        }
        
        if ($dateTo) {
            $where .= " AND DATE(o.created_at) <= :date_to";
            $params['date_to'] = $dateTo;
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total 
                     FROM orders o 
                     LEFT JOIN users u ON o.user_id = u.id 
                     WHERE {$where}";
        $totalResult = $db->selectOne($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        // Get orders
        $offset = ($page - 1) * $perPage;
        $params['limit'] = $perPage;
        $params['offset'] = $offset;
        
        $orders = $db->select(
            "SELECT o.*, u.name as customer_name, u.email as customer_email
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             WHERE {$where}
             ORDER BY o.created_at DESC
             LIMIT :limit OFFSET :offset",
            $params
        );
        
        // Get status counts
        $statusCounts = $db->select(
            "SELECT status, COUNT(*) as count
             FROM orders
             GROUP BY status"
        );
        
        $data = [
            'title' => 'Manage Orders - Admin',
            'orders' => $orders,
            'status_counts' => $statusCounts,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage)
            ],
            'filters' => [
                'search' => $search,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ],
            'admin_menu' => AdminMiddleware::getAdminMenu(),
            'active_menu' => 'orders',
            'success' => $_SESSION['order_success'] ?? null,
            'error' => $_SESSION['order_error'] ?? null
        ];
        
        unset($_SESSION['order_success']);
        unset($_SESSION['order_error']);
        
        View::render('admin/orders/index', $data);
    }
    
    /**
     * View order details
     */
    public function show($id)
    {
        $order = Order::find($id);
        
        if (!$order) {
            $_SESSION['order_error'] = 'Order not found';
            header('Location: ' . View::url('/admin/orders'));
            exit;
        }
        
        // Get customer details
        $customer = User::find($order->user_id);
        
        // Get order items
        $items = $order->getItems();
        
        // Get order history/logs (implement later)
        $history = [];
        
        $data = [
            'title' => "Order #{$order->order_number} - Admin",
            'order' => $order,
            'customer' => $customer,
            'items' => $items,
            'history' => $history,
            'csrf_token' => $this->security->getCsrfToken(),
            'admin_menu' => AdminMiddleware::getAdminMenu(),
            'active_menu' => 'orders'
        ];
        
        View::render('admin/orders/show', $data);
    }
    
    /**
     * Update order status
     */
    public function updateStatus($id)
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token']);
            return;
        }
        
        $order = Order::find($id);
        
        if (!$order) {
            $this->jsonResponse(['success' => false, 'error' => 'Order not found']);
            return;
        }
        
        $status = Request::post('status');
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        
        if (!in_array($status, $validStatuses)) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid status']);
            return;
        }
        
        // Update order status
        if ($order->updateStatus($status)) {
            // Log status change (implement later)
            $this->logOrderActivity($order, "Status changed to {$status}");
            
            // Send notification email (implement later)
            // $this->sendStatusUpdateEmail($order);
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Order status updated successfully',
                'new_status' => $status,
                'badge' => $order->getStatusBadge()
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to update order status']);
        }
    }
    
    /**
     * Update payment status
     */
    public function updatePaymentStatus($id)
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token']);
            return;
        }
        
        $order = Order::find($id);
        
        if (!$order) {
            $this->jsonResponse(['success' => false, 'error' => 'Order not found']);
            return;
        }
        
        $paymentStatus = Request::post('payment_status');
        $paymentMethod = Request::post('payment_method');
        
        if ($order->updatePaymentStatus($paymentStatus, $paymentMethod)) {
            // Log payment update
            $this->logOrderActivity($order, "Payment status changed to {$paymentStatus}");
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Payment status updated successfully',
                'new_status' => $paymentStatus,
                'badge' => $order->getPaymentStatusBadge()
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to update payment status']);
        }
    }
    
    /**
     * Print order invoice
     */
    public function invoice($id)
    {
        $order = Order::find($id);
        
        if (!$order) {
            header('Location: ' . View::url('/admin/orders'));
            exit;
        }
        
        $customer = User::find($order->user_id);
        $items = $order->getItems();
        
        $data = [
            'order' => $order,
            'customer' => $customer,
            'items' => $items
        ];
        
        View::render('admin/orders/invoice', $data);
    }
    
    /**
     * Export orders to CSV
     */
    public function export()
    {
        $status = Request::get('status');
        $dateFrom = Request::get('date_from');
        $dateTo = Request::get('date_to');
        
        $db = Database::getInstance();
        
        // Build query
        $where = "1=1";
        $params = [];
        
        if ($status) {
            $where .= " AND o.status = :status";
            $params['status'] = $status;
        }
        
        if ($dateFrom) {
            $where .= " AND DATE(o.created_at) >= :date_from";
            $params['date_from'] = $dateFrom;
        }
        
        if ($dateTo) {
            $where .= " AND DATE(o.created_at) <= :date_to";
            $params['date_to'] = $dateTo;
        }
        
        $orders = $db->select(
            "SELECT 
                o.order_number,
                o.status,
                o.payment_status,
                o.payment_method,
                o.total_amount,
                o.created_at,
                u.name as customer_name,
                u.email as customer_email,
                o.shipping_name,
                o.shipping_phone,
                o.shipping_address,
                o.shipping_city,
                o.shipping_country
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             WHERE {$where}
             ORDER BY o.created_at DESC",
            $params
        );
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="orders_' . date('Y-m-d') . '.csv"');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Write headers
        fputcsv($output, [
            'Order Number',
            'Customer Name',
            'Customer Email',
            'Status',
            'Payment Status',
            'Payment Method',
            'Total Amount',
            'Shipping Name',
            'Shipping Phone',
            'Shipping Address',
            'Shipping City',
            'Shipping Country',
            'Order Date'
        ]);
        
        // Write data
        foreach ($orders as $order) {
            fputcsv($output, [
                $order['order_number'],
                $order['customer_name'],
                $order['customer_email'],
                $order['status'],
                $order['payment_status'],
                $order['payment_method'],
                $order['total_amount'],
                $order['shipping_name'],
                $order['shipping_phone'],
                $order['shipping_address'],
                $order['shipping_city'],
                $order['shipping_country'],
                $order['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Log order activity
     */
    private function logOrderActivity($order, $activity)
    {
        // Implement order activity logging
        // This would typically save to an order_logs table
        $auth = Auth::getInstance();
        $user = $auth->user();
        
        // Log format: [timestamp] [user] activity
        $log = date('Y-m-d H:i:s') . " [{$user->name}] {$activity}";
        
        // For now, just log to error_log
        error_log("Order #{$order->order_number}: {$log}");
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

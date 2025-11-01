<?php
/**
 * Order Model
 * Handles order processing and management
 */
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/models/Cart.php';
require_once APP_PATH . '/models/Product.php';

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'order_number', 'user_id', 'status', 'payment_status', 'payment_method',
        'subtotal', 'tax_amount', 'shipping_amount', 'discount_amount', 'total_amount',
        'currency', 'shipping_name', 'shipping_email', 'shipping_phone', 'shipping_address',
        'shipping_city', 'shipping_country', 'shipping_postal_code', 'billing_name',
        'billing_email', 'billing_phone', 'billing_address', 'billing_city',
        'billing_country', 'billing_postal_code', 'notes'
    ];
    
    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        
        return "{$prefix}-{$date}-{$random}";
    }
    
    /**
     * Create order from cart
     */
    public function createFromCart($cart, $orderData)
    {
        // Validate cart
        $validation = $cart->validateForCheckout();
        if (!$validation['valid']) {
            return ['success' => false, 'errors' => $validation['errors']];
        }
        
        // Get cart summary
        $summary = $cart->getSummary();
        
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Create order
            $this->fill($orderData);
            $this->order_number = $this->generateOrderNumber();
            $this->subtotal = $summary['subtotal'];
            $this->tax_amount = $summary['tax_amount'];
            $this->shipping_amount = $summary['shipping'];
            $this->total_amount = $summary['total'];
            $this->currency = $summary['currency'];
            $this->status = 'pending';
            $this->payment_status = 'pending';
            
            if (!$this->save()) {
                throw new Exception('Failed to create order');
            }
            
            // Add order items
            $items = $cart->getItems();
            foreach ($items as $item) {
                $orderItem = [
                    'order_id' => $this->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity']
                ];
                
                $this->db->insert('order_items', $orderItem);
                
                // Update product stock
                $product = Product::find($item['product_id']);
                if (!$product->updateStock($item['quantity'], 'decrease')) {
                    throw new Exception("Failed to update stock for product: {$item['name']}");
                }
            }
            
            // Clear cart
            $cart->clear();
            
            // Commit transaction
            $this->db->commit();
            
            // Send order confirmation email (implement later)
            // $this->sendConfirmationEmail();
            
            return ['success' => true, 'order' => $this];
            
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get order items
     */
    public function getItems()
    {
        return $this->db->select(
            "SELECT oi.*, p.slug as product_slug,
                    (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
             FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = :order_id",
            ['order_id' => $this->id]
        );
    }
    
    /**
     * Get order by number
     */
    public static function findByNumber($orderNumber)
    {
        return self::findBy('order_number', $orderNumber);
    }
    
    /**
     * Get user orders
     */
    public static function getUserOrders($userId, $limit = null)
    {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM orders 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $params = ['user_id' => $userId];
        if ($limit) {
            $params['limit'] = $limit;
        }
        
        return $db->select($sql, $params);
    }
    
    /**
     * Update order status
     */
    public function updateStatus($status)
    {
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        
        if (!in_array($status, $validStatuses)) {
            return false;
        }
        
        $this->status = $status;
        
        // Update timestamps
        if ($status === 'shipped') {
            $this->shipped_at = date('Y-m-d H:i:s');
        } elseif ($status === 'delivered') {
            $this->delivered_at = date('Y-m-d H:i:s');
        }
        
        // If cancelled or refunded, restore product stock
        if (in_array($status, ['cancelled', 'refunded'])) {
            $items = $this->getItems();
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->updateStock($item['quantity'], 'increase');
                }
            }
        }
        
        return $this->save();
    }
    
    /**
     * Update payment status
     */
    public function updatePaymentStatus($status, $method = null)
    {
        $validStatuses = ['pending', 'paid', 'failed', 'refunded'];
        
        if (!in_array($status, $validStatuses)) {
            return false;
        }
        
        $this->payment_status = $status;
        
        if ($method) {
            $this->payment_method = $method;
        }
        
        // If payment successful, update order status
        if ($status === 'paid' && $this->status === 'pending') {
            $this->status = 'processing';
        }
        
        return $this->save();
    }
    
    /**
     * Get status badge HTML
     */
    public function getStatusBadge()
    {
        $badges = [
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'processing' => '<span class="badge badge-info">Processing</span>',
            'shipped' => '<span class="badge badge-primary">Shipped</span>',
            'delivered' => '<span class="badge badge-success">Delivered</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            'refunded' => '<span class="badge badge-secondary">Refunded</span>'
        ];
        
        return $badges[$this->status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }
    
    /**
     * Get payment status badge HTML
     */
    public function getPaymentStatusBadge()
    {
        $badges = [
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'paid' => '<span class="badge badge-success">Paid</span>',
            'failed' => '<span class="badge badge-danger">Failed</span>',
            'refunded' => '<span class="badge badge-secondary">Refunded</span>'
        ];
        
        return $badges[$this->payment_status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }
    
    /**
     * Can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }
    
    /**
     * Calculate order statistics for user
     */
    public static function getUserStatistics($userId)
    {
        $db = Database::getInstance();
        
        $stats = $db->selectOne(
            "SELECT 
                COUNT(*) as total_orders,
                COUNT(CASE WHEN status = 'delivered' THEN 1 END) as completed_orders,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_orders,
                SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as total_spent
             FROM orders
             WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        
        return $stats;
    }
}

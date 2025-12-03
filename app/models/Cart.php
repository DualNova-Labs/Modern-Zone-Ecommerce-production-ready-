<?php
/**
 * Cart Model
 * Handles shopping cart operations with session and database persistence
 */
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/models/Product.php';

class Cart extends Model
{
    protected $table = 'cart_items';
    protected $fillable = ['user_id', 'session_id', 'product_id', 'quantity', 'price'];
    
    private static $instance = null;
    private $items = [];
    private $userId = null;
    private $sessionId = null;
    
    private function __construct()
    {
        parent::__construct();
        $this->initializeCart();
    }
    
    /**
     * Get cart instance (Singleton)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize cart from session or database
     */
    private function initializeCart()
    {
        // Get user ID if logged in
        $auth = Auth::getInstance();
        if ($auth->check()) {
            $this->userId = $auth->id();
        }
        
        // Get or create session ID
        $this->sessionId = session_id();
        
        // Load cart items
        $this->loadItems();
    }
    
    /**
     * Load cart items from database
     */
    private function loadItems()
    {
        $where = $this->userId 
            ? 'user_id = :user_id' 
            : 'session_id = :session_id AND user_id IS NULL';
        
        $params = $this->userId 
            ? ['user_id' => $this->userId] 
            : ['session_id' => $this->sessionId];
        
        $items = $this->db->select(
            "SELECT c.*, p.name, p.slug, p.sku, p.price as current_price, 
                    p.quantity as stock, p.status,
                    COALESCE(
                        (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1),
                        p.image
                    ) as image
             FROM {$this->table} c
             JOIN products p ON c.product_id = p.id
             WHERE {$where}
             ORDER BY c.created_at DESC",
            $params
        );
        
        $this->items = [];
        foreach ($items as $item) {
            // Format image path the same way as ProductController
            if (!empty($item['image'])) {
                // If path starts with 'public/', use as-is
                if (strpos($item['image'], 'public/') === 0) {
                    $item['image'] = BASE_URL . '/' . $item['image'];
                }
                // If it's just a filename, prepend the products directory
                else if (strpos($item['image'], '/') === false) {
                    $item['image'] = 'images/products/' . $item['image'];
                }
                // Otherwise use as-is (already has full path)
            }
            
            $this->items[$item['product_id']] = $item;
        }
    }
    
    /**
     * Add item to cart
     */
    public function add($productId, $quantity = 1)
    {
        // Validate product
        $product = Product::find($productId);
        if (!$product || $product->status !== 'active') {
            return ['success' => false, 'error' => 'Product not available'];
        }
        
        // Check stock
        if ($product->quantity < $quantity) {
            return ['success' => false, 'error' => 'Insufficient stock'];
        }
        
        // Check if item already in cart
        if (isset($this->items[$productId])) {
            return $this->updateQuantity($productId, $this->items[$productId]['quantity'] + $quantity);
        }
        
        // Add new item
        $data = [
            'user_id' => $this->userId,
            'session_id' => $this->sessionId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $product->price
        ];
        
        $id = $this->db->insert($this->table, $data);
        
        if ($id) {
            // Reload items
            $this->loadItems();
            return ['success' => true, 'message' => 'Product added to cart'];
        }
        
        return ['success' => false, 'error' => 'Failed to add product to cart'];
    }
    
    /**
     * Update cart item quantity
     */
    public function updateQuantity($productId, $quantity)
    {
        if (!isset($this->items[$productId])) {
            return ['success' => false, 'error' => 'Item not in cart'];
        }
        
        // Check stock
        $product = Product::find($productId);
        if ($product->quantity < $quantity) {
            return ['success' => false, 'error' => 'Insufficient stock'];
        }
        
        if ($quantity <= 0) {
            return $this->remove($productId);
        }
        
        $where = $this->userId 
            ? 'user_id = :user_id AND product_id = :product_id'
            : 'session_id = :session_id AND product_id = :product_id';
        
        $params = $this->userId 
            ? ['user_id' => $this->userId, 'product_id' => $productId]
            : ['session_id' => $this->sessionId, 'product_id' => $productId];
        
        $params['quantity'] = $quantity;
        
        $result = $this->db->update(
            $this->table,
            ['quantity' => ':quantity'],
            $where,
            $params
        );
        
        if ($result) {
            $this->loadItems();
            return ['success' => true, 'message' => 'Cart updated'];
        }
        
        return ['success' => false, 'error' => 'Failed to update cart'];
    }
    
    /**
     * Remove item from cart
     */
    public function remove($productId)
    {
        if (!isset($this->items[$productId])) {
            return ['success' => false, 'error' => 'Item not in cart'];
        }
        
        $where = $this->userId 
            ? 'user_id = :user_id AND product_id = :product_id'
            : 'session_id = :session_id AND product_id = :product_id';
        
        $params = $this->userId 
            ? ['user_id' => $this->userId, 'product_id' => $productId]
            : ['session_id' => $this->sessionId, 'product_id' => $productId];
        
        $this->db->delete($this->table, $where, $params);
        unset($this->items[$productId]);
        return ['success' => true, 'message' => 'Item removed from cart'];
    }
    
    /**
     * Clear cart
     */
    public function clear()
    {
        $where = $this->userId 
            ? 'user_id = :user_id'
            : 'session_id = :session_id';
        
        $params = $this->userId 
            ? ['user_id' => $this->userId]
            : ['session_id' => $this->sessionId];
        
        $this->db->delete($this->table, $where, $params);
        $this->items = [];
        
        return ['success' => true, 'message' => 'Cart cleared'];
    }
    
    /**
     * Get cart items
     */
    public function getItems()
    {
        return array_values($this->items);
    }
    
    /**
     * Get cart item by product ID
     */
    public function getItem($productId)
    {
        return $this->items[$productId] ?? null;
    }
    
    /**
     * Get cart count
     */
    public function getCount()
    {
        return array_sum(array_column($this->items, 'quantity'));
    }
    
    /**
     * Get cart subtotal
     */
    public function getSubtotal()
    {
        $subtotal = 0;
        foreach ($this->items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }
    
    /**
     * Get cart total with tax and shipping
     */
    public function getTotal($taxRate = 0.15, $shippingFee = 0)
    {
        $subtotal = $this->getSubtotal();
        $tax = $subtotal * $taxRate;
        return $subtotal + $tax + $shippingFee;
    }
    
    /**
     * Check if cart is empty
     */
    public function isEmpty()
    {
        return empty($this->items);
    }
    
    /**
     * Merge session cart with user cart on login
     */
    public function mergeWithUserCart($userId)
    {
        if (!$userId) return;
        
        // Get session cart items
        $sessionItems = $this->db->select(
            "SELECT * FROM {$this->table} 
             WHERE session_id = :session_id AND user_id IS NULL",
            ['session_id' => $this->sessionId]
        );
        
        // Update session items to user
        foreach ($sessionItems as $item) {
            // Check if user already has this product in cart
            $existing = $this->db->selectOne(
                "SELECT * FROM {$this->table} 
                 WHERE user_id = :user_id AND product_id = :product_id",
                ['user_id' => $userId, 'product_id' => $item['product_id']]
            );
            
            if ($existing) {
                // Update quantity
                $this->db->update(
                    $this->table,
                    ['quantity' => ':quantity'],
                    'id = :id',
                    [
                        'id' => $existing['id'],
                        'quantity' => $existing['quantity'] + $item['quantity']
                    ]
                );
                // Delete session item
                $this->db->delete($this->table, 'id = :id', ['id' => $item['id']]);
            } else {
                // Update session item to user
                $this->db->update(
                    $this->table,
                    ['user_id' => ':user_id'],
                    'id = :id',
                    ['id' => $item['id'], 'user_id' => $userId]
                );
            }
        }
        
        $this->userId = $userId;
        $this->loadItems();
    }
    
    /**
     * Validate cart items before checkout
     */
    public function validateForCheckout()
    {
        $errors = [];
        
        if ($this->isEmpty()) {
            $errors[] = 'Cart is empty';
            return ['valid' => false, 'errors' => $errors];
        }
        
        foreach ($this->items as $item) {
            // Check product status
            if ($item['status'] !== 'active') {
                $errors[] = "Product '{$item['name']}' is no longer available";
            }
            
            // Check stock
            if ($item['stock'] < $item['quantity']) {
                $errors[] = "Insufficient stock for '{$item['name']}'. Only {$item['stock']} available";
            }
            
            // Check price changes
            if ($item['price'] != $item['current_price']) {
                $this->db->update(
                    $this->table,
                    ['price' => ':price'],
                    'id = :id',
                    ['id' => $item['id'], 'price' => $item['current_price']]
                );
            }
        }
        
        if (!empty($errors)) {
            return ['valid' => false, 'errors' => $errors];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Get cart summary
     */
    public function getSummary()
    {
        $subtotal = $this->getSubtotal();
        $taxRate = 0.15; // 15% VAT
        $tax = $subtotal * $taxRate;
        $shipping = $subtotal > 500 ? 0 : 50; // Free shipping over 500 SAR
        $total = $subtotal + $tax + $shipping;
        
        return [
            'items_count' => $this->getCount(),
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'currency' => 'SAR'
        ];
    }
}

<?php
/**
 * Cart Controller
 * Handles shopping cart operations
 */
require_once APP_PATH . '/models/Cart.php';
require_once APP_PATH . '/models/Product.php';
require_once APP_PATH . '/core/Security.php';

class CartController
{
    private $cart;
    private $security;
    
    public function __construct()
    {
        $this->cart = Cart::getInstance();
        $this->security = Security::getInstance();
    }
    
    /**
     * Display cart page
     */
    public function index()
    {
        $data = [
            'title' => 'Shopping Cart - Modern Zone Trading',
            'description' => 'Review your shopping cart and proceed to checkout',
            'cart' => $this->cart,
            'items' => $this->cart->getItems(),
            'summary' => $this->cart->getSummary(),
            'csrf_token' => $this->security->getCsrfToken()
        ];
        
        View::render('pages/cart/index', $data);
    }
    
    /**
     * Add item to cart (AJAX)
     */
    public function add()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token']);
            return;
        }
        
        $productId = Request::post('product_id');
        $quantity = Request::post('quantity', 1);
        
        if (!$productId) {
            $this->jsonResponse(['success' => false, 'error' => 'Product ID required']);
            return;
        }
        
        $result = $this->cart->add($productId, $quantity);
        
        if ($result['success']) {
            $result['cart_count'] = $this->cart->getCount();
            $result['cart_total'] = number_format($this->cart->getSubtotal(), 2);
        }
        
        $this->jsonResponse($result);
    }
    
    /**
     * Update cart item quantity (AJAX)
     */
    public function update()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token']);
            return;
        }
        
        $productId = Request::post('product_id');
        $quantity = Request::post('quantity');
        
        if (!$productId || $quantity === null) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request']);
            return;
        }
        
        $result = $this->cart->updateQuantity($productId, $quantity);
        
        if ($result['success']) {
            $summary = $this->cart->getSummary();
            $result['cart_count'] = $summary['items_count'];
            $result['subtotal'] = number_format($summary['subtotal'], 2);
            $result['tax'] = number_format($summary['tax_amount'], 2);
            $result['shipping'] = number_format($summary['shipping'], 2);
            $result['total'] = number_format($summary['total'], 2);
            
            // Get updated item total
            $item = $this->cart->getItem($productId);
            if ($item) {
                $result['item_total'] = number_format($item['price'] * $item['quantity'], 2);
            }
        }
        
        $this->jsonResponse($result);
    }
    
    /**
     * Remove item from cart (AJAX)
     */
    public function remove()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token']);
            return;
        }
        
        $productId = Request::post('product_id');
        
        if (!$productId) {
            $this->jsonResponse(['success' => false, 'error' => 'Product ID required']);
            return;
        }
        
        $result = $this->cart->remove($productId);
        
        if ($result['success']) {
            $summary = $this->cart->getSummary();
            $result['cart_count'] = $summary['items_count'];
            $result['subtotal'] = number_format($summary['subtotal'], 2);
            $result['tax'] = number_format($summary['tax_amount'], 2);
            $result['shipping'] = number_format($summary['shipping'], 2);
            $result['total'] = number_format($summary['total'], 2);
            $result['is_empty'] = $this->cart->isEmpty();
        }
        
        $this->jsonResponse($result);
    }
    
    /**
     * Clear cart (AJAX)
     */
    public function clear()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token']);
            return;
        }
        
        $result = $this->cart->clear();
        $result['cart_count'] = 0;
        
        $this->jsonResponse($result);
    }
    
    /**
     * Get cart count (AJAX)
     */
    public function count()
    {
        $this->jsonResponse([
            'count' => $this->cart->getCount(),
            'total' => number_format($this->cart->getSubtotal(), 2)
        ]);
    }
    
    /**
     * Mini cart view (AJAX)
     */
    public function mini()
    {
        $data = [
            'items' => $this->cart->getItems(),
            'summary' => $this->cart->getSummary()
        ];
        
        ob_start();
        View::render('components/mini-cart', $data);
        $html = ob_get_clean();
        
        $this->jsonResponse([
            'html' => $html,
            'count' => $data['summary']['items_count']
        ]);
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

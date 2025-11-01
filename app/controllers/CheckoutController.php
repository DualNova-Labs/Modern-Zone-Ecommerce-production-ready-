<?php
/**
 * Checkout Controller
 * Handles checkout process and order placement
 */
require_once APP_PATH . '/core/Auth.php';
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/models/Cart.php';
require_once APP_PATH . '/models/Order.php';
require_once APP_PATH . '/models/User.php';

class CheckoutController
{
    private $auth;
    private $cart;
    private $security;
    
    public function __construct()
    {
        $this->auth = Auth::getInstance();
        $this->cart = Cart::getInstance();
        $this->security = Security::getInstance();
        
        // Require login for checkout
        if ($this->auth->guest()) {
            $_SESSION['intended_url'] = View::url('/checkout');
            header('Location: ' . View::url('/login'));
            exit;
        }
    }
    
    /**
     * Display checkout page
     */
    public function index()
    {
        // Check if cart is empty
        if ($this->cart->isEmpty()) {
            $_SESSION['cart_error'] = 'Your cart is empty';
            header('Location: ' . View::url('/cart'));
            exit;
        }
        
        // Validate cart items
        $validation = $this->cart->validateForCheckout();
        if (!$validation['valid']) {
            $_SESSION['cart_errors'] = $validation['errors'];
            header('Location: ' . View::url('/cart'));
            exit;
        }
        
        $user = $this->auth->user();
        
        $data = [
            'title' => 'Checkout - Modern Zone Trading',
            'description' => 'Complete your order',
            'user' => $user,
            'cart' => $this->cart,
            'items' => $this->cart->getItems(),
            'summary' => $this->cart->getSummary(),
            'csrf_token' => $this->security->getCsrfToken(),
            'countries' => $this->getCountries(),
            'payment_methods' => $this->getPaymentMethods()
        ];
        
        View::render('pages/checkout/index', $data);
    }
    
    /**
     * Process checkout
     */
    public function process()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['checkout_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/checkout'));
            exit;
        }
        
        // Check if cart is empty
        if ($this->cart->isEmpty()) {
            $_SESSION['cart_error'] = 'Your cart is empty';
            header('Location: ' . View::url('/cart'));
            exit;
        }
        
        // Validate and sanitize input
        $data = $this->validateCheckoutData();
        
        if (!empty($data['errors'])) {
            $_SESSION['checkout_errors'] = $data['errors'];
            $_SESSION['checkout_old'] = Request::all();
            header('Location: ' . View::url('/checkout'));
            exit;
        }
        
        // Create order
        $order = new Order();
        $result = $order->createFromCart($this->cart, $data['order_data']);
        
        if ($result['success']) {
            // Store order in session for confirmation page
            $_SESSION['last_order_id'] = $result['order']->id;
            
            // Redirect based on payment method
            if ($data['order_data']['payment_method'] === 'online') {
                // Redirect to payment gateway (implement later)
                header('Location: ' . View::url('/checkout/payment/' . $result['order']->order_number));
            } else {
                // Cash on delivery - mark as pending payment
                header('Location: ' . View::url('/checkout/success/' . $result['order']->order_number));
            }
            exit;
        } else {
            $_SESSION['checkout_error'] = $result['error'] ?? 'Failed to create order. Please try again.';
            header('Location: ' . View::url('/checkout'));
            exit;
        }
    }
    
    /**
     * Order success page
     */
    public function success($orderNumber)
    {
        $order = Order::findByNumber($orderNumber);
        
        if (!$order || $order->user_id != $this->auth->id()) {
            header('Location: ' . View::url('/'));
            exit;
        }
        
        $data = [
            'title' => 'Order Successful - Modern Zone Trading',
            'description' => 'Your order has been placed successfully',
            'order' => $order,
            'items' => $order->getItems()
        ];
        
        // Clear session
        unset($_SESSION['last_order_id']);
        
        View::render('pages/checkout/success', $data);
    }
    
    /**
     * Validate checkout data
     */
    private function validateCheckoutData()
    {
        $errors = [];
        $user = $this->auth->user();
        
        // Get form data
        $shippingName = $this->security->cleanInput(Request::post('shipping_name'));
        $shippingEmail = $this->security->cleanInput(Request::post('shipping_email'));
        $shippingPhone = $this->security->cleanInput(Request::post('shipping_phone'));
        $shippingAddress = $this->security->cleanInput(Request::post('shipping_address'));
        $shippingCity = $this->security->cleanInput(Request::post('shipping_city'));
        $shippingCountry = $this->security->cleanInput(Request::post('shipping_country'));
        $shippingPostalCode = $this->security->cleanInput(Request::post('shipping_postal_code'));
        
        $sameAsBilling = Request::post('same_as_billing') === 'on';
        $paymentMethod = Request::post('payment_method');
        $notes = $this->security->cleanInput(Request::post('notes'));
        
        // Validate shipping info
        if (empty($shippingName)) {
            $errors['shipping_name'] = 'Name is required';
        }
        
        if (empty($shippingEmail)) {
            $errors['shipping_email'] = 'Email is required';
        } elseif (!$this->security->validateEmail($shippingEmail)) {
            $errors['shipping_email'] = 'Invalid email format';
        }
        
        if (empty($shippingPhone)) {
            $errors['shipping_phone'] = 'Phone is required';
        }
        
        if (empty($shippingAddress)) {
            $errors['shipping_address'] = 'Address is required';
        }
        
        if (empty($shippingCity)) {
            $errors['shipping_city'] = 'City is required';
        }
        
        if (empty($shippingCountry)) {
            $errors['shipping_country'] = 'Country is required';
        }
        
        // Validate payment method
        if (!in_array($paymentMethod, ['cod', 'online'])) {
            $errors['payment_method'] = 'Invalid payment method';
        }
        
        // Prepare order data
        $orderData = [
            'user_id' => $user->id,
            'shipping_name' => $shippingName,
            'shipping_email' => $shippingEmail,
            'shipping_phone' => $shippingPhone,
            'shipping_address' => $shippingAddress,
            'shipping_city' => $shippingCity,
            'shipping_country' => $shippingCountry,
            'shipping_postal_code' => $shippingPostalCode,
            'payment_method' => $paymentMethod,
            'notes' => $notes
        ];
        
        // Set billing info
        if ($sameAsBilling) {
            $orderData['billing_name'] = $shippingName;
            $orderData['billing_email'] = $shippingEmail;
            $orderData['billing_phone'] = $shippingPhone;
            $orderData['billing_address'] = $shippingAddress;
            $orderData['billing_city'] = $shippingCity;
            $orderData['billing_country'] = $shippingCountry;
            $orderData['billing_postal_code'] = $shippingPostalCode;
        } else {
            // Get billing info
            $billingName = $this->security->cleanInput(Request::post('billing_name'));
            $billingEmail = $this->security->cleanInput(Request::post('billing_email'));
            $billingPhone = $this->security->cleanInput(Request::post('billing_phone'));
            $billingAddress = $this->security->cleanInput(Request::post('billing_address'));
            $billingCity = $this->security->cleanInput(Request::post('billing_city'));
            $billingCountry = $this->security->cleanInput(Request::post('billing_country'));
            $billingPostalCode = $this->security->cleanInput(Request::post('billing_postal_code'));
            
            // Validate billing info
            if (empty($billingName)) {
                $errors['billing_name'] = 'Billing name is required';
            }
            
            if (empty($billingEmail)) {
                $errors['billing_email'] = 'Billing email is required';
            } elseif (!$this->security->validateEmail($billingEmail)) {
                $errors['billing_email'] = 'Invalid billing email format';
            }
            
            $orderData['billing_name'] = $billingName;
            $orderData['billing_email'] = $billingEmail;
            $orderData['billing_phone'] = $billingPhone;
            $orderData['billing_address'] = $billingAddress;
            $orderData['billing_city'] = $billingCity;
            $orderData['billing_country'] = $billingCountry;
            $orderData['billing_postal_code'] = $billingPostalCode;
        }
        
        return [
            'errors' => $errors,
            'order_data' => $orderData
        ];
    }
    
    /**
     * Get available countries
     */
    private function getCountries()
    {
        return [
            'Saudi Arabia' => 'Saudi Arabia',
            'United Arab Emirates' => 'United Arab Emirates',
            'Kuwait' => 'Kuwait',
            'Qatar' => 'Qatar',
            'Bahrain' => 'Bahrain',
            'Oman' => 'Oman',
            'Egypt' => 'Egypt',
            'Jordan' => 'Jordan',
            'Lebanon' => 'Lebanon'
        ];
    }
    
    /**
     * Get available payment methods
     */
    private function getPaymentMethods()
    {
        return [
            'cod' => [
                'name' => 'Cash on Delivery',
                'description' => 'Pay when you receive your order',
                'icon' => 'truck'
            ],
            'online' => [
                'name' => 'Online Payment',
                'description' => 'Pay securely with credit/debit card',
                'icon' => 'credit-card'
            ]
        ];
    }
}

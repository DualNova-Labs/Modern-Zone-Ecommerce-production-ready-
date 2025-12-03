<?php
ob_start();
?>

<!-- Breadcrumb -->
<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="<?= View::url('/') ?>">Home</a></li>
            <li>Shopping Cart</li>
        </ul>
    </div>
</section>

<!-- Shopping Cart -->
<section class="cart-page">
    <div class="container">
        <?php if (empty($items)): ?>
            <!-- Empty Cart -->
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>Your Cart is Empty</h2>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="<?= View::url('/products') ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i>
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <!-- Cart Content -->
            <div class="cart-layout">
                <!-- Cart Items -->
                <div class="cart-items">
                    <h2 class="cart-title">Shopping Cart (<?= $summary['items_count'] ?> items)</h2>
                    
                    <div class="cart-items-list">
                        <?php foreach ($items as $item): ?>
                            <div class="cart-item" data-product-id="<?= $item['product_id'] ?>">
                                <div class="cart-item-image">
                                    <?php
                                    // Determine image source with multiple fallbacks
                                    $imageSrc = View::asset('images/placeholder.png'); // Default placeholder
                                    
                                    if (!empty($item['image'])) {
                                        // If path starts with / or http, it's already absolute - use as-is
                                        if (strpos($item['image'], '/') === 0 || strpos($item['image'], 'http') === 0) {
                                            $imageSrc = $item['image'];
                                        }
                                        // Otherwise prepend base URL
                                        else {
                                            $imageSrc = View::asset($item['image']);
                                        }
                                    }
                                    ?>
                                    <img src="<?= $imageSrc ?>" 
                                         alt="<?= htmlspecialchars($item['name']) ?>" 
                                         loading="lazy"
                                         onerror="this.src='<?= View::asset('images/placeholder.png') ?>'">
                                </div>
                                
                                <div class="cart-item-details">
                                    <h3 class="cart-item-title">
                                        <a href="<?= View::url('/products/' . ($item['slug'] ?? '#')) ?>">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </a>
                                    </h3>
                                    
                                    <?php if (!empty($item['sku'])): ?>
                                        <p class="cart-item-sku">SKU: <?= htmlspecialchars($item['sku']) ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="cart-item-price">
                                        <span class="price-label">Price:</span>
                                        <span class="price-value"><?= number_format($item['price'], 2) ?> SAR</span>
                                    </div>
                                    
                                    <?php if (isset($item['stock']) && $item['stock'] < 5): ?>
                                        <p class="cart-item-stock-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Only <?= $item['stock'] ?> left in stock
                                        </p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="cart-item-actions">
                                    <div class="quantity-selector">
                                        <button class="qty-btn qty-minus" data-product-id="<?= $item['product_id'] ?>">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               class="qty-input" 
                                               value="<?= $item['quantity'] ?>" 
                                               min="1" 
                                               max="<?= $item['stock'] ?? 999 ?>"
                                               data-product-id="<?= $item['product_id'] ?>">
                                        <button class="qty-btn qty-plus" data-product-id="<?= $item['product_id'] ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="cart-item-total">
                                        <span class="total-label">Total:</span>
                                        <span class="total-value" data-product-id="<?= $item['product_id'] ?>">
                                            <?= number_format($item['price'] * $item['quantity'], 2) ?> SAR
                                        </span>
                                    </div>
                                    
                                    <button class="cart-item-remove" 
                                            data-product-id="<?= $item['product_id'] ?>"
                                            title="Remove item">
                                        <i class="fas fa-trash-alt"></i>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Cart Actions -->
                    <div class="cart-actions">
                        <a href="<?= View::url('/products') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Continue Shopping
                        </a>
                        <button class="btn btn-outline-danger" id="clearCart">
                            <i class="fas fa-trash"></i>
                            Clear Cart
                        </button>
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3 class="summary-title">Order Summary</h3>
                    
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Subtotal (<?= $summary['items_count'] ?> items):</span>
                            <span class="summary-subtotal"><?= number_format($summary['subtotal'], 2) ?> SAR</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Tax (<?= $summary['tax_rate'] ?>%):</span>
                            <span class="summary-tax"><?= number_format($summary['tax_amount'], 2) ?> SAR</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span class="summary-shipping">
                                <?php if ($summary['shipping'] > 0): ?>
                                    <?= number_format($summary['shipping'], 2) ?> SAR
                                <?php else: ?>
                                    <span class="text-success">FREE</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <?php if ($summary['subtotal'] < 500 && $summary['shipping'] > 0): ?>
                            <div class="summary-note">
                                <i class="fas fa-info-circle"></i>
                                Add <?= number_format(500 - $summary['subtotal'], 2) ?> SAR more for free shipping!
                            </div>
                        <?php endif; ?>
                        
                        <div class="summary-divider"></div>
                        
                        <div class="summary-row summary-total">
                            <span>Total:</span>
                            <span class="summary-total-value"><?= number_format($summary['total'], 2) ?> SAR</span>
                        </div>
                    </div>
                    
                    <!-- Checkout Button -->
                    <a href="<?= View::url('/checkout') ?>" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-lock"></i>
                        Proceed to Checkout
                    </a>
                    
                    <!-- Security Badges -->
                    <div class="security-badges">
                        <div class="badge-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure Checkout</span>
                        </div>
                        <div class="badge-item">
                            <i class="fas fa-truck"></i>
                            <span>Fast Delivery</span>
                        </div>
                        <div class="badge-item">
                            <i class="fas fa-undo"></i>
                            <span>Easy Returns</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Cart JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = '<?= $csrf_token ?>';
    
    // Update quantity
    document.querySelectorAll('.qty-minus, .qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const input = document.querySelector(`.qty-input[data-product-id="${productId}"]`);
            const currentQty = parseInt(input.value);
            const maxQty = parseInt(input.max);
            
            let newQty = currentQty;
            if (this.classList.contains('qty-minus') && currentQty > 1) {
                newQty = currentQty - 1;
            } else if (this.classList.contains('qty-plus') && currentQty < maxQty) {
                newQty = currentQty + 1;
            }
            
            if (newQty !== currentQty) {
                input.value = newQty;
                updateCartItem(productId, newQty);
            }
        });
    });
    
    // Direct quantity input
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            let qty = parseInt(this.value);
            const maxQty = parseInt(this.max);
            
            if (qty < 1) qty = 1;
            if (qty > maxQty) qty = maxQty;
            
            this.value = qty;
            updateCartItem(productId, qty);
        });
    });
    
    // Remove item
    document.querySelectorAll('.cart-item-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                const productId = this.dataset.productId;
                removeCartItem(productId);
            }
        });
    });
    
    // Clear cart
    const clearCartBtn = document.getElementById('clearCart');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your entire cart?')) {
                clearCart();
            }
        });
    }
    
    // Update cart item
    function updateCartItem(productId, quantity) {
        fetch('<?= View::url('/cart/update') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}&csrf_token=${csrfToken}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update item total
                const itemTotal = document.querySelector(`.total-value[data-product-id="${productId}"]`);
                if (itemTotal) {
                    itemTotal.textContent = data.item_total + ' SAR';
                }
                
                // Update summary
                updateSummary(data);
            } else {
                alert(data.error || 'Failed to update cart');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    
    // Remove cart item
    function removeCartItem(productId) {
        fetch('<?= View::url('/cart/remove') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&csrf_token=${csrfToken}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove item from DOM
                const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
                if (cartItem) {
                    cartItem.remove();
                }
                
                // Check if cart is empty
                if (data.is_empty) {
                    location.reload();
                } else {
                    updateSummary(data);
                }
            } else {
                alert(data.error || 'Failed to remove item');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    
    // Clear cart
    function clearCart() {
        fetch('<?= View::url('/cart/clear') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `csrf_token=${csrfToken}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Failed to clear cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    
    // Update summary
    function updateSummary(data) {
        document.querySelector('.cart-title').textContent = `Shopping Cart (${data.cart_count} items)`;
        document.querySelector('.summary-subtotal').textContent = data.subtotal + ' SAR';
        document.querySelector('.summary-tax').textContent = data.tax + ' SAR';
        document.querySelector('.summary-shipping').innerHTML = data.shipping === '0.00' 
            ? '<span class="text-success">FREE</span>' 
            : data.shipping + ' SAR';
        document.querySelector('.summary-total-value').textContent = data.total + ' SAR';
        
        // Update items count in summary
        const summaryRows = document.querySelectorAll('.summary-row');
        if (summaryRows[0]) {
            summaryRows[0].querySelector('span').textContent = `Subtotal (${data.cart_count} items):`;
        }
    }
    });
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

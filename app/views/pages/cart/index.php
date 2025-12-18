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
                        
                        <?php if (($summary['subtotal'] + $summary['tax_amount']) < 100 && $summary['shipping'] > 0): ?>
                            <div class="summary-note">
                                <i class="fas fa-info-circle"></i>
                                Add <?= number_format(100 - ($summary['subtotal'] + $summary['tax_amount']), 2) ?> SAR more for free shipping!
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
                    
                    <!-- Request a Quote Button -->
                    <button type="button" class="btn btn-lg btn-block quote-request-btn" style="margin-top: 1rem;" onclick="openQuoteModal()">
                        <i class="fas fa-file-invoice"></i>
                        Request a Quote
                    </button>
                    
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

<!-- Request a Quote Modal -->
<div id="quoteModal" class="quote-modal" style="display: none;">
    <div class="quote-modal-overlay" onclick="closeQuoteModal()"></div>
    <div class="quote-modal-content">
        <div class="quote-modal-header">
            <h3>
                <i class="fas fa-file-invoice"></i>
                Request a Quote
            </h3>
            <button class="quote-modal-close" onclick="closeQuoteModal()">&times;</button>
        </div>
        
        <form id="quoteForm" onsubmit="submitQuoteRequest(event)">
            <div class="quote-modal-body">
                <p class="quote-modal-description">
                    Fill in your details below and we'll send you a customized quote for your cart items.
                </p>
                
                <!-- Selected Items Summary -->
                <div class="quote-items-summary">
                    <h4><i class="fas fa-shopping-cart"></i> Items in Quote Request</h4>
                    <p class="quote-items-count"><?= $summary['items_count'] ?? 0 ?> item(s) - Total: <?= number_format($summary['total'] ?? 0, 2) ?> SAR</p>
                </div>
                
                <!-- Customer Details Form -->
                <div class="quote-form-grid">
                    <div class="quote-form-group">
                        <label for="quote_name" class="quote-form-label">
                            Full Name <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="quote_name" 
                               name="name" 
                               class="quote-form-input" 
                               required 
                               placeholder="Enter your full name">
                    </div>
                    
                    <div class="quote-form-group">
                        <label for="quote_email" class="quote-form-label">
                            Email Address <span class="required">*</span>
                        </label>
                        <input type="email" 
                               id="quote_email" 
                               name="email" 
                               class="quote-form-input" 
                               required 
                               placeholder="your.email@example.com">
                    </div>
                    
                    <div class="quote-form-group">
                        <label for="quote_phone" class="quote-form-label">
                            Phone Number <span class="required">*</span>
                        </label>
                        <input type="tel" 
                               id="quote_phone" 
                               name="phone" 
                               class="quote-form-input" 
                               required 
                               placeholder="+966 XX XXX XXXX">
                    </div>
                    
                    <div class="quote-form-group">
                        <label for="quote_company" class="quote-form-label">
                            Company Name
                        </label>
                        <input type="text" 
                               id="quote_company" 
                               name="company" 
                               class="quote-form-input" 
                               placeholder="Your company name (optional)">
                    </div>
                </div>
                
                <div class="quote-form-group">
                    <label for="quote_requirements" class="quote-form-label">
                        Additional Requirements/Message
                    </label>
                    <textarea id="quote_requirements" 
                              name="requirements" 
                              class="quote-form-textarea" 
                              rows="4" 
                              placeholder="Please provide any specific requirements, quantities, or questions about your quote..."></textarea>
                </div>
                
                <div class="quote-form-group">
                    <label class="quote-form-checkbox">
                        <input type="checkbox" name="urgent" value="1">
                        <span>This is an urgent request</span>
                    </label>
                </div>
            </div>
            
            <div class="quote-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeQuoteModal()">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Submit Quote Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quote Modal Styles -->
<style>
.quote-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.quote-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
}

.quote-modal-content {
    position: relative;
    background: white;
    border-radius: 16px;
    max-width: 700px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.quote-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem 2rem;
    border-bottom: 2px solid #f1f5f9;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px 16px 0 0;
}

.quote-modal-header h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.quote-modal-header h3 i {
    color: #6366f1;
}

.quote-modal-close {
    background: transparent;
    border: none;
    font-size: 2rem;
    color: #64748b;
    cursor: pointer;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.quote-modal-close:hover {
    background: #f1f5f9;
    color: #1e293b;
    transform: rotate(90deg);
}

.quote-modal-body {
    padding: 2rem;
}

.quote-modal-description {
    font-size: 0.9375rem;
    color: #64748b;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.quote-items-summary {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-left: 4px solid #3b82f6;
    padding: 1rem 1.25rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.quote-items-summary h4 {
    margin: 0 0 0.5rem 0;
    font-size: 0.9375rem;
    font-weight: 600;
    color: #1e40af;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quote-items-count {
    margin: 0;
    font-size: 0.875rem;
    color: #1e40af;
}

.quote-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

.quote-form-group {
    margin-bottom: 1.25rem;
}

.quote-form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
}

.quote-form-label .required {
    color: #ef4444;
}

.quote-form-input,
.quote-form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9375rem;
    font-family: inherit;
    transition: all 0.3s;
}

.quote-form-input:focus,
.quote-form-textarea:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.quote-form-textarea {
    resize: vertical;
    min-height: 100px;
}

.quote-form-checkbox {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9375rem;
    color: #334155;
    cursor: pointer;
}

.quote-form-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.quote-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding: 1.5rem 2rem;
    border-top: 2px solid #f1f5f9;
    background: #f8fafc;
    border-radius: 0 0 16px 16px;
}

.quote-modal-footer .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s;
}

.quote-modal-footer .btn-secondary {
    background: #f1f5f9;
    color: #64748b;
    border: none;
}

.quote-modal-footer .btn-secondary:hover {
    background: #e2e8f0;
    color: #475569;
}

.quote-modal-footer .btn-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    border: none;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
}

.quote-modal-footer .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(99, 102, 241, 0.4);
}

@media (max-width: 768px) {
    .quote-form-grid {
        grid-template-columns: 1fr;
    }
    
    .quote-modal-content {
        max-height: 95vh;
    }
    
    .quote-modal-header,
    .quote-modal-body,
    .quote-modal-footer {
        padding: 1.25rem;
    }
    
    .quote-modal-footer {
        flex-direction: column;
    }
    
    .quote-modal-footer .btn {
        width: 100%;
    }
}

/* Request a Quote Button Styling */
.quote-request-btn {
    background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 50%, #3b82f6 100%) !important;
    border: none !important;
    color: white !important;
    font-weight: 600 !important;
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4) !important;
    transition: all 0.3s ease !important;
    position: relative;
    overflow: hidden;
}

.quote-request-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
}

.quote-request-btn:hover::before {
    left: 100%;
}

.quote-request-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 25px rgba(139, 92, 246, 0.6) !important;
    background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 50%, #2563eb 100%) !important;
}

.quote-request-btn:active {
    transform: translateY(0) !important;
    box-shadow: 0 2px 10px rgba(139, 92, 246, 0.4) !important;
}

.quote-request-btn i {
    margin-right: 0.5rem;
    font-size: 1.1rem;
}
</style>

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

// Quote Modal Functions
function openQuoteModal() {
    const modal = document.getElementById('quoteModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeQuoteModal() {
    const modal = document.getElementById('quoteModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        document.getElementById('quoteForm').reset();
    }
}

// Close modal when pressing Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeQuoteModal();
    }
});

// Submit Quote Request
function submitQuoteRequest(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    
    // Collect form data
    const formData = new FormData(form);
    formData.append('csrf_token', '<?= $csrf_token ?>');
    formData.append('cart_items', '<?= $summary['items_count'] ?? 0 ?>');
    formData.append('cart_total', '<?= $summary['total'] ?? 0 ?>');
    
    // Send quote request
    fetch('<?= View::url('/quote/submit') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('✅ Quote request submitted successfully! We\'ll contact you soon.');
            closeQuoteModal();
            form.reset();
        } else {
            alert('❌ ' + (data.message || 'Failed to submit quote request. Please try again.'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ An error occurred. Please try again or contact us directly.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

<?php
/**
 * Mini Cart Component
 * Displays cart preview dropdown in header
 */
?>

<div class="mini-cart-dropdown" id="miniCartDropdown">
    <div class="mini-cart-header">
        <h3 class="mini-cart-title">
            <i class="fas fa-shopping-cart"></i>
            Shopping Cart
        </h3>
        <div class="mini-cart-header-actions">
            <span class="mini-cart-count"><?= $summary['items_count'] ?? 0 ?> Item<?= ($summary['items_count'] ?? 0) != 1 ? 's' : '' ?></span>
            <button class="mini-cart-close" aria-label="Close cart">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <?php if (empty($items)): ?>
        <!-- Empty Cart State -->
        <div class="mini-cart-empty">
            <i class="fas fa-shopping-cart"></i>
            <p>Your cart is empty</p>
            <a href="<?= View::url('/products') ?>" class="btn btn-primary btn-sm">
                Start Shopping
            </a>
        </div>
    <?php else: ?>
        <!-- Cart Items -->
        <div class="mini-cart-items">
            <?php foreach ($items as $item): ?>
                <div class="mini-cart-item" data-product-id="<?= $item['product_id'] ?>">
                    <div class="mini-cart-item-image">
                        <?php
                        $imageSrc = View::asset('images/placeholder.png');
                        if (!empty($item['image'])) {
                            if (strpos($item['image'], '/') === 0 || strpos($item['image'], 'http') === 0) {
                                $imageSrc = $item['image'];
                            } else {
                                $imageSrc = View::asset($item['image']);
                            }
                        }
                        ?>
                        <img src="<?= $imageSrc ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>"
                             onerror="this.src='<?= View::asset('images/placeholder.png') ?>'">
                    </div>
                    
                    <div class="mini-cart-item-details">
                        <h4 class="mini-cart-item-name">
                            <a href="<?= View::url('/products/' . ($item['slug'] ?? '#')) ?>">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        </h4>
                        <div class="mini-cart-item-meta">
                            <span class="mini-cart-item-qty"><?= $item['quantity'] ?> x</span>
                            <span class="mini-cart-item-price"><?= number_format($item['price'], 2) ?> SAR</span>
                        </div>
                    </div>
                    
                    <button class="mini-cart-item-remove" 
                            onclick="removeFromMiniCart(<?= $item['product_id'] ?>)"
                            title="Remove item">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Cart Summary -->
        <div class="mini-cart-footer">
            <div class="mini-cart-subtotal">
                <span>Subtotal:</span>
                <span class="mini-cart-total-amount"><?= number_format($summary['subtotal'] ?? 0, 2) ?> SAR</span>
            </div>
            
            <?php if (($summary['subtotal'] ?? 0) < 500): ?>
                <div class="mini-cart-shipping-notice">
                    <i class="fas fa-truck"></i>
                    <small>Add <?= number_format(500 - ($summary['subtotal'] ?? 0), 2) ?> SAR more for free shipping!</small>
                </div>
            <?php else: ?>
                <div class="mini-cart-shipping-notice free">
                    <i class="fas fa-check-circle"></i>
                    <small>You qualify for free shipping! 🎉</small>
                </div>
            <?php endif; ?>
            
            <div class="mini-cart-actions">
                <a href="<?= View::url('/cart') ?>" class="btn btn-secondary btn-block">
                    <i class="fas fa-shopping-cart"></i>
                    View Cart
                </a>
                <a href="<?= View::url('/checkout') ?>" class="btn btn-primary btn-block">
                    <i class="fas fa-lock"></i>
                    Checkout
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

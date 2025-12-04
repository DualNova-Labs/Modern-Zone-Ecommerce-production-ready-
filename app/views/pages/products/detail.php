<?php
ob_start();
?>

<!-- Breadcrumb -->
<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="<?= View::url('/') ?>">Home</a></li>
            <li><a href="<?= View::url('/products') ?>">Products</a></li>
            <li><?= htmlspecialchars($product['title']) ?></li>
        </ul>
    </div>
</section>

<!-- Product Detail -->
<section class="product-detail">
    <div class="container">
        <div class="product-detail-grid">
            <!-- Product Gallery -->
            <div class="product-gallery">
                <div class="gallery-main">
                    <button class="gallery-action" title="Add to Wishlist">
                        <i class="far fa-heart"></i>
                    </button>
                    <img src="<?= $product['images'][0] ?>" alt="<?= htmlspecialchars($product['title']) ?>" id="mainImage">
                </div>
                <?php if (count($product['images']) > 1): ?>
                <div class="gallery-thumbs">
                    <?php foreach ($product['images'] as $index => $image): ?>
                        <button class="gallery-thumb <?= ($index === 0) ? 'active' : '' ?>" 
                                data-image="<?= $image ?>">
                            <img src="<?= $image ?>" alt="Product image <?= $index + 1 ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Product Info -->
            <div class="product-info">
                <!-- Product Category Badge -->
                <?php if (!empty($product['category'])): ?>
                <div class="product-category-badge">
                    <span><?= htmlspecialchars($product['category']) ?></span>
                </div>
                <?php endif; ?>
                
                <h1 class="product-title"><?= htmlspecialchars($product['title']) ?></h1>
                
                <!-- SKU & Stock Info -->
                <div class="product-sku-stock">
                    <?php if (!empty($product['sku'])): ?>
                    <span class="sku">SKU: <?= htmlspecialchars($product['sku']) ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="product-price-section">
                    <?php if (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                    <span class="product-price-old"><?= number_format($product['compare_price'], 2) ?> SAR</span>
                    <?php endif; ?>
                    <span class="product-price"><?= number_format($product['price'], 2) ?> SAR</span>
                    <span class="product-badge">In Stock</span>
                </div>
                
                <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                
                <div class="product-options">
                    <div class="option-group">
                        <label>Quantity:</label>
                        <div class="quantity-selector">
                            <button class="qty-btn" id="qtyMinus" type="button">−</button>
                            <input type="number" class="qty-input" value="1" min="1" id="qtyInput" readonly>
                            <button class="qty-btn" id="qtyPlus" type="button">+</button>
                        </div>
                    </div>
                </div>
                
                <div class="product-actions-group">
                    <button class="btn btn-primary btn-lg add-to-cart" data-id="<?= $product['id'] ?? $product['slug'] ?>">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                    <button class="btn btn-secondary btn-lg buy-now-btn">
                        <i class="fas fa-bolt"></i>
                        Buy Now
                    </button>
                </div>
                
                <div class="product-meta">
                    <div class="meta-item">
                        <i class="fas fa-truck"></i>
                        <span>Free shipping on orders over 500 SAR</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>2-year warranty included</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-undo"></i>
                        <span>30-day return policy</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="product-tabs">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="description">
                    <i class="fas fa-file-alt"></i> Description
                </button>
                <button class="tab-btn" data-tab="specifications">
                    <i class="fas fa-list-ul"></i> Specifications
                </button>
            </div>
            
            <div class="tabs-content">
                <div class="tab-panel active" id="description">
                    <h3>Product Description</h3>
                    <p><?= htmlspecialchars($product['full_description']) ?></p>
                    
                    <h4>Key Features:</h4>
                    <ul>
                        <li>High-performance motor for demanding applications</li>
                        <li>Ergonomic design for comfortable extended use</li>
                        <li>Durable construction with premium materials</li>
                        <li>Advanced safety features</li>
                        <li>Easy maintenance and serviceability</li>
                    </ul>
                </div>
                
                <div class="tab-panel" id="specifications">
                    <h3>Technical Specifications</h3>
                    <table class="specs-table">
                        <?php if (!empty($product['specifications'])): ?>
                            <?php foreach ($product['specifications'] as $key => $value): ?>
                                <tr>
                                    <th><?= htmlspecialchars($key) ?></th>
                                    <td><?= htmlspecialchars($value) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" style="text-align: center; color: #6c757d;">No specifications available</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <?php if (!empty($related_products)): ?>
            <div class="related-products">
                <h2 class="section-title">Related Products</h2>
                <div class="products-grid">
                    <?php foreach (array_slice($related_products, 0, 4) as $relatedProduct): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?= $relatedProduct['image'] ?>" alt="<?= htmlspecialchars($relatedProduct['title']) ?>" loading="lazy">
                            </div>
                            <div class="product-content">
                                <h3 class="product-title">
                                    <a href="<?= View::url('/products/' . $relatedProduct['slug']) ?>"><?= htmlspecialchars($relatedProduct['title']) ?></a>
                                </h3>
                                <div class="product-footer">
                                    <span class="product-price"><?= number_format($relatedProduct['price'], 2) ?> SAR</span>
                                    <a href="<?= View::url('/products/' . $relatedProduct['slug']) ?>" class="btn btn-primary btn-sm">View</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Additional Product Detail Styles */
.product-category-badge {
    margin-bottom: 8px;
}

.product-category-badge span {
    display: inline-block;
    padding: 4px 12px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1565c0;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.product-sku-stock {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 4px;
}

.product-sku-stock .sku {
    font-size: 0.85rem;
    color: #6c757d;
}

.product-price-old {
    font-size: 1.1rem;
    color: #adb5bd;
    text-decoration: line-through;
}

.buy-now-btn {
    position: relative;
    overflow: hidden;
}

.buy-now-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.buy-now-btn:hover::before {
    left: 100%;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity selector
    const qtyInput = document.getElementById('qtyInput');
    const qtyMinus = document.getElementById('qtyMinus');
    const qtyPlus = document.getElementById('qtyPlus');
    
    if (qtyMinus && qtyPlus && qtyInput) {
        qtyMinus.addEventListener('click', function() {
            let value = parseInt(qtyInput.value) || 1;
            if (value > 1) {
                qtyInput.value = value - 1;
            }
        });
        
        qtyPlus.addEventListener('click', function() {
            let value = parseInt(qtyInput.value) || 1;
            qtyInput.value = value + 1;
        });
    }
    
    // Gallery thumbnail click
    const thumbs = document.querySelectorAll('.gallery-thumb');
    const mainImage = document.getElementById('mainImage');
    
    thumbs.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Remove active class from all thumbs
            thumbs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked thumb
            this.classList.add('active');
            // Update main image
            if (mainImage) {
                mainImage.src = this.dataset.image;
            }
        });
    });
    
    // Tab functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Remove active class from all buttons and panels
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));
            
            // Add active class to clicked button and corresponding panel
            this.classList.add('active');
            document.getElementById(tabId)?.classList.add('active');
        });
    });
    
    // Wishlist button
    const wishlistBtn = document.querySelector('.gallery-action');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.color = '#e74c3c';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.color = '';
            }
        });
    }
});
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

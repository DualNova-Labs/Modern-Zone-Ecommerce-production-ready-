<?php
ob_start();
?>

<!-- Breadcrumb -->
<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="/host/mod/">Home</a></li>
            <li><a href="/host/mod/products">Products</a></li>
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
                    <img src="<?= $product['images'][0] ?>" alt="<?= htmlspecialchars($product['title']) ?>" id="mainImage">
                    <button class="gallery-action" title="Add to Wishlist">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <div class="gallery-thumbs">
                    <?php foreach ($product['images'] as $index => $image): ?>
                        <button class="gallery-thumb <?= ($index === 0) ? 'active' : '' ?>" 
                                data-image="<?= $image ?>">
                            <img src="<?= $image ?>" alt="Product image <?= $index + 1 ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="product-info">
                <h1 class="product-title"><?= htmlspecialchars($product['title']) ?></h1>
                
                <div class="product-rating">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="rating-text">(4.5 out of 5) - 127 reviews</span>
                </div>
                
                <div class="product-price-section">
                    <span class="product-price"><?= number_format($product['price'], 2) ?> SAR</span>
                    <span class="product-badge">In Stock</span>
                </div>
                
                <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                
                <div class="product-options">
                    <div class="option-group">
                        <label>Quantity:</label>
                        <div class="quantity-selector">
                            <button class="qty-btn" id="qtyMinus">-</button>
                            <input type="number" class="qty-input" value="1" min="1" id="qtyInput">
                            <button class="qty-btn" id="qtyPlus">+</button>
                        </div>
                    </div>
                </div>
                
                <div class="product-actions-group">
                    <button class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-cart-plus"></i>
                        Add to Cart
                    </button>
                    <button class="btn btn-secondary btn-lg">
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
                <button class="tab-btn active" data-tab="description">Description</button>
                <button class="tab-btn" data-tab="specifications">Specifications</button>
                <button class="tab-btn" data-tab="reviews">Reviews (127)</button>
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
                        <?php foreach ($product['specifications'] as $key => $value): ?>
                            <tr>
                                <th><?= htmlspecialchars($key) ?></th>
                                <td><?= htmlspecialchars($value) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="tab-panel" id="reviews">
                    <h3>Customer Reviews</h3>
                    
                    <div class="reviews-summary">
                        <div class="summary-rating">
                            <span class="rating-number">4.5</span>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <p>Based on 127 reviews</p>
                        </div>
                    </div>
                    
                    <div class="reviews-list">
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <h4>John Smith</h4>
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <span class="review-date">2 weeks ago</span>
                            </div>
                            <p class="review-text">Excellent product! Very powerful and reliable. Been using it for a month now and it performs flawlessly.</p>
                        </div>
                    </div>
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
                                    <a href="/host/mod/products/<?= $relatedProduct['slug'] ?>"><?= htmlspecialchars($relatedProduct['title']) ?></a>
                                </h3>
                                <div class="product-footer">
                                    <span class="product-price"><?= number_format($relatedProduct['price'], 2) ?> SAR</span>
                                    <a href="/host/mod/products/<?= $relatedProduct['slug'] ?>" class="btn btn-primary btn-sm">View</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

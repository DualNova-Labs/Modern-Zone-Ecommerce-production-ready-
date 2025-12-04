<?php
ob_start();
?>

<?php if (!empty($banners)): ?>
<!-- Hero Banner Slider -->
<section class="hero-slider">
    <div class="slider-container">
        <div class="slider-track" id="heroSliderTrack">
            <?php foreach ($banners as $index => $banner): ?>
            <div class="slider-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="banner-slide">
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($banner['image']) ?>" 
                         alt="<?= htmlspecialchars($banner['title']) ?>"
                         onerror="this.src='<?= View::asset('images/placeholder.svg') ?>'">
                    <div class="banner-overlay">
                        <div class="banner-content">
                            <?php if (!empty($banner['badge'])): ?>
                                <span class="banner-badge"><?= htmlspecialchars($banner['badge']) ?></span>
                            <?php endif; ?>
                            <h2 class="banner-title"><?= htmlspecialchars($banner['title']) ?></h2>
                            <?php if (!empty($banner['subtitle'])): ?>
                                <p class="banner-subtitle"><?= htmlspecialchars($banner['subtitle']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($banner['link_url'])): ?>
                                <a href="<?= View::url($banner['link_url']) ?>" class="banner-btn">
                                    <?= htmlspecialchars($banner['link_text'] ?? 'Learn More') ?> <i class="fas fa-arrow-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Slider Controls -->
        <?php if (count($banners) > 1): ?>
        <button class="slider-arrow slider-prev" id="heroPrev" aria-label="Previous slide">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slider-arrow slider-next" id="heroNext" aria-label="Next slide">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Slider Dots -->
        <div class="slider-dots" id="heroSliderDots">
            <?php foreach ($banners as $index => $banner): ?>
                <button class="slider-dot <?= $index === 0 ? 'active' : '' ?>" 
                        data-slide="<?= $index ?>" 
                        aria-label="Go to slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- Trust Badges Section -->
<section class="trust-badges-section">
    <div class="container">
        <div class="trust-badges-grid">
            <div class="trust-badge-item">
                <div class="trust-badge-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="trust-badge-content">
                    <h4>Free Shipping</h4>
                    <p>On orders over 500 SAR</p>
                </div>
            </div>
            <div class="trust-badge-item">
                <div class="trust-badge-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="trust-badge-content">
                    <h4>Secure Payment</h4>
                    <p>100% secure transactions</p>
                </div>
            </div>
            <div class="trust-badge-item">
                <div class="trust-badge-icon">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <div class="trust-badge-content">
                    <h4>Easy Returns</h4>
                    <p>30-day return policy</p>
                </div>
            </div>
            <div class="trust-badge-item">
                <div class="trust-badge-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="trust-badge-content">
                    <h4>24/7 Support</h4>
                    <p>Dedicated customer service</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Brands Section -->
<section class="brands-section-modern">
    <div class="container">
        <div class="section-header-modern">
            <span class="section-badge">Trusted Partners</span>
            <h2 class="section-title-modern">Our Premium Brands</h2>
            <p class="section-subtitle-modern">We partner with the world's leading manufacturers</p>
        </div>
        <div class="brands-carousel-wrapper">
            <div class="brands-carousel" id="brandsCarousel">
                <div class="brands-track">
                    <?php if (!empty($brands)): ?>
                        <?php foreach ($brands as $brand): ?>
                            <a href="<?= View::url('products?brand=' . urlencode($brand['slug'])) ?>" class="brand-item-modern">
                                <div class="brand-logo-modern">
                                    <img src="<?= htmlspecialchars($brand['logo']) ?>" 
                                         alt="<?= htmlspecialchars($brand['name']) ?>" 
                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<?= htmlspecialchars($brand['name']) ?>';">
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="featured-products-modern">
    <div class="container">
        <div class="section-header-modern">
            <span class="section-badge">Hand Picked</span>
            <h2 class="section-title-modern">Featured Products</h2>
            <p class="section-subtitle-modern">Our most popular tools and equipment selected for you</p>
        </div>
        
        <div class="products-grid-modern">
            <?php foreach ($featured_products as $product): ?>
                <a href="<?= View::url('products/' . $product['slug']) ?>" class="product-card-modern">
                    <div class="product-image-modern">
                        <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['title']) ?>" loading="lazy">
                        <div class="product-badge-modern">Featured</div>
                        <div class="product-overlay-modern">
                            <button class="product-quick-view" onclick="event.preventDefault(); event.stopPropagation();">
                                <i class="far fa-eye"></i> Quick View
                            </button>
                        </div>
                        <button class="product-wishlist-btn" onclick="event.preventDefault(); event.stopPropagation();">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    <div class="product-content-modern">
                        <h3 class="product-title-modern"><?= htmlspecialchars($product['title']) ?></h3>
                        <p class="product-description-modern"><?= htmlspecialchars(substr($product['description'], 0, 60)) ?>...</p>
                        <div class="product-footer-modern">
                            <div class="product-price-modern">
                                <span class="price-current"><?= number_format($product['price'], 2) ?> SAR</span>
                            </div>
                            <span class="product-view-btn">
                                <i class="fas fa-shopping-cart"></i>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        
        <div class="section-footer-modern">
            <a href="<?= View::url('products') ?>" class="btn-view-all">
                View All Products
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Best Selling Section -->
<section class="best-selling-modern">
    <div class="container">
        <div class="section-header-flex">
            <div class="section-header-left">
                <span class="section-badge">Top Rated</span>
                <h2 class="section-title-modern">Best Selling Products</h2>
            </div>
            <div class="best-selling-nav-modern">
                <button class="nav-arrow-modern" id="bestSellingPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="nav-arrow-modern" id="bestSellingNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <div class="best-selling-slider-wrapper">
            <div class="best-selling-slider-modern" id="bestSellingSlider">
                <?php foreach ($best_selling as $product): ?>
                    <div class="best-selling-card-modern">
                        <a href="<?= View::url('products/' . $product['slug']) ?>" class="best-selling-product-modern">
                            <?php if ($product['discount']): ?>
                                <div class="discount-badge-modern">-<?= $product['discount'] ?>%</div>
                            <?php endif; ?>
                            <div class="best-selling-image-modern">
                                <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['title']) ?>" loading="lazy">
                            </div>
                            <div class="best-selling-content-modern">
                                <h3 class="best-selling-title-modern"><?= htmlspecialchars($product['title']) ?></h3>
                                <div class="best-selling-price-modern">
                                    <?php if ($product['original_price']): ?>
                                        <span class="price-old"><?= number_format($product['original_price'], 2) ?> SAR</span>
                                    <?php endif; ?>
                                    <span class="price-new"><?= number_format($product['price'], 2) ?> SAR</span>
                                </div>
                                <div class="best-selling-action">
                                    <span class="add-to-cart-text">Add to Cart</span>
                                    <i class="fas fa-plus"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section-modern">
    <div class="container">
        <div class="section-header-modern">
            <span class="section-badge">Explore</span>
            <h2 class="section-title-modern">Browse by Category</h2>
            <p class="section-subtitle-modern">Find the perfect tools for your needs</p>
        </div>
        
        <div class="categories-grid-modern" id="categoriesGrid">
            <?php foreach ($categories as $category): ?>
                <a href="<?= $category['link'] ?>" class="category-card-modern" style="--bg-image: url('<?= $category['image'] ?>');">
                    <div class="category-card-bg"></div>
                    <div class="category-card-content-modern">
                        <div class="category-icon-modern">
                            <i class="fas fa-<?= $category['icon'] ?>"></i>
                        </div>
                        <h3 class="category-title-modern"><?= htmlspecialchars($category['title']) ?></h3>
                        <p class="category-description-modern"><?= htmlspecialchars($category['description']) ?></p>
                        <span class="category-link-modern">
                            Explore <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-modern">
    <div class="container">
        <div class="newsletter-wrapper">
            <div class="newsletter-content-modern">
                <div class="newsletter-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h2>Subscribe to Our Newsletter</h2>
                <p>Get the latest updates on new products and upcoming sales</p>
            </div>
            <form class="newsletter-form-modern">
                <div class="newsletter-input-group">
                    <input type="email" placeholder="Enter your email address" required>
                    <button type="submit">
                        Subscribe <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
/* ===== MODERN HOME PAGE STYLES ===== */

/* Trust Badges Section */
.trust-badges-section {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    padding: 30px 0;
    border-bottom: 1px solid #e9ecef;
}

.trust-badges-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.trust-badge-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.trust-badge-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.trust-badge-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--primary-color) 0%, #e65c00 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.trust-badge-content h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0 0 4px 0;
}

.trust-badge-content p {
    font-size: 0.85rem;
    color: #6c757d;
    margin: 0;
}

/* Section Header Modern */
.section-header-modern {
    text-align: center;
    margin-bottom: 50px;
}

.section-badge {
    display: inline-block;
    padding: 6px 16px;
    background: linear-gradient(135deg, #fff5f0 0%, #ffe8db 100%);
    color: var(--primary-color);
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 12px;
}

.section-title-modern {
    font-size: 2.25rem;
    font-weight: 800;
    color: #1a1a2e;
    margin: 0 0 12px 0;
    line-height: 1.2;
}

.section-subtitle-modern {
    font-size: 1.05rem;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Brands Section Modern */
.brands-section-modern {
    background: #fff;
    padding: 60px 0;
    overflow: hidden;
}

.brand-item-modern {
    flex: 0 0 auto;
    width: 140px;
    height: 140px;
    background: #fff;
    border: 2px solid #e9ecef;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
}

.brand-item-modern:hover {
    border-color: var(--primary-color);
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(var(--primary-color-rgb), 0.15);
}

.brand-logo-modern {
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.brand-logo-modern img {
    max-width: 100%;
    max-height: 80px;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.brand-item-modern:hover .brand-logo-modern img {
    transform: scale(1.1);
}

/* Featured Products Modern */
.featured-products-modern {
    padding: 80px 0;
    background: linear-gradient(180deg, #f8f9fa 0%, #fff 100%);
}

.products-grid-modern {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.product-card-modern {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    display: block;
    position: relative;
}

.product-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.product-image-modern {
    position: relative;
    aspect-ratio: 1;
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    overflow: hidden;
}

.product-image-modern img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 20px;
    transition: transform 0.5s ease;
}

.product-card-modern:hover .product-image-modern img {
    transform: scale(1.08);
}

.product-badge-modern {
    position: absolute;
    top: 16px;
    left: 16px;
    padding: 6px 14px;
    background: linear-gradient(135deg, var(--primary-color) 0%, #e65c00 100%);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
}

.product-wishlist-btn {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    background: #fff;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 2;
}

.product-wishlist-btn:hover {
    color: #e74c3c;
    transform: scale(1.1);
}

.product-overlay-modern {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    display: flex;
    justify-content: center;
}

.product-card-modern:hover .product-overlay-modern {
    opacity: 1;
    transform: translateY(0);
}

.product-quick-view {
    padding: 10px 20px;
    background: #fff;
    border: none;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #1a1a2e;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.product-quick-view:hover {
    background: var(--primary-color);
    color: #fff;
}

.product-content-modern {
    padding: 20px;
}

.product-title-modern {
    font-size: 1rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0 0 8px 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-description-modern {
    font-size: 0.85rem;
    color: #6c757d;
    margin: 0 0 16px 0;
    line-height: 1.5;
}

.product-footer-modern {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.product-price-modern .price-current {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--primary-color);
}

.product-view-btn {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, var(--primary-color) 0%, #e65c00 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.product-card-modern:hover .product-view-btn {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.4);
}

.section-footer-modern {
    text-align: center;
    margin-top: 50px;
}

.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 36px;
    background: #fff;
    color: #1a1a2e;
    border: 2px solid #e9ecef;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    background: var(--primary-color);
    color: #fff;
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(var(--primary-color-rgb), 0.3);
}

.btn-view-all i {
    transition: transform 0.3s ease;
}

.btn-view-all:hover i {
    transform: translateX(5px);
}

/* Best Selling Modern */
.best-selling-modern {
    padding: 80px 0;
    background: #fff;
}

.section-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 40px;
}

.section-header-left .section-badge {
    margin-bottom: 8px;
}

.section-header-left .section-title-modern {
    margin: 0;
}

.best-selling-nav-modern {
    display: flex;
    gap: 10px;
}

.nav-arrow-modern {
    width: 48px;
    height: 48px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-arrow-modern:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: #fff;
    transform: scale(1.05);
}

.best-selling-slider-modern {
    display: flex;
    gap: 24px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    padding: 10px 5px;
    margin: 0 -5px;
}

.best-selling-slider-modern::-webkit-scrollbar {
    display: none;
}

.best-selling-card-modern {
    flex: 0 0 calc(20% - 20px);
    min-width: 240px;
}

.best-selling-product-modern {
    display: block;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.4s ease;
    text-decoration: none;
    border: 2px solid transparent;
}

.best-selling-product-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-color);
}

.discount-badge-modern {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 6px 12px;
    background: #e74c3c;
    color: #fff;
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 8px;
    z-index: 2;
}

.best-selling-image-modern {
    position: relative;
    height: 200px;
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.best-selling-image-modern img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.4s ease;
}

.best-selling-product-modern:hover .best-selling-image-modern img {
    transform: scale(1.1);
}

.best-selling-content-modern {
    padding: 20px;
}

.best-selling-title-modern {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0 0 12px 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 42px;
}

.best-selling-price-modern {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}

.best-selling-price-modern .price-old {
    font-size: 0.9rem;
    color: #adb5bd;
    text-decoration: line-through;
}

.best-selling-price-modern .price-new {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--primary-color);
}

.best-selling-action {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.best-selling-action .add-to-cart-text {
    font-size: 0.85rem;
    font-weight: 600;
    color: #495057;
}

.best-selling-action i {
    color: var(--primary-color);
}

.best-selling-product-modern:hover .best-selling-action {
    background: var(--primary-color);
}

.best-selling-product-modern:hover .best-selling-action .add-to-cart-text,
.best-selling-product-modern:hover .best-selling-action i {
    color: #fff;
}

/* Categories Section Modern */
.categories-section-modern {
    padding: 80px 0;
    background: linear-gradient(180deg, #f8f9fa 0%, #fff 100%);
}

.categories-grid-modern {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.category-card-modern {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    min-height: 320px;
    display: flex;
    align-items: flex-end;
    text-decoration: none;
    transition: all 0.4s ease;
}

.category-card-bg {
    position: absolute;
    inset: 0;
    background-image: var(--bg-image);
    background-size: cover;
    background-position: center;
    transition: transform 0.5s ease;
}

.category-card-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.1) 100%);
    transition: all 0.3s ease;
}

.category-card-modern:hover .category-card-bg {
    transform: scale(1.1);
}

.category-card-modern:hover .category-card-bg::after {
    background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.2) 100%);
}

.category-card-content-modern {
    position: relative;
    z-index: 2;
    padding: 30px;
    width: 100%;
}

.category-icon-modern {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color) 0%, #e65c00 100%);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.5rem;
    margin-bottom: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.4);
}

.category-card-modern:hover .category-icon-modern {
    transform: scale(1.1) rotate(5deg);
}

.category-title-modern {
    font-size: 1.35rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 8px 0;
}

.category-description-modern {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.85);
    margin: 0 0 16px 0;
    line-height: 1.5;
}

.category-link-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #fff;
    font-size: 0.9rem;
    font-weight: 700;
    padding: 10px 20px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 25px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.category-card-modern:hover .category-link-modern {
    background: var(--primary-color);
    gap: 12px;
}

/* Newsletter Modern */
.newsletter-modern {
    padding: 80px 0;
    background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 100%);
}

.newsletter-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 60px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 24px;
    padding: 50px 60px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.newsletter-content-modern {
    flex: 1;
}

.newsletter-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--primary-color) 0%, #e65c00 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.75rem;
    margin-bottom: 20px;
}

.newsletter-content-modern h2 {
    font-size: 1.75rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 10px 0;
}

.newsletter-content-modern p {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

.newsletter-form-modern {
    flex: 1;
    max-width: 500px;
}

.newsletter-input-group {
    display: flex;
    gap: 12px;
}

.newsletter-input-group input {
    flex: 1;
    padding: 18px 24px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 14px;
    color: #fff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.newsletter-input-group input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.newsletter-input-group input:focus {
    outline: none;
    border-color: var(--primary-color);
    background: rgba(255, 255, 255, 0.15);
}

.newsletter-input-group button {
    padding: 18px 32px;
    background: linear-gradient(135deg, var(--primary-color) 0%, #e65c00 100%);
    border: none;
    border-radius: 14px;
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.newsletter-input-group button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(var(--primary-color-rgb), 0.4);
}

/* Responsive Styles */
@media (max-width: 1200px) {
    .products-grid-modern,
    .categories-grid-modern {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .trust-badges-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .products-grid-modern,
    .categories-grid-modern {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .newsletter-wrapper {
        flex-direction: column;
        text-align: center;
        padding: 40px;
    }
    
    .newsletter-form-modern {
        max-width: 100%;
        width: 100%;
    }
    
    .section-header-flex {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .trust-badges-grid {
        grid-template-columns: 1fr;
    }
    
    .products-grid-modern,
    .categories-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .section-title-modern {
        font-size: 1.75rem;
    }
    
    .featured-products-modern,
    .best-selling-modern,
    .categories-section-modern,
    .newsletter-modern {
        padding: 50px 0;
    }
    
    .newsletter-input-group {
        flex-direction: column;
    }
    
    .newsletter-input-group button {
        width: 100%;
        justify-content: center;
    }
    
    .category-card-modern {
        min-height: 280px;
    }
}

@media (max-width: 480px) {
    .best-selling-card-modern {
        min-width: 200px;
    }
    
    .trust-badge-item {
        padding: 16px;
    }
    
    .trust-badge-icon {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Best Selling Slider Navigation
    const slider = document.getElementById('bestSellingSlider');
    const prevBtn = document.getElementById('bestSellingPrev');
    const nextBtn = document.getElementById('bestSellingNext');
    
    if (slider && prevBtn && nextBtn) {
        const scrollAmount = 280;
        
        prevBtn.addEventListener('click', function() {
            slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });
        
        nextBtn.addEventListener('click', function() {
            slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    }
    
    // Wishlist button toggle
    document.querySelectorAll('.product-wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
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
    });
});
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

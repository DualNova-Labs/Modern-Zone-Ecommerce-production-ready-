<?php
ob_start();
?>

<!-- Hero Banner Slider -->
<section class="hero-slider">
    <div class="slider-container">
        <div class="slider-track" id="heroSliderTrack">
            <!-- Banner 1: Industrial Tools & Equipment -->
            <div class="slider-item active">
                <div class="banner-slide">
                    <img src="<?= View::asset('images/banners/1.jpeg') ?>" alt="Modern Zone Trading - Industrial Tools & Equipment">
                    <div class="banner-overlay">
                        <div class="banner-content">
                            <span class="banner-badge">NEW ARRIVALS</span>
                            <h2 class="banner-title">Industrial Tools & Equipment</h2>
                            <p class="banner-subtitle">Premium Quality Tools for Professional Use</p>
                            <a href="<?= View::url('products') ?>" class="banner-btn">
                                Shop Now <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Banner 2: Cutting Tools -->
            <div class="slider-item">
                <div class="banner-slide">
                    <img src="<?= View::asset('images/banners/2.jpeg') ?>" alt="Professional Cutting Tools - Modern Zone Trading">
                    <div class="banner-overlay">
                        <div class="banner-content">
                            <span class="banner-badge">BEST SELLERS</span>
                            <h2 class="banner-title">Professional Cutting Tools</h2>
                            <p class="banner-subtitle">Precision Tools from Leading Brands</p>
                            <a href="<?= View::url('products?category=cutting-tools') ?>" class="banner-btn">
                                Explore Collection <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Banner 3: CNC Machine Tools -->
            <div class="slider-item">
                <div class="banner-slide">
                    <img src="<?= View::asset('images/banners/3.jpeg') ?>" alt="CNC Machine Tools - Modern Zone Trading">
                    <div class="banner-overlay">
                        <div class="banner-content">
                            <span class="banner-badge">FEATURED</span>
                            <h2 class="banner-title">CNC Tool Holders</h2>
                            <p class="banner-subtitle">High-Precision Machining Solutions</p>
                            <a href="<?= View::url('products?category=cnc-holders') ?>" class="banner-btn">
                                View Products <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Banner 4: Measuring Instruments -->
            <div class="slider-item">
                <div class="banner-slide">
                    <img src="<?= View::asset('images/banners/4.jpeg') ?>" alt="Precision Measuring Instruments - Modern Zone Trading">
                    <div class="banner-overlay">
                        <div class="banner-content">
                            <span class="banner-badge">PRECISION TOOLS</span>
                            <h2 class="banner-title">Measuring Instruments</h2>
                            <p class="banner-subtitle">Accurate Measurements Every Time</p>
                            <a href="<?= View::url('products?category=measuring-tools') ?>" class="banner-btn">
                                Discover More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Banner 5: Power Tools -->
            <div class="slider-item">
                <div class="banner-slide">
                    <img src="<?= View::asset('images/banners/5.jpeg') ?>" alt="Power Tools & Equipment - Modern Zone Trading">
                    <div class="banner-overlay">
                        <div class="banner-content">
                            <span class="banner-badge">SPECIAL OFFER</span>
                            <h2 class="banner-title">Power Tools & Equipment</h2>
                            <p class="banner-subtitle">Heavy-Duty Tools for Every Job</p>
                            <a href="<?= View::url('products?category=power-tools') ?>" class="banner-btn">
                                Shop Power Tools <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Banner 6: Complete Solutions -->
            <div class="slider-item">
                <div class="banner-slide">
                    <img src="<?= View::asset('images/banners/6.jpeg') ?>" alt="Industrial Solutions - Modern Zone Trading Saudi Arabia">
                    <div class="banner-overlay">
                        <div class="banner-content">
                            <span class="banner-badge">AUTHORIZED DISTRIBUTOR</span>
                            <h2 class="banner-title">Complete Industrial Solutions</h2>
                            <p class="banner-subtitle">Trusted Partner in Saudi Arabia</p>
                            <a href="<?= View::url('about') ?>" class="banner-btn">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Slider Controls -->
        <button class="slider-arrow slider-prev" id="heroPrev" aria-label="Previous slide">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slider-arrow slider-next" id="heroNext" aria-label="Next slide">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Slider Dots -->
        <div class="slider-dots" id="heroSliderDots">
            <button class="slider-dot active" data-slide="0" aria-label="Go to slide 1"></button>
            <button class="slider-dot" data-slide="1" aria-label="Go to slide 2"></button>
            <button class="slider-dot" data-slide="2" aria-label="Go to slide 3"></button>
            <button class="slider-dot" data-slide="3" aria-label="Go to slide 4"></button>
            <button class="slider-dot" data-slide="4" aria-label="Go to slide 5"></button>
            <button class="slider-dot" data-slide="5" aria-label="Go to slide 6"></button>
        </div>
    </div>
</section>

<!-- Our Brands Section -->
<section class="brands-section">
    <div class="container">
        <h2 class="brands-title">Our Brands</h2>
        <div class="brands-carousel-wrapper">
            <div class="brands-carousel" id="brandsCarousel">
                <div class="brands-track">
                    <?php if (!empty($brands)): ?>
                        <?php foreach ($brands as $brand): ?>
                            <a href="<?= View::url('products?brand=' . urlencode($brand['slug'])) ?>" class="brand-item">
                                <div class="brand-logo">
                                    <img src="<?= BASE_URL . '/' . htmlspecialchars($brand['logo']) ?>" 
                                         alt="<?= htmlspecialchars($brand['name']) ?>" 
                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<?= htmlspecialchars($brand['name']) ?>';">
                                </div>
                            </a>
                        <?php endforeach; ?>
                        <!-- Duplicate for infinite scroll -->
                        <?php foreach ($brands as $brand): ?>
                            <a href="<?= View::url('products?brand=' . urlencode($brand['slug'])) ?>" class="brand-item">
                                <div class="brand-logo">
                                    <img src="<?= BASE_URL . '/' . htmlspecialchars($brand['logo']) ?>" 
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
<section class="featured-products">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Featured Products</h2>
            <p class="section-subtitle">Our most popular tools and equipment</p>
        </div>
        
        <div class="products-grid">
            <?php foreach ($featured_products as $product): ?>
                <a href="<?= View::url('products/' . $product['slug']) ?>" class="product-card-link">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['title']) ?>" loading="lazy">
                            <div class="product-badge">Featured</div>
                            <div class="product-actions">
                                <button class="product-action-btn" title="Add to Wishlist" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="product-action-btn" title="Quick View" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-content">
                            <h3 class="product-title"><?= htmlspecialchars($product['title']) ?></h3>
                            <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                            <div class="product-footer">
                                <span class="product-price"><?= number_format($product['price'], 2) ?> SAR</span>
                                <span class="btn btn-primary btn-sm">View Product</span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        
        <div class="section-footer">
            <a href="<?= View::url('products') ?>" class="btn btn-secondary btn-lg">
                View All Products
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Best Selling Section -->
<section class="best-selling">
    <div class="container">
        <div class="best-selling-header">
            <h2 class="best-selling-title">Best Selling</h2>
            <div class="best-selling-nav">
                <button class="best-selling-arrow best-selling-prev" id="bestSellingPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="best-selling-arrow best-selling-next" id="bestSellingNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <div class="best-selling-slider-wrapper">
            <div class="best-selling-slider" id="bestSellingSlider">
                <?php foreach ($best_selling as $product): ?>
                    <div class="best-selling-card">
                        <a href="<?= View::url('products/' . $product['slug']) ?>" class="best-selling-product">
                            <?php if ($product['discount']): ?>
                                <div class="best-selling-badge">-<?= $product['discount'] ?>%</div>
                            <?php endif; ?>
                            <div class="best-selling-image">
                                <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['title']) ?>" loading="lazy">
                            </div>
                            <div class="best-selling-content">
                                <h3 class="best-selling-product-title"><?= htmlspecialchars($product['title']) ?></h3>
                                <div class="best-selling-price">
                                    <?php if ($product['original_price']): ?>
                                        <span class="best-selling-original-price"><?= number_format($product['original_price'], 2) ?> SAR</span>
                                    <?php endif; ?>
                                    <span class="best-selling-current-price"><?= number_format($product['price'], 2) ?> SAR</span>
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
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Browse by Category</h2>
            <p class="section-subtitle">Find the perfect tools for your needs</p>
            <p class="mobile-scroll-hint">
                <i class="fas fa-hand-pointer"></i> Swipe to explore more categories
            </p>
        </div>
        
        <div class="categories-grid" id="categoriesGrid">
            <?php foreach ($categories as $category): ?>
                <a href="<?= $category['link'] ?>" class="category-card" style="background-image: url('<?= $category['image'] ?>');">
                    <div class="category-card-overlay"></div>
                    <div class="category-card-content">
                        <div class="category-icon">
                            <i class="fas fa-<?= $category['icon'] ?>"></i>
                        </div>
                        <h3 class="category-title"><?= htmlspecialchars($category['title']) ?></h3>
                        <p class="category-description"><?= htmlspecialchars($category['description']) ?></p>
                        <span class="category-link">
                            Learn More
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
/* Mobile scroll hint */
.mobile-scroll-hint {
    display: none;
    font-size: 0.9rem;
    color: var(--primary-color);
    margin-top: 10px;
    font-weight: 600;
    animation: pulse 2s infinite;
}

.mobile-scroll-hint i {
    margin-right: 5px;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.6;
    }
}

@media (max-width: 480px) {
    .mobile-scroll-hint {
        display: block;
    }
}
</style>

<script>
// Hide scroll hint after user scrolls
document.addEventListener('DOMContentLoaded', function() {
    const categoriesGrid = document.getElementById('categoriesGrid');
    const scrollHint = document.querySelector('.mobile-scroll-hint');
    
    if (categoriesGrid && scrollHint && window.innerWidth <= 480) {
        let hasScrolled = false;
        
        categoriesGrid.addEventListener('scroll', function() {
            if (!hasScrolled && this.scrollLeft > 20) {
                hasScrolled = true;
                scrollHint.style.transition = 'opacity 0.5s';
                scrollHint.style.opacity = '0';
                setTimeout(() => {
                    scrollHint.style.display = 'none';
                }, 500);
            }
        });
    }
});
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

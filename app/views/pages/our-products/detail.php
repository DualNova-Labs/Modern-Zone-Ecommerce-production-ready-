<?php
ob_start();
?>

<!-- Product Category Hero Section -->
<section class="product-category-hero">
    <div class="container">
        <div class="hero-content">
            <div class="breadcrumb">
                <a href="<?= BASE_URL ?>/">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="<?= BASE_URL ?>/our-products">Our Products</a>
                <i class="fas fa-chevron-right"></i>
                <span><?= htmlspecialchars($product['name']) ?></span>
            </div>
            <h1 class="product-category-title"><?= htmlspecialchars($product['name']) ?></h1>
            <p class="product-category-subtitle"><?= htmlspecialchars($product['description']) ?></p>
            <div class="category-badge"><?= htmlspecialchars($product['category']) ?></div>
        </div>
    </div>
</section>

<!-- Product Details Section -->
<section class="product-details-section">
    <div class="container">
        <div class="product-details-grid">
            <!-- Product Image -->
            <div class="product-image-section">
                <?php if ($product['image']): ?>
                    <div class="main-image">
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             onerror="this.parentElement.innerHTML='<div class=\'image-placeholder\'><i class=\'fas fa-tools\'></i></div>'">
                    </div>
                <?php else: ?>
                    <div class="main-image">
                        <div class="image-placeholder">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Product Info -->
            <div class="product-info-section">
                <h2 class="info-title">Product Information</h2>
                
                <div class="info-group">
                    <h3 class="info-label">Category</h3>
                    <p class="info-value"><?= htmlspecialchars($product['category']) ?></p>
                </div>
                
                <?php if (!empty($product['applications'])): ?>
                    <div class="info-group">
                        <h3 class="info-label">Applications</h3>
                        <ul class="info-list">
                            <?php foreach ($product['applications'] as $application): ?>
                                <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($application) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($product['features'])): ?>
                    <div class="info-group">
                        <h3 class="info-label">Key Features</h3>
                        <ul class="info-list features-list">
                            <?php foreach ($product['features'] as $feature): ?>
                                <li><i class="fas fa-star"></i> <?= htmlspecialchars($feature) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="action-buttons">
                    <a href="<?= BASE_URL ?>/contact" class="btn btn-primary btn-lg">
                        <i class="fas fa-envelope"></i>
                        Request Quote
                    </a>
                    <a href="<?= BASE_URL ?>/contact" class="btn btn-secondary btn-lg">
                        <i class="fas fa-phone"></i>
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products Section -->
<?php if (!empty($relatedProducts)): ?>
<section class="related-products-section">
    <div class="container">
        <h2 class="section-title">Related Products</h2>
        <div class="related-products-grid">
            <?php foreach ($relatedProducts as $related): ?>
                <a href="<?= BASE_URL ?>/our-products/<?= htmlspecialchars($related['slug']) ?>" class="related-product-card">
                    <div class="related-image">
                        <?php if ($related['image']): ?>
                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($related['image']) ?>" 
                                 alt="<?= htmlspecialchars($related['name']) ?>"
                                 loading="lazy"
                                 onerror="this.parentElement.innerHTML='<div class=\'image-placeholder-small\'><i class=\'fas fa-tools\'></i></div>'">
                        <?php else: ?>
                            <div class="image-placeholder-small">
                                <i class="fas fa-tools"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="related-content">
                        <h3 class="related-name"><?= htmlspecialchars($related['name']) ?></h3>
                        <p class="related-category"><?= htmlspecialchars($related['category']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
/* Product Category Hero */
.product-category-hero {
    background: linear-gradient(135deg, var(--secondary-color) 0%, #1a252f 100%);
    padding: 100px 0 60px;
    position: relative;
    overflow: hidden;
}

.product-category-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
}

.hero-content {
    position: relative;
    color: white;
    max-width: 900px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    font-size: 0.9rem;
    opacity: 0.8;
}

.breadcrumb a {
    color: white;
    transition: opacity 0.2s;
}

.breadcrumb a:hover {
    opacity: 0.7;
}

.breadcrumb i {
    font-size: 0.7rem;
}

.product-category-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.product-category-subtitle {
    font-size: 1.2rem;
    line-height: 1.8;
    margin-bottom: 20px;
    opacity: 0.95;
}

.category-badge {
    display: inline-block;
    background: var(--primary-color);
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Product Details Section */
.product-details-section {
    padding: 80px 0;
}

.product-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: start;
}

.product-image-section {
    position: sticky;
    top: 100px;
}

.main-image {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    aspect-ratio: 1;
}

.main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
}

.image-placeholder i {
    font-size: 6rem;
    color: var(--gray-400);
}

.product-info-section {
    background: white;
    padding: 40px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
}

.info-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 3px solid var(--primary-color);
}

.info-group {
    margin-bottom: 30px;
}

.info-label {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--secondary-color);
    margin-bottom: 12px;
}

.info-value {
    font-size: 1rem;
    color: var(--gray-700);
    line-height: 1.6;
}

.info-list {
    list-style: none;
    padding: 0;
}

.info-list li {
    display: flex;
    align-items: start;
    gap: 12px;
    padding: 8px 0;
    font-size: 1rem;
    color: var(--gray-700);
    line-height: 1.6;
}

.info-list i {
    color: var(--primary-color);
    margin-top: 4px;
    flex-shrink: 0;
}

.features-list i {
    color: #fbbf24;
}

.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid var(--gray-200);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    border-radius: var(--border-radius-sm);
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
    text-align: center;
    justify-content: center;
    flex: 1;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: #e85d12;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background: var(--secondary-color);
    color: white;
}

.btn-secondary:hover {
    background: #1a252f;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Related Products Section */
.related-products-section {
    padding: 80px 0;
    background: var(--gray-50);
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 40px;
    text-align: center;
}

.related-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.related-product-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: transform 0.3s, box-shadow 0.3s;
    display: block;
}

.related-product-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.related-image {
    position: relative;
    padding-top: 75%;
    overflow: hidden;
    background: var(--gray-100);
}

.related-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.related-product-card:hover .related-image img {
    transform: scale(1.1);
}

.image-placeholder-small {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--gray-200) 0%, var(--gray-300) 100%);
}

.image-placeholder-small i {
    font-size: 3rem;
    color: var(--gray-400);
}

.related-content {
    padding: 20px;
}

.related-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--secondary-color);
    margin-bottom: 8px;
}

.related-category {
    font-size: 0.9rem;
    color: var(--primary-color);
    font-weight: 600;
    text-transform: uppercase;
}

/* Responsive Design */
@media (max-width: 968px) {
    .product-category-title {
        font-size: 2.5rem;
    }
    
    .product-details-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .product-image-section {
        position: static;
    }
    
    .product-info-section {
        padding: 30px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .related-products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 640px) {
    .product-category-hero {
        padding: 80px 0 40px;
    }
    
    .product-category-title {
        font-size: 2rem;
    }
    
    .product-category-subtitle {
        font-size: 1rem;
    }
    
    .product-details-section {
        padding: 50px 0;
    }
    
    .product-info-section {
        padding: 20px;
    }
    
    .info-title {
        font-size: 1.5rem;
    }
    
    .related-products-section {
        padding: 50px 0;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .related-products-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

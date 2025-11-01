<?php
ob_start();
?>

<!-- Our Products Hero Section -->
<section class="our-products-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Our Products</h1>
            <p class="hero-subtitle">Comprehensive range of industrial tools and equipment for all your manufacturing needs</p>
        </div>
    </div>
</section>

<!-- Products Grid Section -->
<section class="our-products-section">
    <div class="container">
        <?php if (!empty($products)): ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <a href="<?= BASE_URL ?>/our-products/<?= htmlspecialchars($product['slug']) ?>" class="product-category-card">
                        <div class="category-image">
                            <?php if ($product['image']): ?>
                                <img src="<?= BASE_URL ?>/<?= htmlspecialchars($product['image']) ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                     loading="lazy"
                                     onerror="this.src='<?= BASE_URL ?>/public/assets/images/placeholder-product.jpg'">
                            <?php else: ?>
                                <div class="category-placeholder">
                                    <i class="fas fa-tools"></i>
                                </div>
                            <?php endif; ?>
                            <div class="category-overlay">
                                <span class="view-details">View Details <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                        <div class="category-content">
                            <h3 class="category-name"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="category-type"><?= htmlspecialchars($product['category']) ?></p>
                            <p class="category-description"><?= htmlspecialchars($product['description']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-products">
                <i class="fas fa-box-open"></i>
                <p>No products available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Our Products Hero */
.our-products-hero {
    background: linear-gradient(135deg, var(--primary-color) 0%, #e85d12 100%);
    padding: 80px 0 60px;
    position: relative;
    overflow: hidden;
}

.our-products-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.5;
}

.hero-content {
    position: relative;
    text-align: center;
    color: white;
    max-width: 800px;
    margin: 0 auto;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.hero-subtitle {
    font-size: 1.25rem;
    line-height: 1.8;
    opacity: 0.95;
}

/* Products Section */
.our-products-section {
    padding: 80px 0;
    background: var(--gray-50);
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
}

.product-category-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: transform 0.3s, box-shadow 0.3s;
    display: block;
    height: 100%;
}

.product-category-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.category-image {
    position: relative;
    padding-top: 75%;
    overflow: hidden;
    background: var(--gray-100);
}

.category-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.product-category-card:hover .category-image img {
    transform: scale(1.1);
}

.category-placeholder {
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

.category-placeholder i {
    font-size: 4rem;
    color: var(--gray-400);
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 107, 53, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.product-category-card:hover .category-overlay {
    opacity: 1;
}

.view-details {
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.view-details i {
    transition: transform 0.3s;
}

.product-category-card:hover .view-details i {
    transform: translateX(5px);
}

.category-content {
    padding: 24px;
}

.category-name {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 8px;
}

.category-type {
    font-size: 0.9rem;
    color: var(--primary-color);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

.category-description {
    font-size: 0.95rem;
    color: var(--gray-600);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.no-products {
    text-align: center;
    padding: 80px 20px;
    color: var(--gray-600);
}

.no-products i {
    font-size: 5rem;
    color: var(--gray-300);
    margin-bottom: 20px;
}

.no-products p {
    font-size: 1.2rem;
}

/* Responsive Design */
@media (max-width: 968px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 640px) {
    .our-products-hero {
        padding: 60px 0 40px;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .our-products-section {
        padding: 50px 0;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

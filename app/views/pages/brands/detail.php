<?php
ob_start();
?>

<!-- Brand Hero Section -->
<section class="brand-hero">
    <div class="brand-hero-overlay"></div>
    <div class="container">
        <div class="brand-hero-content">
            <div class="brand-logo-container">
                <div class="brand-logo-circle">
                    <img src="<?= !empty($brand['logo']) ? View::asset('images/brands/' . $brand['logo']) : View::asset('images/placeholder.svg') ?>" 
                         alt="<?= htmlspecialchars($brand['name']) ?> Logo" 
                         class="brand-logo-img"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="brand-logo-fallback" style="display: none;">
                        <?= htmlspecialchars(substr($brand['name'], 0, 1)) ?>
                    </div>
                </div>
            </div>
            <h1 class="brand-name"><?= htmlspecialchars($brand['name']) ?></h1>
            <?php if (isset($current_subcategory) && $current_subcategory): ?>
                <div class="brand-subcategory-badge">
                    <i class="fas fa-filter"></i> <?= htmlspecialchars($current_subcategory['name']) ?>
                </div>
            <?php endif; ?>
            <p class="brand-tagline"><?= htmlspecialchars($brand['description'] ?? 'Premium quality industrial tools and equipment') ?></p>
            <div class="brand-meta">
                <?php if (isset($brand['country'])): ?>
                    <span class="brand-meta-item">
                        <i class="fas fa-globe"></i>
                        <?= htmlspecialchars($brand['country']) ?>
                    </span>
                <?php endif; ?>
                <?php if (isset($brand['founded'])): ?>
                    <span class="brand-meta-item">
                        <i class="fas fa-calendar"></i>
                        Since <?= htmlspecialchars($brand['founded']) ?>
                    </span>
                <?php endif; ?>
                <?php if (isset($brand['website'])): ?>
                    <span class="brand-meta-item">
                        <i class="fas fa-link"></i>
                        <a href="http://<?= htmlspecialchars($brand['website']) ?>" target="_blank" rel="noopener">
                            <?= htmlspecialchars($brand['website']) ?>
                        </a>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Brand Products Section -->
<section class="brand-products">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <?= htmlspecialchars($brand['name']) ?> 
                <?php if (isset($current_subcategory) && $current_subcategory): ?>
                    - <?= htmlspecialchars($current_subcategory['name']) ?>
                <?php endif; ?>
                Products
            </h2>
            <p class="section-subtitle">Discover our range of <?= htmlspecialchars($brand['name']) ?> tools and equipment</p>
        </div>
        
        <?php if (!empty($brand_subcategories)): ?>
            <!-- Subcategory Filter Tabs -->
            <div class="subcategory-filter">
                <a href="<?= View::url('/products?brand=' . $brand['slug']) ?>" 
                   class="subcategory-chip <?= empty($current_subcategory) ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i> All Products
                </a>
                <?php foreach ($brand_subcategories as $subcat): ?>
                    <a href="<?= View::url('/products?brand=' . $brand['slug'] . '&subcategory=' . $subcat['slug']) ?>" 
                       class="subcategory-chip <?= (isset($current_subcategory) && $current_subcategory && $current_subcategory['id'] == $subcat['id']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($subcat['name']) ?>
                        <?php if ($subcat['product_count'] > 0): ?>
                            <span class="chip-count"><?= $subcat['product_count'] ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($products)): ?>
            <div class="brand-products-layout">
                
                <!-- Products Grid -->
                <div class="products-content">
                    <div class="products-header">
                        <p class="products-count">Showing <span id="productCount"><?= count($products) ?></span> of <?= count($products) ?> Products</p>
                    </div>
                    
                    <div class="products-grid" id="productsGrid">
                        <?php foreach ($products as $product): ?>
                            <a href="<?= View::url('/products/' . $product['slug']) ?>" 
                               class="product-card-link" 
                               data-category="<?= htmlspecialchars($product['category'] ?? '') ?>" 
                               data-price="<?= $product['price'] ?>">
                                <div class="product-card">
                                    <div class="product-image">
                                        <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['title']) ?>" loading="lazy">
                                        <?php if (!empty($product['subcategory_name'])): ?>
                                            <div class="product-badge subcategory-badge"><?= htmlspecialchars($product['subcategory_name']) ?></div>
                                        <?php else: ?>
                                            <div class="product-badge brand-badge"><?= htmlspecialchars($brand['name']) ?></div>
                                        <?php endif; ?>
                                        <div class="product-actions">
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
                </div>
            </div>
            
            <?php if (isset($current_subcategory) && $current_subcategory): ?>
                <div class="section-footer">
                    <a href="<?= View::url('/products?brand=' . $brand['slug']) ?>" class="btn btn-secondary btn-lg">
                        View All <?= htmlspecialchars($brand['name']) ?> Products
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-products">
                <i class="fas fa-box-open"></i>
                <p>No products available <?= isset($current_subcategory) && $current_subcategory ? 'in this category' : 'for this brand' ?> at the moment.</p>
                <?php if (isset($current_subcategory) && $current_subcategory): ?>
                    <a href="<?= View::url('/products?brand=' . $brand['slug']) ?>" class="btn btn-primary">View All <?= htmlspecialchars($brand['name']) ?> Products</a>
                <?php else: ?>
                    <a href="<?= View::url('/products') ?>" class="btn btn-primary">Browse All Products</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Add Brand Page Specific Styles -->
<style>
/* Brand Hero Section */
.brand-hero {
    background: linear-gradient(135deg, var(--primary-color) 0%, #e85d12 100%);
    padding: 80px 0 60px;
    position: relative;
    overflow: hidden;
}

.brand-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.5;
}

.brand-hero-content {
    position: relative;
    text-align: center;
    color: white;
    max-width: 900px;
    margin: 0 auto;
}

.brand-logo-container {
    margin-bottom: 30px;
}

.brand-logo-circle {
    width: 160px;
    height: 160px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    padding: 20px;
    position: relative;
}

.brand-logo-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.brand-logo-fallback {
    font-size: 64px;
    font-weight: bold;
    color: var(--primary-color);
    display: none;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.brand-name {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 15px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.brand-subcategory-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 15px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.brand-subcategory-badge i {
    font-size: 0.85rem;
}

.brand-tagline {
    font-size: 1.25rem;
    line-height: 1.8;
    margin-bottom: 30px;
    opacity: 0.95;
}

/* Subcategory Filter Chips */
.subcategory-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: center;
    margin-bottom: 40px;
    padding: 20px;
    background: var(--gray-100);
    border-radius: 16px;
}

.subcategory-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    border: 2px solid var(--gray-200);
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-700);
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.subcategory-chip:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.subcategory-chip.active {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    box-shadow: 0 4px 15px rgba(232, 93, 18, 0.3);
}

.subcategory-chip .chip-count {
    background: rgba(0, 0, 0, 0.1);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
}

.subcategory-chip.active .chip-count {
    background: rgba(255, 255, 255, 0.25);
}

.subcategory-badge {
    background: #10b981 !important;
}

.brand-meta {
    display: flex;
    gap: 30px;
    justify-content: center;
    flex-wrap: wrap;
    font-size: 1rem;
}

.brand-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.brand-meta-item i {
    opacity: 0.8;
}

.brand-meta-item a {
    text-decoration: underline;
    transition: opacity 0.2s;
}

.brand-meta-item a:hover {
    opacity: 0.8;
}

/* Brand Products Section */
.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 30px;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 80px;
    height: 4px;
    background: var(--primary-color);
    border-radius: 2px;
}

/* Brand Products Section */
.brand-products {
    padding: 80px 0;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-subtitle {
    font-size: 1.1rem;
    color: var(--gray-600);
    margin-top: 15px;
}

.brand-products-layout {
    display: block;
    margin-bottom: 50px;
}

/* Products Content */
.products-content {
    min-width: 0;
}

.products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.products-count {
    font-size: 1rem;
    color: var(--gray-600);
}

.products-count span {
    font-weight: 600;
    color: var(--secondary-color);
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
    margin-bottom: 0;
}

.product-card-link {
    display: block;
    height: 100%;
}

.product-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: transform var(--transition-base), box-shadow var(--transition-base);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.product-image {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: var(--gray-100);
}

.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-base);
}

.product-card:hover .product-image img {
    transform: scale(1.1);
}

.product-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: var(--primary-color);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.product-actions {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transform: translateX(10px);
    transition: opacity var(--transition-base), transform var(--transition-base);
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.product-action-btn {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-md);
    transition: background var(--transition-base), color var(--transition-base);
}

.product-action-btn:hover {
    background: var(--primary-color);
    color: white;
}

.product-content {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--secondary-color);
    margin-bottom: 10px;
}

.product-description {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-bottom: 15px;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.product-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary-color);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: var(--border-radius-sm);
    font-weight: 600;
    transition: all var(--transition-base);
    text-align: center;
    justify-content: center;
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

.btn-sm {
    padding: 8px 16px;
    font-size: 0.9rem;
}

.btn-lg {
    padding: 16px 32px;
    font-size: 1.1rem;
}

.section-footer {
    text-align: center;
    margin-top: 40px;
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
    margin-bottom: 30px;
}

/* Responsive Design */
@media (max-width: 968px) {
    .brand-name {
        font-size: 2.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    

    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 640px) {
    .brand-hero {
        padding: 60px 0 40px;
    }
    
    .brand-logo-circle {
        width: 120px;
        height: 120px;
    }
    
    .brand-name {
        font-size: 2rem;
    }
    
    .brand-subcategory-badge {
        font-size: 0.85rem;
        padding: 6px 16px;
    }
    
    .brand-tagline {
        font-size: 1rem;
    }
    
    .brand-meta {
        flex-direction: column;
        gap: 15px;
    }
    
    .brand-products {
        padding: 50px 0;
    }
    
    .section-title {
        font-size: 1.75rem;
    }
    
    .subcategory-filter {
        padding: 15px;
        gap: 8px;
        overflow-x: auto;
        flex-wrap: nowrap;
        justify-content: flex-start;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    
    .subcategory-filter::-webkit-scrollbar {
        display: none;
    }
    
    .subcategory-chip {
        padding: 8px 16px;
        font-size: 0.8rem;
        white-space: nowrap;
        flex-shrink: 0;
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

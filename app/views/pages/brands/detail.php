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
            <h2 class="section-title"><?= htmlspecialchars($brand['name']) ?> Products</h2>
            <p class="section-subtitle">Discover our range of <?= htmlspecialchars($brand['name']) ?> tools and equipment</p>
        </div>
        
        <?php if (!empty($products)): ?>
            <div class="brand-products-layout">
                <!-- Filters Sidebar -->
                <aside class="filters-sidebar">
                    <div class="filters-header">
                        <h3 class="filters-title">Filters</h3>
                        <button class="clear-filters-btn" id="clearFilters">Clear All</button>
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="filter-group">
                        <button class="filter-group-header" data-filter="category">
                            <span>Category</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-group-content active" id="categoryFilter">
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <label class="filter-checkbox">
                                        <input type="checkbox" name="category" value="<?= htmlspecialchars($category['slug']) ?>">
                                        <span><?= htmlspecialchars($category['name']) ?></span>
                                        <span class="filter-count">(<?= $category['count'] ?>)</span>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Price Filter -->
                    <div class="filter-group">
                        <button class="filter-group-header" data-filter="price">
                            <span>Price</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-group-content active" id="priceFilter">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="price" value="0-100">
                                <span>Under 100 SAR</span>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="price" value="100-200">
                                <span>100 - 200 SAR</span>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="price" value="200-400">
                                <span>200 - 400 SAR</span>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="price" value="400-600">
                                <span>400 - 600 SAR</span>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="price" value="600+">
                                <span>600 SAR & Above</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Stock Filter -->
                    <div class="filter-group">
                        <label class="filter-checkbox stock-filter">
                            <input type="checkbox" name="stock" id="showOutOfStock">
                            <span>Show out of stock items</span>
                        </label>
                    </div>
                </aside>
                
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
                                        <div class="product-badge brand-badge"><?= htmlspecialchars($brand['name']) ?></div>
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
                </div>
            </div>
            
            <div class="section-footer">
                <a href="<?= View::url('/products?brand=' . $brand['slug']) ?>" class="btn btn-secondary btn-lg">
                    View All <?= htmlspecialchars($brand['name']) ?> Products
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="no-products">
                <i class="fas fa-box-open"></i>
                <p>No products available for this brand at the moment.</p>
                <a href="<?= View::url('/products') ?>" class="btn btn-primary">Browse All Products</a>
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
    margin-bottom: 20px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.brand-tagline {
    font-size: 1.25rem;
    line-height: 1.8;
    margin-bottom: 30px;
    opacity: 0.95;
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
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 40px;
    margin-bottom: 50px;
}

/* Filters Sidebar */
.filters-sidebar {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    box-shadow: var(--shadow-sm);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--gray-200);
}

.filters-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin: 0;
}

.clear-filters-btn {
    background: none;
    border: none;
    color: var(--primary-color);
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    padding: 4px 8px;
    transition: opacity 0.2s;
}

.clear-filters-btn:hover {
    opacity: 0.8;
}

.filter-group {
    margin-bottom: 24px;
    border-bottom: 1px solid var(--gray-200);
    padding-bottom: 16px;
}

.filter-group:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.filter-group-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    background: none;
    border: none;
    padding: 12px 0;
    cursor: pointer;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--secondary-color);
    transition: color 0.2s;
}

.filter-group-header:hover {
    color: var(--primary-color);
}

.filter-group-header i {
    transition: transform 0.3s;
    font-size: 0.9rem;
}

.filter-group-header.collapsed i {
    transform: rotate(-90deg);
}

.filter-group-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.filter-group-content.active {
    max-height: 500px;
    padding-top: 8px;
}

.filter-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    cursor: pointer;
    font-size: 0.95rem;
    color: var(--gray-700);
    transition: color 0.2s;
}

.filter-checkbox:hover {
    color: var(--primary-color);
}

.filter-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--primary-color);
}

.filter-checkbox span {
    flex: 1;
}

.filter-count {
    color: var(--gray-500);
    font-size: 0.85rem;
}

.stock-filter {
    padding: 12px 0;
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
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
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
    
    .brand-products-layout {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .filters-sidebar {
        position: static;
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
    
    .products-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Brand Page Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterGroupHeaders = document.querySelectorAll('.filter-group-header');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const productCards = document.querySelectorAll('.product-card-link');
    const productCountEl = document.getElementById('productCount');
    
    // Toggle filter groups
    filterGroupHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            this.classList.toggle('collapsed');
            content.classList.toggle('active');
        });
    });
    
    // Filter functionality
    function applyFilters() {
        const selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked')).map(cb => cb.value);
        const selectedPrices = Array.from(document.querySelectorAll('input[name="price"]:checked')).map(cb => cb.value);
        const showOutOfStock = document.getElementById('showOutOfStock').checked;
        
        let visibleCount = 0;
        
        productCards.forEach(card => {
            let show = true;
            const category = card.dataset.category;
            const price = parseFloat(card.dataset.price);
            
            // Category filter
            if (selectedCategories.length > 0 && !selectedCategories.includes(category)) {
                show = false;
            }
            
            // Price filter
            if (selectedPrices.length > 0) {
                const matchesPrice = selectedPrices.some(range => {
                    if (range === '0-100') return price < 100;
                    if (range === '100-200') return price >= 100 && price < 200;
                    if (range === '200-400') return price >= 200 && price < 400;
                    if (range === '400-600') return price >= 400 && price < 600;
                    if (range === '600+') return price >= 600;
                    return false;
                });
                if (!matchesPrice) show = false;
            }
            
            if (show) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        productCountEl.textContent = visibleCount;
    }
    
    // Add event listeners to all filter checkboxes
    document.querySelectorAll('.filter-checkbox input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });
    
    // Clear all filters
    clearFiltersBtn.addEventListener('click', function() {
        document.querySelectorAll('.filter-checkbox input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
        });
        applyFilters();
    });
});
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

<?php
ob_start();
?>

<!-- Category Hero Section -->
<section class="category-hero">
    <div class="category-hero-overlay"></div>
    <div class="container">
        <div class="category-hero-content">
            <h1 class="category-name"><?= htmlspecialchars($category['name']) ?></h1>
            <p class="category-tagline"><?= htmlspecialchars($category['description']) ?></p>
            <div class="category-meta">
                <span class="category-meta-item">
                    <i class="fas fa-box"></i>
                    <?= count($products) ?> Products Available
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Category Products Section -->
<section class="category-products">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?= htmlspecialchars($category['name']) ?> Products</h2>
            <p class="section-subtitle">Browse our complete range of <?= strtolower($category['name']) ?></p>
        </div>
        
        <?php if (!empty($products)): ?>
            <div class="brand-products-layout">
                <!-- Filters Sidebar -->
                <aside class="filters-sidebar">
                    <div class="filters-header">
                        <h3 class="filters-title">Filters</h3>
                        <button class="clear-filters-btn" id="clearFilters">Clear All</button>
                    </div>
                    
                    <!-- Price Filter -->
                    <div class="filter-group">
                        <button class="filter-group-header" data-filter="price">
                            <span>Price Range</span>
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
                        <div class="products-sort">
                            <select name="sort" id="sortSelect" class="sort-select">
                                <option value="default">Sort by: Default</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="name-asc">Name: A to Z</option>
                                <option value="name-desc">Name: Z to A</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="products-grid" id="productsGrid">
                        <?php foreach ($products as $product): ?>
                            <a href="/host/modernzone_new/products/<?= $product['slug'] ?>" 
                               class="product-card-link" 
                               data-category="<?= htmlspecialchars($product['category'] ?? '') ?>" 
                               data-price="<?= $product['price'] ?>">
                                <div class="product-card">
                                    <div class="product-image">
                                        <img src="<?= View::asset($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" loading="lazy">
                                        <div class="product-badge category-badge"><?= htmlspecialchars($category['name']) ?></div>
                                        <?php if (!($product['stock'] ?? true)): ?>
                                            <div class="product-badge out-of-stock">Out of Stock</div>
                                        <?php endif; ?>
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
                                        <?php if (isset($product['sku'])): ?>
                                            <p class="product-sku">SKU: <?= htmlspecialchars($product['sku']) ?></p>
                                        <?php endif; ?>
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
                <a href="<?= View::url('products') ?>" class="btn btn-secondary btn-lg">
                    Browse All Categories
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="no-products">
                <div class="no-products-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3>No Products Available</h3>
                <p>We currently don't have any products in this category.</p>
                <a href="<?= View::url('') ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    Back to Home
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

<?php
ob_start();
?>

<!-- Breadcrumb -->
<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="/host/mod/">Home</a></li>
            <li>Products</li>
        </ul>
    </div>
</section>

<!-- Products Page -->
<section class="products-page">
    <div class="container">
        <div class="products-layout">
            <!-- Sidebar Filter -->
            <aside class="products-sidebar" id="productsSidebar">
                <div class="sidebar-header">
                    <h3>Filters</h3>
                    <button class="sidebar-close" id="sidebarClose">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Categories Filter -->
                <div class="filter-group">
                    <h4 class="filter-title">Categories</h4>
                    <div class="filter-list">
                        <?php foreach ($categories as $cat): ?>
                            <label class="filter-item">
                                <input type="checkbox" name="category" value="<?= $cat['slug'] ?>" 
                                    <?= ($currentCategory === $cat['slug']) ? 'checked' : '' ?>>
                                <span><?= htmlspecialchars($cat['name']) ?> (<?= $cat['count'] ?>)</span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Price Range Filter -->
                <div class="filter-group">
                    <h4 class="filter-title">Price Range</h4>
                    <div class="price-range">
                        <input type="range" min="0" max="1000" value="1000" class="price-slider">
                        <div class="price-values">
                            <span>$0</span>
                            <span>$1000+</span>
                        </div>
                    </div>
                </div>
                
                <!-- Brand Filter -->
                <div class="filter-group">
                    <h4 class="filter-title">Brand</h4>
                    <div class="filter-list">
                        <label class="filter-item">
                            <input type="checkbox" name="brand" value="bosch">
                            <span>Bosch</span>
                        </label>
                        <label class="filter-item">
                            <input type="checkbox" name="brand" value="dewalt">
                            <span>DeWalt</span>
                        </label>
                        <label class="filter-item">
                            <input type="checkbox" name="brand" value="makita">
                            <span>Makita</span>
                        </label>
                        <label class="filter-item">
                            <input type="checkbox" name="brand" value="milwaukee">
                            <span>Milwaukee</span>
                        </label>
                    </div>
                </div>
                
                <!-- Reset Filters -->
                <button class="btn btn-secondary btn-block">
                    <i class="fas fa-redo"></i> Reset Filters
                </button>
            </aside>
            
            <!-- Products Grid -->
            <div class="products-content">
                <!-- Toolbar -->
                <div class="products-toolbar">
                    <div class="toolbar-left">
                        <button class="filter-toggle" id="filterToggle">
                            <i class="fas fa-filter"></i>
                            Filters
                        </button>
                        <p class="products-count"><?= count($products) ?> Products Found</p>
                    </div>
                    
                    <div class="toolbar-right">
                        <div class="sort-group">
                            <label for="sortBy">Sort By:</label>
                            <select id="sortBy" class="form-select">
                                <option value="popular">Most Popular</option>
                                <option value="newest">Newest First</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="name">Name: A to Z</option>
                            </select>
                        </div>
                        
                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="view-btn" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
                    <?php if (empty($products)): ?>
                        <div class="no-products">
                            <i class="fas fa-box-open"></i>
                            <h3>No Products Found</h3>
                            <p>Try adjusting your filters or search criteria</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['title']) ?>" loading="lazy">
                                    <div class="product-actions">
                                        <button class="product-action-btn" title="Add to Wishlist">
                                            <i class="far fa-heart"></i>
                                        </button>
                                        <button class="product-action-btn" title="Quick View">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <h3 class="product-title">
                                        <a href="/host/mod/products/<?= $product['slug'] ?>"><?= htmlspecialchars($product['title']) ?></a>
                                    </h3>
                                    <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                                    <div class="product-footer">
                                        <span class="product-price"><?= number_format($product['price'], 2) ?> SAR</span>
                                        <a href="/host/mod/products/<?= $product['slug'] ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View Product
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if (!empty($products)): ?>
                    <div class="pagination">
                        <button class="pagination-btn" <?= ($currentPage <= 1) ? 'disabled' : '' ?>>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <button class="pagination-btn <?= ($i == $currentPage) ? 'active' : '' ?>">
                                <?= $i ?>
                            </button>
                        <?php endfor; ?>
                        
                        <button class="pagination-btn" <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

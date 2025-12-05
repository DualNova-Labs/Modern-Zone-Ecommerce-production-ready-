<?php
ob_start();
$searchQuery = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
?>

<!-- Products Page -->
<section class="products-page">
    <div class="container">
        <div class="products-layout">
            <!-- Sidebar Filters -->
            <aside class="products-sidebar">
                <!-- Main Filter Card -->
                <div class="filter-card">
                    <!-- Filter Header -->
                    <div class="filter-header">
                        <div class="filter-header-title">
                            <div class="filter-icon-wrapper">
                                <i class="fas fa-sliders-h"></i>
                            </div>
                            <span>Filters</span>
                        </div>
                        <?php if ($searchQuery || $categoryFilter): ?>
                            <a href="<?= View::url('/products') ?>" class="clear-filters-link">
                                <i class="fas fa-times-circle"></i> Clear All
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Active Filters Pills -->
                    <?php if ($searchQuery || $categoryFilter): ?>
                    <div class="active-filter-pills">
                        <?php if ($categoryFilter): ?>
                            <span class="filter-pill">
                                <i class="fas fa-folder"></i>
                                <?= htmlspecialchars($categoryFilter) ?>
                                <a href="<?= View::url('/products' . ($searchQuery ? '?search=' . urlencode($searchQuery) : '')) ?>" class="pill-remove">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        <?php endif; ?>
                        <?php if ($searchQuery): ?>
                            <span class="filter-pill">
                                <i class="fas fa-search"></i>
                                "<?= htmlspecialchars($searchQuery) ?>"
                                <a href="<?= View::url('/products' . ($categoryFilter ? '?category=' . $categoryFilter : '')) ?>" class="pill-remove">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Categories Accordion -->
                    <div class="filter-accordion">
                        <div class="accordion-item active">
                            <button class="accordion-header" type="button">
                                <div class="accordion-title">
                                    <i class="fas fa-layer-group"></i>
                                    <span>Categories</span>
                                </div>
                                <i class="fas fa-chevron-down accordion-arrow"></i>
                            </button>
                            <div class="accordion-content">
                                <div class="category-list">
                                    <label class="category-item <?= empty($categoryFilter) ? 'selected' : '' ?>">
                                        <input type="radio" name="category" value="" <?= empty($categoryFilter) ? 'checked' : '' ?>>
                                        <span class="category-checkbox">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="category-name">All Products</span>
                                        <span class="category-count"><?= count($products) ?></span>
                                    </label>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $cat): ?>
                                            <label class="category-item <?= $categoryFilter === $cat['slug'] ? 'selected' : '' ?>">
                                                <input type="radio" name="category" value="<?= $cat['slug'] ?>" <?= $categoryFilter === $cat['slug'] ? 'checked' : '' ?>>
                                                <span class="category-checkbox">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                                <span class="category-name"><?= htmlspecialchars($cat['name']) ?></span>
                                                <span class="category-count"><?= $cat['count'] ?? 0 ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Range Accordion -->
                        <div class="accordion-item active">
                            <button class="accordion-header" type="button">
                                <div class="accordion-title">
                                    <i class="fas fa-tag"></i>
                                    <span>Price Range</span>
                                </div>
                                <i class="fas fa-chevron-down accordion-arrow"></i>
                            </button>
                            <div class="accordion-content">
                                <!-- Price Slider -->
                                <div class="price-slider-container">
                                    <div class="price-slider-track">
                                        <div class="price-slider-range" id="priceRange"></div>
                                        <input type="range" class="price-slider" id="priceMin" min="0" max="1000" value="0">
                                        <input type="range" class="price-slider" id="priceMax" min="0" max="1000" value="1000">
                                    </div>
                                </div>
                                
                                <!-- Price Inputs -->
                                <div class="price-inputs-row">
                                    <div class="price-input-group">
                                        <span class="price-currency">SAR</span>
                                        <input type="number" id="minPriceInput" placeholder="0" min="0" value="0">
                                    </div>
                                    <span class="price-divider">—</span>
                                    <div class="price-input-group">
                                        <span class="price-currency">SAR</span>
                                        <input type="number" id="maxPriceInput" placeholder="1000" min="0" value="1000">
                                    </div>
                                </div>
                                
                                <button class="apply-price-btn" type="button">
                                    <i class="fas fa-check"></i> Apply Price Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Divider -->
                    <div class="filter-divider"></div>
                    
                    <!-- Quick Links Section -->
                    <div class="quick-filters-section">
                        <h4 class="quick-filters-title">
                            <i class="fas fa-bolt"></i> Quick Filters
                        </h4>
                        <div class="quick-filter-pills">
                            <a href="<?= View::url('/products?featured=1') ?>" class="quick-pill">
                                <i class="fas fa-star"></i>
                                <span>Featured</span>
                            </a>
                            <a href="<?= View::url('/products?bestseller=1') ?>" class="quick-pill bestseller">
                                <i class="fas fa-fire-alt"></i>
                                <span>Best Sellers</span>
                            </a>
                            <a href="<?= View::url('/products?new=1') ?>" class="quick-pill new-arrival">
                                <i class="fas fa-sparkles"></i>
                                <span>New Arrivals</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Help Support Card -->
                <div class="support-card">
                    <div class="support-card-bg"></div>
                    <div class="support-content">
                        <div class="support-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4 class="support-title">Need Help?</h4>
                        <p class="support-text">Our product experts are ready to assist you with any questions</p>
                        <a href="<?= View::url('/contact') ?>" class="support-btn">
                            <i class="fas fa-comments"></i>
                            Contact Us
                        </a>
                    </div>
                </div>
            </aside>
            
            <!-- Mobile Sort & Filter Bar (Flipkart Style) -->
            <div class="mobile-filter-bar">
                <button class="mobile-filter-btn" id="mobileSortBtn">
                    <i class="fas fa-sort"></i>
                    <span>Sort</span>
                </button>
                <div class="mobile-filter-divider"></div>
                <button class="mobile-filter-btn" id="mobileFilterBtn">
                    <i class="fas fa-filter"></i>
                    <span>Filter</span>
                </button>
            </div>
            
            <!-- Mobile Filter Modal (Flipkart Style) -->
            <div class="mobile-filter-modal" id="mobileFilterModal">
                <div class="mobile-filter-overlay" id="filterOverlay"></div>
                <div class="mobile-filter-sheet">
                    <!-- Header -->
                    <div class="filter-sheet-header">
                        <button class="filter-back-btn" id="filterCloseBtn">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <span class="filter-sheet-title">Filters</span>
                    </div>
                    
                    <!-- Body -->
                    <div class="filter-sheet-body">
                        <!-- Left: Filter Categories -->
                        <div class="filter-categories">
                            <button class="filter-cat-btn active" data-filter="category">
                                Category
                            </button>
                            <button class="filter-cat-btn" data-filter="price">
                                Price
                            </button>
                            <button class="filter-cat-btn" data-filter="quick">
                                Quick Filters
                            </button>
                        </div>
                        
                        <!-- Right: Filter Options -->
                        <div class="filter-options">
                            <!-- Category Options -->
                            <div class="filter-option-panel active" id="filter-category">
                                <label class="filter-checkbox-item <?= empty($categoryFilter) ? 'checked' : '' ?>">
                                    <input type="checkbox" name="mob_category" value="" <?= empty($categoryFilter) ? 'checked' : '' ?>>
                                    <span class="checkbox-box"></span>
                                    <span class="checkbox-label">All Products</span>
                                </label>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <label class="filter-checkbox-item <?= $categoryFilter === $cat['slug'] ? 'checked' : '' ?>">
                                            <input type="checkbox" name="mob_category" value="<?= $cat['slug'] ?>" <?= $categoryFilter === $cat['slug'] ? 'checked' : '' ?>>
                                            <span class="checkbox-box"></span>
                                            <span class="checkbox-label"><?= htmlspecialchars($cat['name']) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Price Options -->
                            <div class="filter-option-panel" id="filter-price">
                                <label class="filter-checkbox-item">
                                    <input type="checkbox" name="mob_price" value="0-100">
                                    <span class="checkbox-box"></span>
                                    <span class="checkbox-label">SAR 0 - SAR 100</span>
                                </label>
                                <label class="filter-checkbox-item">
                                    <input type="checkbox" name="mob_price" value="100-500">
                                    <span class="checkbox-box"></span>
                                    <span class="checkbox-label">SAR 100 - SAR 500</span>
                                </label>
                                <label class="filter-checkbox-item">
                                    <input type="checkbox" name="mob_price" value="500-1000">
                                    <span class="checkbox-box"></span>
                                    <span class="checkbox-label">SAR 500 - SAR 1000</span>
                                </label>
                                <label class="filter-checkbox-item">
                                    <input type="checkbox" name="mob_price" value="1000+">
                                    <span class="checkbox-box"></span>
                                    <span class="checkbox-label">SAR 1000 and Above</span>
                                </label>
                            </div>
                            
                            <!-- Quick Filters -->
                            <div class="filter-option-panel" id="filter-quick">
                                <label class="filter-checkbox-item">
                                    <input type="checkbox" name="mob_quick" value="featured">
                                    <span class="checkbox-box"></span>
                                    <span class="checkbox-label">Featured Products</span>
                                </label>
                                <label class="filter-checkbox-item">
                                    <input type="checkbox" name="mob_quick" value="bestseller">
                                    <span class="checkbox-box"></span>
                                    <span class="checkbox-label">Best Sellers</span>
                                </label>
                                <label class="filter-checkbox-item">
                                    <input type="checkbox" name="mob_quick" value="new">
                                    <span class="checkbox-box"></span>
                                    <span class="checkbox-label">New Arrivals</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="filter-sheet-footer">
                        <div class="filter-footer-info">
                            <span class="filter-count"><?= count($products) ?></span>
                            <span>products found</span>
                        </div>
                        <button class="filter-apply-btn" id="applyFiltersBtn">Apply</button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Sort Modal -->
            <div class="mobile-sort-modal" id="mobileSortModal">
                <div class="mobile-sort-overlay" id="sortOverlay"></div>
                <div class="mobile-sort-sheet">
                    <div class="sort-sheet-header">
                        <span>Sort By</span>
                        <button class="sort-close-btn" id="sortCloseBtn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="sort-options">
                        <label class="sort-option">
                            <input type="radio" name="mobile_sort" value="popular" checked>
                            <span>Most Popular</span>
                        </label>
                        <label class="sort-option">
                            <input type="radio" name="mobile_sort" value="newest">
                            <span>Newest First</span>
                        </label>
                        <label class="sort-option">
                            <input type="radio" name="mobile_sort" value="price-low">
                            <span>Price: Low to High</span>
                        </label>
                        <label class="sort-option">
                            <input type="radio" name="mobile_sort" value="price-high">
                            <span>Price: High to Low</span>
                        </label>
                        <label class="sort-option">
                            <input type="radio" name="mobile_sort" value="name">
                            <span>Name: A to Z</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Main Products Area -->
            <main class="products-main">
                <!-- Toolbar (Desktop) -->
                <div class="products-toolbar desktop-toolbar">
                    <div class="toolbar-info">
                        <span class="results-count">
                            <strong><?= count($products) ?></strong> products found
                            <?php if ($searchQuery): ?>
                                for "<em><?= htmlspecialchars($searchQuery) ?></em>"
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <div class="toolbar-actions">
                        <div class="sort-dropdown">
                            <label><i class="fas fa-sort-amount-down"></i></label>
                            <select id="sortBy" class="sort-select">
                                <option value="popular">Most Popular</option>
                                <option value="newest">Newest First</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="name">Name: A to Z</option>
                            </select>
                        </div>
                        
                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid" title="Grid View">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button class="view-btn" data-view="list" title="List View">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Active Filters -->
                <?php if ($searchQuery || $categoryFilter): ?>
                <div class="active-filters">
                    <span class="active-filters-label">Active Filters:</span>
                    <?php if ($searchQuery): ?>
                        <span class="filter-tag">
                            Search: <?= htmlspecialchars($searchQuery) ?>
                            <a href="<?= View::url('/products' . ($categoryFilter ? '?category=' . $categoryFilter : '')) ?>"><i class="fas fa-times"></i></a>
                        </span>
                    <?php endif; ?>
                    <?php if ($categoryFilter): ?>
                        <span class="filter-tag">
                            Category: <?= htmlspecialchars($categoryFilter) ?>
                            <a href="<?= View::url('/products' . ($searchQuery ? '?search=' . urlencode($searchQuery) : '')) ?>"><i class="fas fa-times"></i></a>
                        </span>
                    <?php endif; ?>
                    <a href="<?= View::url('/products') ?>" class="clear-all">Clear All</a>
                </div>
                <?php endif; ?>
                
                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
                    <?php if (empty($products)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h3 class="empty-state-title">No Products Found</h3>
                            <p class="empty-state-text">We couldn't find any products matching your criteria. Try adjusting your filters or browse all products.</p>
                            <a href="<?= View::url('/products') ?>" class="empty-state-btn">
                                <i class="fas fa-arrow-left"></i> Browse All Products
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <article class="product-card">
                                <!-- Image Section -->
                                <div class="card-image-section">
                                    <!-- Refined Badges -->
                                    <div class="card-badges">
                                        <?php if (!empty($product['featured'])): ?>
                                            <span class="card-badge badge-featured">
                                                <i class="fas fa-star"></i> Featured
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($product['best_seller'])): ?>
                                            <span class="card-badge badge-hot">
                                                <i class="fas fa-fire-alt"></i> Hot
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Quick Actions -->
                                    <div class="card-quick-actions">
                                        <button class="quick-action-btn wishlist-btn" title="Add to Wishlist">
                                            <i class="far fa-heart"></i>
                                        </button>
                                        <a href="<?= View::url('/products/' . $product['slug']) ?>" class="quick-action-btn" title="Quick View">
                                            <i class="fas fa-expand"></i>
                                        </a>
                                    </div>
                                    
                                    <!-- Product Image -->
                                    <a href="<?= View::url('/products/' . $product['slug']) ?>" class="card-image-link">
                                        <div class="card-image-container">
                                            <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['title']) ?>" loading="lazy">
                                        </div>
                                    </a>
                                </div>
                                
                                <!-- Content Section -->
                                <div class="card-content">
                                    <!-- Category -->
                                    <?php if (!empty($product['category_name'])): ?>
                                        <span class="card-category"><?= htmlspecialchars($product['category_name']) ?></span>
                                    <?php endif; ?>
                                    
                                    <!-- Title -->
                                    <h3 class="card-title">
                                        <a href="<?= View::url('/products/' . $product['slug']) ?>"><?= htmlspecialchars($product['title']) ?></a>
                                    </h3>
                                    
                                    <!-- SKU -->
                                    <?php if (!empty($product['sku'])): ?>
                                        <span class="card-sku">SKU: <?= htmlspecialchars($product['sku']) ?></span>
                                    <?php endif; ?>
                                    
                                    <!-- Price -->
                                    <div class="card-price-section">
                                        <span class="card-price">
                                            <span class="price-amount"><?= number_format($product['price'], 2) ?></span>
                                            <span class="price-currency">SAR</span>
                                        </span>
                                    </div>
                                    
                                    <!-- CTA Button -->
                                    <button class="card-add-to-cart add-to-cart" data-id="<?= $product['id'] ?? $product['slug'] ?>">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span>Add to Cart</span>
                                    </button>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if (!empty($products) && $totalPages > 1): ?>
                    <div class="pagination-wrapper">
                        <div class="pagination">
                            <a href="?page=<?= max(1, $currentPage - 1) ?>" 
                               class="pagination-btn pagination-prev <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                <i class="fas fa-chevron-left"></i>
                                <span>Previous</span>
                            </a>
                            
                            <div class="pagination-numbers">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="?page=<?= $i ?>" 
                                       class="pagination-num <?= ($i == $currentPage) ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                            
                            <a href="?page=<?= min($totalPages, $currentPage + 1) ?>" 
                               class="pagination-btn pagination-next <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                <span>Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                        
                        <div class="pagination-info">
                            Showing page <?= $currentPage ?> of <?= $totalPages ?>
                        </div>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</section>

<style>
/* ===== Products Page Layout ===== */
.products-page {
    padding: 2.5rem 0 4rem;
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    min-height: 60vh;
}

.products-page .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.products-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    align-items: start;
}

/* ===== Premium Sidebar ===== */
.products-sidebar {
    position: sticky;
    top: 20px;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* Filter Card */
.filter-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 0;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.04);
}

/* Filter Header */
.filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #fafbfc 0%, #ffffff 100%);
    border-bottom: 1px solid #f0f1f3;
}

.filter-header-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1rem;
    font-weight: 700;
    color: #1a1a2e;
}

.filter-icon-wrapper {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #ff6b35 0%, #ff8f5a 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(255,107,53,0.3);
}

.filter-icon-wrapper i {
    color: white;
    font-size: 0.9rem;
}

.clear-filters-link {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8rem;
    color: #888;
    text-decoration: none;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    transition: all 0.2s ease;
    background: #f5f5f5;
}

.clear-filters-link:hover {
    background: #fee2e2;
    color: #dc2626;
}

.clear-filters-link i {
    font-size: 0.7rem;
}

/* Active Filter Pills */
.active-filter-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: #fafbfc;
    border-bottom: 1px solid #f0f1f3;
}

.filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.875rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 50px;
    font-size: 0.8rem;
    color: #374151;
    font-weight: 500;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}

.filter-pill i {
    font-size: 0.7rem;
    color: #ff6b35;
}

.pill-remove {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    background: #f3f4f6;
    border-radius: 50%;
    color: #6b7280;
    margin-left: 0.25rem;
    transition: all 0.2s;
}

.pill-remove:hover {
    background: #fee2e2;
    color: #dc2626;
}

.pill-remove i {
    font-size: 0.6rem;
    color: inherit;
}

/* Accordion */
.filter-accordion {
    padding: 0.5rem 0;
}

.accordion-item {
    border-bottom: 1px solid #f0f1f3;
}

.accordion-item:last-child {
    border-bottom: none;
}

.accordion-header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.accordion-header:hover {
    background: #fafbfc;
}

.accordion-title {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: #374151;
}

.accordion-title i {
    width: 18px;
    color: #9ca3af;
    font-size: 0.85rem;
}

.accordion-arrow {
    font-size: 0.7rem;
    color: #9ca3af;
    transition: transform 0.3s ease;
}

.accordion-item.active .accordion-arrow {
    transform: rotate(180deg);
}

.accordion-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
}

.accordion-item.active .accordion-content {
    max-height: 500px;
    padding: 0 1.5rem 1.25rem;
}

/* Category List */
.category-list {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.625rem 0.875rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.category-item:hover {
    background: #f8f9fa;
}

.category-item.selected {
    background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
}

.category-item input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.category-checkbox {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.category-checkbox i {
    font-size: 0.6rem;
    color: white;
    opacity: 0;
    transform: scale(0);
    transition: all 0.2s ease;
}

.category-item.selected .category-checkbox {
    background: linear-gradient(135deg, #ff6b35 0%, #ff8f5a 100%);
    border-color: #ff6b35;
    box-shadow: 0 2px 8px rgba(255,107,53,0.3);
}

.category-item.selected .category-checkbox i {
    opacity: 1;
    transform: scale(1);
}

.category-name {
    flex: 1;
    font-size: 0.875rem;
    color: #4b5563;
    font-weight: 500;
}

.category-item.selected .category-name {
    color: #1f2937;
    font-weight: 600;
}

.category-count {
    font-size: 0.75rem;
    color: #9ca3af;
    background: #f3f4f6;
    padding: 0.125rem 0.5rem;
    border-radius: 10px;
    font-weight: 500;
}

.category-item.selected .category-count {
    background: #ff6b35;
    color: white;
}

/* Price Slider */
.price-slider-container {
    padding: 0.5rem 0 1.5rem;
}

.price-slider-track {
    position: relative;
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
}

.price-slider-range {
    position: absolute;
    height: 100%;
    background: linear-gradient(90deg, #ff6b35 0%, #ff8f5a 100%);
    border-radius: 3px;
    left: 0%;
    right: 0%;
}

.price-slider {
    position: absolute;
    width: 100%;
    height: 6px;
    -webkit-appearance: none;
    appearance: none;
    background: transparent;
    pointer-events: none;
    top: 0;
}

.price-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    background: white;
    border: 3px solid #ff6b35;
    border-radius: 50%;
    cursor: pointer;
    pointer-events: auto;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: transform 0.2s, box-shadow 0.2s;
}

.price-slider::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(255,107,53,0.3);
}

.price-slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    background: white;
    border: 3px solid #ff6b35;
    border-radius: 50%;
    cursor: pointer;
    pointer-events: auto;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* Price Inputs */
.price-inputs-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.price-input-group {
    flex: 1;
    display: flex;
    align-items: center;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s;
}

.price-input-group:focus-within {
    border-color: #ff6b35;
    background: white;
    box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
}

.price-currency {
    font-size: 0.75rem;
    color: #9ca3af;
    font-weight: 600;
    margin-right: 0.5rem;
}

.price-input-group input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 0.875rem;
    color: #1f2937;
    font-weight: 500;
    width: 100%;
    outline: none;
}

.price-input-group input::placeholder {
    color: #9ca3af;
}

.price-divider {
    color: #d1d5db;
    font-weight: 300;
}

.apply-price-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.apply-price-btn:hover {
    background: linear-gradient(135deg, #ff6b35 0%, #ff8f5a 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255,107,53,0.3);
}

.apply-price-btn i {
    font-size: 0.75rem;
}

/* Filter Divider */
.filter-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent 0%, #e5e7eb 50%, transparent 100%);
    margin: 0.5rem 1.5rem;
}

/* Quick Filters Section */
.quick-filters-section {
    padding: 1rem 1.5rem 1.5rem;
}

.quick-filters-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 0.875rem 0;
}

.quick-filters-title i {
    color: #fbbf24;
    font-size: 0.75rem;
}

.quick-filter-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.quick-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 0.875rem;
    background: #f3f4f6;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
    color: #4b5563;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.quick-pill i {
    font-size: 0.7rem;
    color: #fbbf24;
}

.quick-pill:hover {
    background: #fff7ed;
    border-color: #fed7aa;
    color: #ea580c;
}

.quick-pill:hover i {
    color: #ea580c;
}

.quick-pill.bestseller i {
    color: #ef4444;
}

.quick-pill.bestseller:hover {
    background: #fef2f2;
    border-color: #fecaca;
    color: #dc2626;
}

.quick-pill.bestseller:hover i {
    color: #dc2626;
}

.quick-pill.new-arrival i {
    color: #22c55e;
}

.quick-pill.new-arrival:hover {
    background: #f0fdf4;
    border-color: #bbf7d0;
    color: #16a34a;
}

.quick-pill.new-arrival:hover i {
    color: #16a34a;
}

/* Support Card */
.support-card {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
}

.support-card-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
}

.support-card-bg::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255,107,53,0.15) 0%, transparent 70%);
}

.support-card-bg::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -30%;
    width: 80%;
    height: 80%;
    background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, transparent 70%);
}

.support-content {
    position: relative;
    z-index: 1;
    padding: 1.75rem 1.5rem;
    text-align: center;
}

.support-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, rgba(255,107,53,0.2) 0%, rgba(255,143,90,0.1) 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    border: 1px solid rgba(255,107,53,0.2);
}

.support-icon i {
    font-size: 1.375rem;
    color: #ff6b35;
}

.support-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.125rem;
    font-weight: 700;
    color: white;
}

.support-text {
    margin: 0 0 1.25rem 0;
    font-size: 0.875rem;
    color: rgba(255,255,255,0.6);
    line-height: 1.5;
}

.support-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.875rem 1.5rem;
    background: linear-gradient(135deg, #ff6b35 0%, #ff8f5a 100%);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(255,107,53,0.3);
}

.support-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(255,107,53,0.4);
}

.support-btn i {
    font-size: 0.9rem;
}

/* ===== Main Products Area ===== */
.products-main {
    min-width: 0;
}

/* Toolbar */
.products-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.results-count {
    font-size: 0.95rem;
    color: #666;
}

.results-count strong {
    color: #1a1a2e;
}

.results-count em {
    color: #ff6b35;
    font-style: normal;
    font-weight: 600;
}

.toolbar-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.sort-dropdown {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sort-dropdown label {
    color: #888;
}

.sort-select {
    padding: 0.5rem 2rem 0.5rem 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 0.875rem;
    background: white;
    cursor: pointer;
    transition: all 0.3s;
}

.sort-select:focus {
    outline: none;
    border-color: #ff6b35;
}

.view-toggle {
    display: flex;
    background: #f5f5f5;
    border-radius: 8px;
    padding: 0.25rem;
}

.view-btn {
    padding: 0.5rem 0.75rem;
    border: none;
    background: transparent;
    border-radius: 6px;
    cursor: pointer;
    color: #888;
    transition: all 0.2s;
}

.view-btn:hover {
    color: #ff6b35;
}

.view-btn.active {
    background: #ff6b35;
    color: white;
}

/* Active Filters */
.active-filters {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: #fff8f5;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.active-filters-label {
    font-size: 0.875rem;
    color: #666;
    font-weight: 500;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.375rem 0.75rem;
    background: white;
    border: 1px solid #ff6b35;
    border-radius: 50px;
    font-size: 0.8125rem;
    color: #ff6b35;
}

.filter-tag a {
    color: #ff6b35;
    display: flex;
}

.filter-tag a:hover {
    color: #e55a2b;
}

.clear-all {
    margin-left: auto;
    color: #ff6b35;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
}

.clear-all:hover {
    text-decoration: underline;
}

/* ===== Products Grid ===== */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

/* ===== Compact Product Card ===== */
.product-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 
                0 1px 2px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
    border: 1px solid #f0f0f0;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #e0e0e0;
}

/* Card Image Section */
.card-image-section {
    position: relative;
    background: #ffffff;
    padding: 0.75rem;
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Refined Badges */
.card-badges {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    z-index: 10;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.card-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    padding: 0.2rem 0.5rem;
    border-radius: 3px;
    font-size: 0.6rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}

.card-badge i {
    font-size: 0.5rem;
}

.card-badge.badge-featured {
    background: #fff3cd;
    color: #856404;
}

.card-badge.badge-hot {
    background: #f8d7da;
    color: #721c24;
}

/* Quick Actions - Wishlist only */
.card-quick-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    z-index: 10;
}

.quick-action-btn {
    width: 28px;
    height: 28px;
    border: none;
    background: transparent;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.85rem;
    color: #9ca3af;
    transition: all 0.2s ease;
    text-decoration: none;
}

.quick-action-btn:hover {
    color: #ef4444;
}

.quick-action-btn.wishlist-btn:hover i {
    font-weight: 900;
}

/* Hide second action button */
.card-quick-actions .quick-action-btn:nth-child(2) {
    display: none;
}

/* Image Container */
.card-image-link {
    display: block;
    width: 100%;
    height: 100%;
}

.card-image-container {
    width: 100%;
    height: 130px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-image-container img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.product-card:hover .card-image-container img {
    transform: scale(1.05);
}

/* Card Content */
.card-content {
    padding: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
    background: white;
    border-top: 1px solid #f5f5f5;
}

/* Category Label */
.card-category {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #2874f0;
    font-weight: 500;
    display: none;
}

/* Title */
.card-title {
    font-size: 0.8rem;
    font-weight: 500;
    margin: 0;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.2em;
    color: #212121;
}

.card-title a {
    color: #212121;
    text-decoration: none;
}

.card-title a:hover {
    color: #2874f0;
}

/* SKU */
.card-sku {
    font-size: 0.65rem;
    color: #878787;
    font-weight: 400;
}

/* Price Section */
.card-price-section {
    margin-top: 0.375rem;
}

.card-price {
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
}

.price-amount {
    font-size: 1rem;
    font-weight: 600;
    color: #212121;
    line-height: 1;
}

.price-currency {
    font-size: 0.7rem;
    font-weight: 500;
    color: #878787;
}

/* CTA Button */
.card-add-to-cart {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
    width: 100%;
    padding: 0.5rem 0.75rem;
    margin-top: 0.5rem;
    background: #ff6b35;
    color: white;
    border: none;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.card-add-to-cart:hover {
    background: #e55a2b;
}

.card-add-to-cart i {
    font-size: 0.7rem;
}

.card-add-to-cart span {
    font-weight: 600;
}

/* ===== Empty State ===== */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 2rem;
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: #f9fafb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
    border: 1px solid #e5e7eb;
}

.empty-state-icon i {
    font-size: 2rem;
    color: #9ca3af;
}

.empty-state-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.empty-state-text {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0 0 1.5rem 0;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.5;
}

.empty-state-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: #1f2937;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.empty-state-btn:hover {
    background: #374151;
}

.empty-state-btn i {
    font-size: 0.75rem;
}

/* ===== Mobile Filter Bar (Flipkart Style) ===== */
.mobile-filter-bar {
    display: none;
    position: sticky;
    top: 0;
    z-index: 100;
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.mobile-filter-bar {
    display: none;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
}

.mobile-filter-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 1rem;
    background: none;
    border: none;
    font-size: 0.875rem;
    font-weight: 500;
    color: #212121;
    cursor: pointer;
}

.mobile-filter-btn i {
    font-size: 0.8rem;
    color: #666;
}

.mobile-filter-divider {
    width: 1px;
    height: 24px;
    background: #e0e0e0;
}

/* Mobile Filter Modal */
.mobile-filter-modal,
.mobile-sort-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1000;
}

.mobile-filter-modal.active,
.mobile-sort-modal.active {
    display: block;
}

.mobile-filter-overlay,
.mobile-sort-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
}

/* Filter Sheet */
.mobile-filter-sheet {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: #fff;
    display: flex;
    flex-direction: column;
}

.filter-sheet-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.875rem 1rem;
    border-bottom: 1px solid #e0e0e0;
    background: #fff;
}

.filter-back-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    font-size: 1rem;
    color: #212121;
    cursor: pointer;
}

.filter-sheet-title {
    font-size: 1rem;
    font-weight: 500;
    color: #212121;
}

.filter-sheet-body {
    flex: 1;
    display: flex;
    overflow: hidden;
}

/* Filter Categories (Left Sidebar) */
.filter-categories {
    width: 140px;
    background: #f5f5f5;
    border-right: 1px solid #e0e0e0;
    overflow-y: auto;
}

.filter-cat-btn {
    display: block;
    width: 100%;
    padding: 1rem 0.875rem;
    text-align: left;
    background: none;
    border: none;
    border-left: 3px solid transparent;
    font-size: 0.8125rem;
    font-weight: 400;
    color: #666;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-cat-btn:hover {
    background: #eee;
}

.filter-cat-btn.active {
    background: #fff;
    border-left-color: #2874f0;
    color: #2874f0;
    font-weight: 500;
}

/* Filter Options (Right Panel) */
.filter-options {
    flex: 1;
    overflow-y: auto;
    background: #fff;
}

.filter-option-panel {
    display: none;
    padding: 0.5rem 0;
}

.filter-option-panel.active {
    display: block;
}

.filter-checkbox-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    cursor: pointer;
    transition: background 0.2s;
}

.filter-checkbox-item:hover {
    background: #f9f9f9;
}

.filter-checkbox-item input {
    display: none;
}

.checkbox-box {
    width: 18px;
    height: 18px;
    border: 2px solid #c2c2c2;
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}

.checkbox-box::after {
    content: '';
    width: 10px;
    height: 6px;
    border-left: 2px solid #fff;
    border-bottom: 2px solid #fff;
    transform: rotate(-45deg) scale(0);
    transition: transform 0.2s;
    margin-top: -2px;
}

.filter-checkbox-item.checked .checkbox-box,
.filter-checkbox-item input:checked + .checkbox-box {
    background: #2874f0;
    border-color: #2874f0;
}

.filter-checkbox-item.checked .checkbox-box::after,
.filter-checkbox-item input:checked + .checkbox-box::after {
    transform: rotate(-45deg) scale(1);
}

.checkbox-label {
    font-size: 0.875rem;
    color: #212121;
}

/* Filter Footer */
.filter-sheet-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.875rem 1rem;
    border-top: 1px solid #e0e0e0;
    background: #fff;
}

.filter-footer-info {
    font-size: 0.875rem;
    color: #666;
}

.filter-count {
    font-weight: 600;
    color: #212121;
}

.filter-apply-btn {
    padding: 0.75rem 2.5rem;
    background: #ff6b35;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 0.9375rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.filter-apply-btn:hover {
    background: #e55a2b;
}

/* Sort Sheet */
.mobile-sort-sheet {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 16px 16px 0 0;
    max-height: 70vh;
    overflow: hidden;
}

.sort-sheet-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e0e0e0;
}

.sort-sheet-header span {
    font-size: 1rem;
    font-weight: 600;
    color: #212121;
}

.sort-close-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    border: none;
    border-radius: 50%;
    font-size: 0.875rem;
    color: #666;
    cursor: pointer;
}

.sort-options {
    padding: 0.5rem 0;
}

.sort-option {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 1rem 1.25rem;
    cursor: pointer;
    transition: background 0.2s;
}

.sort-option:hover {
    background: #f9f9f9;
}

.sort-option input {
    width: 18px;
    height: 18px;
    accent-color: #2874f0;
}

.sort-option span {
    font-size: 0.9375rem;
    color: #212121;
}

/* ===== Pagination ===== */
.pagination-wrapper {
    text-align: center;
}

.pagination {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    padding: 0.75rem;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.pagination-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    background: transparent;
    border: none;
    border-radius: 8px;
    color: #666;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.pagination-btn:hover:not(.disabled) {
    background: #f5f5f5;
    color: #ff6b35;
}

.pagination-btn.disabled {
    opacity: 0.4;
    pointer-events: none;
}

.pagination-numbers {
    display: flex;
    gap: 0.25rem;
}

.pagination-num {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #666;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.pagination-num:hover {
    background: #f5f5f5;
    color: #ff6b35;
}

.pagination-num.active {
    background: #ff6b35;
    color: white;
}

.pagination-info {
    margin-top: 1rem;
    font-size: 0.875rem;
    color: #888;
}

/* ===== Responsive Design ===== */
@media (max-width: 1200px) {
    .products-layout {
        grid-template-columns: 260px 1fr;
    }
}

@media (max-width: 992px) {
    .products-layout {
        grid-template-columns: 1fr;
    }
    
    .products-sidebar {
        position: static;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .filter-card {
        order: 1;
    }
    
    .support-card {
        display: none;
    }
    
    /* Collapse filters by default on tablet */
    .accordion-item {
        border-bottom: none;
    }
    
    .accordion-item:not(.active) .accordion-content {
        max-height: 0;
        padding: 0;
    }
    
    .filter-header {
        padding: 0.875rem 1rem;
    }
    
    .filter-header-title {
        font-size: 0.9rem;
    }
    
    .filter-icon-wrapper {
        width: 32px;
        height: 32px;
        border-radius: 8px;
    }
    
    .filter-icon-wrapper i {
        font-size: 0.8rem;
    }
    
    .accordion-header {
        padding: 0.75rem 1rem;
    }
    
    .accordion-title {
        font-size: 0.8rem;
    }
    
    .accordion-item.active .accordion-content {
        padding: 0 1rem 0.75rem;
    }
    
    .quick-filters-section {
        padding: 0.75rem 1rem;
    }
    
    .quick-filters-title {
        font-size: 0.7rem;
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 768px) {
    .products-page {
        padding: 0 0 1rem;
    }
    
    .products-page .container {
        padding: 0;
    }
    
    .products-layout {
        gap: 0;
    }
    
    /* Hide desktop sidebar on mobile */
    .products-sidebar {
        display: none !important;
    }
    
    /* Hide desktop toolbar on mobile */
    .desktop-toolbar {
        display: none !important;
    }
    
    /* Hide active filters bar on mobile */
    .active-filters {
        display: none !important;
    }
    
    /* Show mobile filter bar */
    .mobile-filter-bar {
        display: grid !important;
        grid-template-columns: 1fr auto 1fr;
        margin-bottom: 0.5rem;
    }
    
    /* Products main area */
    .products-main {
        padding: 0 0.5rem;
    }
    
    .filter-card {
        border-radius: 10px;
    }
    
    .filter-header {
        padding: 0.75rem 1rem;
    }
    
    .filter-header-title span {
        font-size: 0.875rem;
    }
    
    .filter-icon-wrapper {
        width: 28px;
        height: 28px;
    }
    
    .clear-filters-link {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .active-filter-pills {
        padding: 0.5rem 1rem;
        gap: 0.375rem;
    }
    
    .filter-pill {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .pill-remove {
        width: 16px;
        height: 16px;
    }
    
    /* Accordion compact */
    .accordion-header {
        padding: 0.625rem 1rem;
    }
    
    .accordion-title {
        font-size: 0.8rem;
        gap: 0.5rem;
    }
    
    .accordion-title i {
        width: 14px;
        font-size: 0.75rem;
    }
    
    .accordion-item.active .accordion-content {
        padding: 0 1rem 0.625rem;
        max-height: 400px;
    }
    
    /* Category items compact */
    .category-item {
        padding: 0.5rem 0.625rem;
        gap: 0.5rem;
    }
    
    .category-checkbox {
        width: 18px;
        height: 18px;
    }
    
    .category-name {
        font-size: 0.8rem;
    }
    
    .category-count {
        font-size: 0.65rem;
        padding: 0.1rem 0.375rem;
    }
    
    /* Price filter compact */
    .price-slider-container {
        padding: 0.25rem 0 1rem;
    }
    
    .price-inputs-row {
        flex-direction: row;
        gap: 0.5rem;
    }
    
    .price-input-group {
        padding: 0.375rem 0.5rem;
    }
    
    .price-currency {
        font-size: 0.65rem;
    }
    
    .price-input-group input {
        font-size: 0.8rem;
    }
    
    .apply-price-btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 6px;
    }
    
    /* Quick filters compact */
    .quick-filters-section {
        padding: 0.625rem 1rem 0.75rem;
    }
    
    .quick-filters-title {
        font-size: 0.65rem;
        margin-bottom: 0.375rem;
    }
    
    .quick-filter-pills {
        gap: 0.25rem;
    }
    
    .quick-pill {
        padding: 0.3rem 0.5rem;
        font-size: 0.65rem;
        gap: 0.25rem;
    }
    
    .quick-pill i {
        font-size: 0.55rem;
    }
    
    /* Toolbar compact */
    .products-toolbar {
        flex-direction: row;
        align-items: center;
        padding: 0.625rem 0.75rem;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        gap: 0.5rem;
    }
    
    .toolbar-info {
        flex: 1;
    }
    
    .results-count {
        font-size: 0.8rem;
    }
    
    .results-count strong {
        font-size: 0.9rem;
    }
    
    .toolbar-actions {
        gap: 0.5rem;
    }
    
    .sort-dropdown {
        display: none;
    }
    
    .view-toggle {
        padding: 0.125rem;
        border-radius: 6px;
    }
    
    .view-btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
    }
    
    /* Products Grid */
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }
    
    /* Product Card Mobile */
    .product-card {
        border-radius: 8px;
        border: 1px solid #eee;
    }
    
    .card-image-section {
        height: 130px;
        padding: 0.5rem;
    }
    
    .card-image-container {
        height: 110px;
    }
    
    .card-badges {
        top: 0.375rem;
        left: 0.375rem;
        gap: 0.2rem;
    }
    
    .card-badge {
        font-size: 0.5rem;
        padding: 0.15rem 0.35rem;
    }
    
    .card-badge i {
        font-size: 0.45rem;
    }
    
    .card-quick-actions {
        top: 0.375rem;
        right: 0.375rem;
    }
    
    .quick-action-btn {
        width: 24px;
        height: 24px;
        font-size: 0.7rem;
    }
    
    .card-content {
        padding: 0.5rem;
        gap: 0.2rem;
    }
    
    .card-title {
        font-size: 0.75rem;
        min-height: auto;
        -webkit-line-clamp: 2;
        line-height: 1.3;
    }
    
    .card-sku {
        display: none;
    }
    
    .card-price-section {
        margin-top: 0.25rem;
        padding-top: 0;
        border-top: none;
    }
    
    .price-amount {
        font-size: 0.9rem;
        font-weight: 700;
    }
    
    .price-currency {
        font-size: 0.65rem;
    }
    
    .card-add-to-cart {
        padding: 0.4rem 0.5rem;
        margin-top: 0.375rem;
        font-size: 0.65rem;
        border-radius: 4px;
        gap: 0.25rem;
    }
    
    .card-add-to-cart i {
        font-size: 0.6rem;
    }
    
    /* Pagination compact */
    .pagination-wrapper {
        margin-top: 1rem;
    }
    
    .pagination {
        padding: 0.5rem;
        border-radius: 8px;
        gap: 0.25rem;
    }
    
    .pagination-btn {
        padding: 0.375rem 0.625rem;
        font-size: 0.75rem;
    }
    
    .pagination-btn span {
        display: none;
    }
    
    .pagination-numbers {
        gap: 0.125rem;
    }
    
    .pagination-num {
        min-width: 32px;
        height: 32px;
        font-size: 0.75rem;
    }
    
    .pagination-info {
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }
    
    /* Empty state compact */
    .empty-state {
        padding: 2rem 1rem;
    }
    
    .empty-state-icon {
        width: 60px;
        height: 60px;
        margin-bottom: 1rem;
    }
    
    .empty-state-icon i {
        font-size: 1.5rem;
    }
    
    .empty-state-title {
        font-size: 1rem;
    }
    
    .empty-state-text {
        font-size: 0.8rem;
    }
    
    .empty-state-btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 400px) {
    .products-page .container {
        padding: 0 0.5rem;
    }
    
    .products-grid {
        gap: 0.375rem;
    }
    
    .card-image-section {
        height: 110px;
    }
    
    .card-image-container {
        height: 90px;
    }
    
    .card-content {
        padding: 0.375rem;
    }
    
    .card-title {
        font-size: 0.7rem;
    }
    
    .price-amount {
        font-size: 0.8rem;
    }
    
    .card-add-to-cart {
        padding: 0.35rem 0.4rem;
        font-size: 0.6rem;
    }
    
    .filter-header {
        padding: 0.625rem 0.75rem;
    }
    
    .accordion-header {
        padding: 0.5rem 0.75rem;
    }
    
    .accordion-item.active .accordion-content {
        padding: 0 0.75rem 0.5rem;
    }
    
    .category-item {
        padding: 0.375rem 0.5rem;
    }
    
    .category-name {
        font-size: 0.75rem;
    }
    
    .quick-filters-section {
        padding: 0.5rem 0.75rem 0.625rem;
    }
    
    .quick-pill {
        padding: 0.25rem 0.4rem;
        font-size: 0.6rem;
    }
    
    .products-toolbar {
        padding: 0.5rem;
    }
    
    .results-count {
        font-size: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== Accordion Functionality =====
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const item = this.closest('.accordion-item');
            item.classList.toggle('active');
        });
    });
    
    // ===== Category Selection =====
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            const value = radio.value;
            
            // Update URL with category filter
            const url = new URL(window.location);
            if (value) {
                url.searchParams.set('category', value);
            } else {
                url.searchParams.delete('category');
            }
            window.location = url;
        });
    });
    
    // ===== Price Range Slider =====
    const priceMin = document.getElementById('priceMin');
    const priceMax = document.getElementById('priceMax');
    const minInput = document.getElementById('minPriceInput');
    const maxInput = document.getElementById('maxPriceInput');
    const priceRange = document.getElementById('priceRange');
    
    function updatePriceRange() {
        if (!priceMin || !priceMax || !priceRange) return;
        
        const min = parseInt(priceMin.value);
        const max = parseInt(priceMax.value);
        const total = parseInt(priceMin.max);
        
        const leftPercent = (min / total) * 100;
        const rightPercent = 100 - (max / total) * 100;
        
        priceRange.style.left = leftPercent + '%';
        priceRange.style.right = rightPercent + '%';
        
        if (minInput) minInput.value = min;
        if (maxInput) maxInput.value = max;
    }
    
    if (priceMin) {
        priceMin.addEventListener('input', function() {
            if (parseInt(this.value) > parseInt(priceMax.value) - 50) {
                this.value = parseInt(priceMax.value) - 50;
            }
            updatePriceRange();
        });
    }
    
    if (priceMax) {
        priceMax.addEventListener('input', function() {
            if (parseInt(this.value) < parseInt(priceMin.value) + 50) {
                this.value = parseInt(priceMin.value) + 50;
            }
            updatePriceRange();
        });
    }
    
    if (minInput) {
        minInput.addEventListener('change', function() {
            priceMin.value = this.value;
            updatePriceRange();
        });
    }
    
    if (maxInput) {
        maxInput.addEventListener('change', function() {
            priceMax.value = this.value;
            updatePriceRange();
        });
    }
    
    // Initialize price range
    updatePriceRange();
    
    // Apply Price Filter
    const applyPriceBtn = document.querySelector('.apply-price-btn');
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', function() {
            const url = new URL(window.location);
            url.searchParams.set('min_price', minInput.value);
            url.searchParams.set('max_price', maxInput.value);
            window.location = url;
        });
    }
    
    // ===== View Toggle =====
    const viewBtns = document.querySelectorAll('.view-btn');
    const productsGrid = document.getElementById('productsGrid');
    
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            if (this.dataset.view === 'list') {
                productsGrid.style.gridTemplateColumns = '1fr';
                productsGrid.classList.add('list-view');
            } else {
                productsGrid.style.gridTemplateColumns = '';
                productsGrid.classList.remove('list-view');
            }
        });
    });
    
    // ===== Sort Functionality =====
    const sortSelect = document.getElementById('sortBy');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const url = new URL(window.location);
            url.searchParams.set('sort', this.value);
            window.location = url;
        });
    }
    
    // ===== Mobile Filter Modal (Flipkart Style) =====
    const mobileFilterBtn = document.getElementById('mobileFilterBtn');
    const mobileFilterModal = document.getElementById('mobileFilterModal');
    const filterCloseBtn = document.getElementById('filterCloseBtn');
    const filterOverlay = document.getElementById('filterOverlay');
    const filterCatBtns = document.querySelectorAll('.filter-cat-btn');
    const filterPanels = document.querySelectorAll('.filter-option-panel');
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    
    // Open filter modal
    if (mobileFilterBtn) {
        mobileFilterBtn.addEventListener('click', function() {
            mobileFilterModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Close filter modal
    function closeFilterModal() {
        mobileFilterModal.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (filterCloseBtn) {
        filterCloseBtn.addEventListener('click', closeFilterModal);
    }
    
    if (filterOverlay) {
        filterOverlay.addEventListener('click', closeFilterModal);
    }
    
    // Switch filter categories
    filterCatBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filterType = this.dataset.filter;
            
            // Update active button
            filterCatBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding panel
            filterPanels.forEach(panel => {
                panel.classList.remove('active');
                if (panel.id === 'filter-' + filterType) {
                    panel.classList.add('active');
                }
            });
        });
    });
    
    // Handle checkbox clicks
    const filterCheckboxItems = document.querySelectorAll('.filter-checkbox-item');
    filterCheckboxItems.forEach(item => {
        item.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            const name = checkbox.name;
            
            // For category, only allow one selection
            if (name === 'mob_category') {
                document.querySelectorAll('input[name="mob_category"]').forEach(cb => {
                    cb.checked = false;
                    cb.closest('.filter-checkbox-item').classList.remove('checked');
                });
            }
            
            checkbox.checked = !checkbox.checked;
            this.classList.toggle('checked', checkbox.checked);
        });
    });
    
    // Apply filters
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            const url = new URL(window.location);
            
            // Get selected category
            const selectedCategory = document.querySelector('input[name="mob_category"]:checked');
            if (selectedCategory && selectedCategory.value) {
                url.searchParams.set('category', selectedCategory.value);
            } else {
                url.searchParams.delete('category');
            }
            
            // Get selected price ranges
            const selectedPrices = document.querySelectorAll('input[name="mob_price"]:checked');
            if (selectedPrices.length > 0) {
                const priceValues = Array.from(selectedPrices).map(p => p.value);
                url.searchParams.set('price', priceValues.join(','));
            }
            
            // Get quick filters
            const selectedQuick = document.querySelectorAll('input[name="mob_quick"]:checked');
            selectedQuick.forEach(q => {
                url.searchParams.set(q.value, '1');
            });
            
            window.location = url;
        });
    }
    
    // ===== Mobile Sort Modal =====
    const mobileSortBtn = document.getElementById('mobileSortBtn');
    const mobileSortModal = document.getElementById('mobileSortModal');
    const sortCloseBtn = document.getElementById('sortCloseBtn');
    const sortOverlay = document.getElementById('sortOverlay');
    const sortOptions = document.querySelectorAll('.sort-option input');
    
    // Open sort modal
    if (mobileSortBtn) {
        mobileSortBtn.addEventListener('click', function() {
            mobileSortModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Close sort modal
    function closeSortModal() {
        mobileSortModal.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (sortCloseBtn) {
        sortCloseBtn.addEventListener('click', closeSortModal);
    }
    
    if (sortOverlay) {
        sortOverlay.addEventListener('click', closeSortModal);
    }
    
    // Apply sort on selection
    sortOptions.forEach(option => {
        option.addEventListener('change', function() {
            const url = new URL(window.location);
            url.searchParams.set('sort', this.value);
            window.location = url;
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

<?php
ob_start();
?>

<!-- Breadcrumb -->
<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="<?= View::url('/') ?>">Home</a></li>
            <li>Products</li>
        </ul>
    </div>
</section>

<!-- Products Page -->
<section class="products-page">
    <div class="container">
        <!-- Toolbar -->
        <div class="products-toolbar">
            <div class="toolbar-left">
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
                    <p>Try adjusting your search criteria</p>
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
                                <a href="<?= View::url('/products/' . $product['slug']) ?>"><?= htmlspecialchars($product['title']) ?></a>
                            </h3>
                            <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                            <div class="product-footer">
                                <span class="product-price"><?= number_format($product['price'], 2) ?> SAR</span>
                                <a href="<?= View::url('/products/' . $product['slug']) ?>" class="btn btn-primary btn-sm">
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
</section>

<style>
/* ===== Container & Layout ===== */
.products-page {
    padding: 3rem 0;
    background: #f8f9fa;
    min-height: 60vh;
}

.products-page .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* ===== Toolbar ===== */
.products-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    gap: 1rem;
    flex-wrap: wrap;
}

.toolbar-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.products-count {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.toolbar-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.sort-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sort-group label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #666;
    white-space: nowrap;
}

.form-select {
    padding: 0.5rem 2rem 0.5rem 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.875rem;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 180px;
}

.form-select:focus {
    outline: none;
    border-color: #ff6b35;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.view-toggle {
    display: flex;
    gap: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 0.25rem;
    background: #f8f9fa;
}

.view-btn {
    padding: 0.5rem 0.75rem;
    border: none;
    background: transparent;
    border-radius: 6px;
    cursor: pointer;
    color: #666;
    transition: all 0.3s ease;
}

.view-btn:hover {
    background: white;
    color: #ff6b35;
}

.view-btn.active {
    background: #ff6b35;
    color: white;
    box-shadow: 0 2px 4px rgba(255, 107, 53, 0.2);
}

/* ===== Products Grid ===== */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
    justify-content: center;
}

/* ===== Product Card ===== */
.product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 320px;
    margin: 0 auto;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.product-image {
    position: relative;
    width: 100%;
    height: 240px;
    overflow: hidden;
    background: #f8f9fa;
}

.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.08);
}

.product-actions {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.product-action-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    font-size: 1rem;
}

.product-action-btn:hover {
    background: #ff6b35;
    color: white;
    transform: scale(1.1);
}

.product-content {
    padding: 1rem 1.25rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    color: #333;
}

.product-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-title a:hover {
    color: #ff6b35;
}

.product-description {
    display: none;
}

.product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.75rem;
    margin-top: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f0f0f0;
}

.product-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ff6b35;
    white-space: nowrap;
}

.btn {
    padding: 0.625rem 1rem;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
    transition: all 0.3s ease;
    white-space: nowrap;
    line-height: 1;
}

.btn-primary {
    background: #ff6b35;
    color: white;
    box-shadow: 0 2px 8px rgba(255, 107, 53, 0.2);
}

.btn-primary:hover {
    background: #e55a2b;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(255, 107, 53, 0.35);
}

.btn-sm {
    padding: 0.625rem 1rem;
    font-size: 0.8125rem;
}

.btn i {
    font-size: 0.875rem;
}

/* ===== No Products ===== */
.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.no-products i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.no-products h3 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.no-products p {
    color: #666;
}

/* ===== Pagination ===== */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.pagination-btn {
    min-width: 40px;
    height: 40px;
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    background: white;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination-btn:hover:not(:disabled) {
    border-color: #ff6b35;
    color: #ff6b35;
    background: #fff8f5;
}

.pagination-btn.active {
    background: #ff6b35;
    color: white;
    border-color: #ff6b35;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ===== Breadcrumb ===== */
.breadcrumb {
    background: white;
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
}

.breadcrumb-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.breadcrumb-list li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #666;
}

.breadcrumb-list li:not(:last-child)::after {
    content: '/';
    color: #ccc;
    margin-left: 0.5rem;
}

.breadcrumb-list a {
    color: #666;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-list a:hover {
    color: #ff6b35;
}

/* ===== Responsive Design ===== */

/* Large Desktop (max-width for cards) */
@media (min-width: 1400px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(330px, 340px));
    }
}

/* Desktop / Laptop */
@media (max-width: 1200px) {
    .products-page .container {
        max-width: 100%;
        padding: 0 1.5rem;
    }
    
    .products-grid {
        gap: 1.75rem;
    }
}

/* Tablet Landscape */
@media (max-width: 1024px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .product-card {
        max-width: 100%;
    }
}

/* Tablet Portrait (2 columns) */
@media (max-width: 768px) {
    .products-page .container {
        padding: 0 1rem;
    }
    
    .products-toolbar {
        flex-direction: column;
        align-items: stretch;
        padding: 1rem;
    }
    
    .toolbar-left,
    .toolbar-right {
        width: 100%;
        justify-content: space-between;
    }
    
    .toolbar-right {
        flex-direction: row;
        justify-content: space-between;
    }
    
    .sort-group {
        flex: 1;
    }
    
    .form-select {
        width: 100%;
        min-width: auto;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .product-card {
        max-width: 100%;
    }
    
    .product-image {
        height: 200px;
    }
    
    .product-content {
        padding: 0.875rem 1rem;
    }
    
    .product-title {
        font-size: 0.9375rem;
        margin-bottom: 0.375rem;
    }
    
    .product-price {
        font-size: 1.125rem;
    }
    
    .btn-sm {
        padding: 0.5rem 0.875rem;
        font-size: 0.75rem;
    }
}

/* Mobile (1 column) */
@media (max-width: 480px) {
    .products-page {
        padding: 2rem 0;
    }
    
    .products-toolbar {
        padding: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .products-count {
        font-size: 0.875rem;
    }
    
    .toolbar-right {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .sort-group {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
    }
    
    .sort-group label {
        font-size: 0.8125rem;
    }
    
    .view-toggle {
        width: 100%;
        justify-content: center;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
    
    .product-card {
        max-width: 380px;
        margin: 0 auto;
        width: 100%;
    }
    
    .product-image {
        height: 280px;
    }
    
    .product-content {
        padding: 1rem 1.25rem;
    }
    
    .product-title {
        font-size: 1rem;
    }
    
    .product-footer {
        flex-direction: row;
        align-items: center;
    }
    
    .product-price {
        font-size: 1.25rem;
    }
    
    .btn-sm {
        padding: 0.625rem 1rem;
        font-size: 0.8125rem;
    }
    
    .pagination-btn {
        min-width: 36px;
        height: 36px;
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }
}

/* Extra small devices */
@media (max-width: 360px) {
    .products-page .container {
        padding: 0 0.75rem;
    }
    
    .product-card {
        max-width: 100%;
    }
    
    .product-footer {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
    }
    
    .btn-sm {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

<?php
$pageTitle = 'Products';
$breadcrumb = 'Home / Products';
ob_start();
?>

<style>
    .section-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-content {
        padding: 2rem;
    }
    
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border: 1px solid;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border-color: #10b981;
    }
    
    .alert-error {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border-color: #ef4444;
    }
    
    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .search-form {
        display: flex;
        gap: 1rem;
        flex: 1;
        max-width: 700px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .search-form input,
    .search-form select {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .search-form input[type="text"] {
        flex: 1;
        min-width: 200px;
    }
    
    .search-form select {
        min-width: 150px;
    }
    
    .search-form input:focus,
    .search-form select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }
    
    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
    }
    
    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
    }
    
    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: white;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    
    .mobile-cards {
        display: none;
    }
    
    .product-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .product-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .product-card-info {
        flex: 1;
    }
    
    .product-card-id {
        font-size: 0.75rem;
        color: #6366f1;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .product-card-name {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }
    
    .product-card-brand {
        font-size: 0.875rem;
        color: #64748b;
    }
    
    .product-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .product-detail {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .product-detail-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .product-detail-value {
        font-size: 0.875rem;
        color: #1e293b;
        font-weight: 500;
    }
    
    .product-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }
    
    .product-card-status {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .product-card-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    th {
        text-align: left;
        padding: 1rem;
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    
    td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    tr:last-child td {
        border-bottom: none;
    }
    
    tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }
    
    .product-id {
        font-weight: 600;
        color: #6366f1;
        font-size: 0.875rem;
    }
    
    .product-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .product-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }
    
    .product-brand {
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .product-sku {
        font-family: 'Courier New', monospace;
        font-size: 0.8125rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }
    
    .price {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }
    
    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .pagination {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
        flex-wrap: wrap;
        padding: 1rem 0;
    }
    
    .pagination a,
    .pagination span {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        color: #64748b;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 44px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
    }
    
    .pagination a:hover {
        background: #f8fafc;
        border-color: #6366f1;
        color: #6366f1;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
    }
    
    .pagination .active {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border-color: #6366f1;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }
    
    .pagination .disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .pagination-info {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 1rem;
        font-size: 0.875rem;
        color: #64748b;
        text-align: center;
    }
    
    .pagination-nav {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .pagination-nav svg {
        width: 16px;
        height: 16px;
    }
    
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: #94a3b8;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state p {
        font-size: 1.125rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    
    .empty-state a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
    }
    
    .empty-state a:hover {
        text-decoration: underline;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Mobile Responsive */
    @media (max-width: 1024px) {
        .toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-form {
            max-width: none;
        }
    }
    
    @media (max-width: 768px) {
        .section-header {
            padding: 1rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .section-content {
            padding: 1rem;
        }
        
        .search-form {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .search-form input,
        .search-form select {
            width: 100%;
            min-width: auto;
        }
        
        .actions {
            flex-direction: column;
        }
        
        .pagination {
            gap: 0.25rem;
            padding: 0.75rem 0;
        }
        
        .pagination a,
        .pagination span {
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            min-width: 40px;
        }
        
        .pagination-info {
            font-size: 0.8125rem;
            margin-top: 0.75rem;
        }
        
        /* Hide table and show cards on mobile */
        .table-container {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .product-card-details {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .product-card-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }
        
        .product-card-actions {
            justify-content: center;
        }
    }
</style>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #6366f1;">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            Product Management
        </h2>
    </div>
    
    <div class="section-content">

        <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                <path d="M9 12l2 2 4-4"></path>
                <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"></path>
            </svg>
            <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

            <div class="toolbar">
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                    <select name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($filters['category'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="active" <?= ($filters['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        <option value="out_of_stock" <?= ($filters['status'] ?? '') == 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <a href="<?= View::url('/admin/products/create') ?>" class="btn btn-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Product
                </a>
            </div>

        <?php if (!empty($products)): ?>
        <!-- Desktop Table View -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <div class="product-id">#<?= $product['id'] ?></div>
                        </td>
                        <td>
                            <div class="product-info">
                                <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                                <div class="product-brand"><?= htmlspecialchars($product['brand_name'] ?? 'No Brand') ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="product-sku"><?= htmlspecialchars($product['sku']) ?></div>
                        </td>
                        <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                        <td>
                            <div class="price">SAR <?= number_format($product['price'], 2) ?></div>
                        </td>
                        <td>
                            <?php if ($product['quantity'] <= 10): ?>
                                <span class="badge badge-danger"><?= $product['quantity'] ?></span>
                            <?php else: ?>
                                <span class="badge badge-success"><?= $product['quantity'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $statusBadge = [
                                'active' => 'success',
                                'inactive' => 'warning',
                                'out_of_stock' => 'danger'
                            ];
                            ?>
                            <span class="badge badge-<?= $statusBadge[$product['status']] ?? 'warning' ?>">
                                <?= ucfirst(str_replace('_', ' ', $product['status'])) ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="<?= View::url('/admin/products/' . $product['id'] . '/edit') ?>" class="btn btn-primary btn-sm">Edit</a>
                            <button onclick="deleteProduct(<?= $product['id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Card View -->
        <div class="mobile-cards">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-card-header">
                    <div class="product-card-info">
                        <div class="product-card-id">#<?= $product['id'] ?></div>
                        <div class="product-card-name"><?= htmlspecialchars($product['name']) ?></div>
                        <div class="product-card-brand"><?= htmlspecialchars($product['brand_name'] ?? 'No Brand') ?></div>
                    </div>
                </div>
                
                <div class="product-card-details">
                    <div class="product-detail">
                        <div class="product-detail-label">SKU</div>
                        <div class="product-detail-value product-sku"><?= htmlspecialchars($product['sku']) ?></div>
                    </div>
                    <div class="product-detail">
                        <div class="product-detail-label">Category</div>
                        <div class="product-detail-value"><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></div>
                    </div>
                    <div class="product-detail">
                        <div class="product-detail-label">Price</div>
                        <div class="product-detail-value price">SAR <?= number_format($product['price'], 2) ?></div>
                    </div>
                    <div class="product-detail">
                        <div class="product-detail-label">Stock</div>
                        <div class="product-detail-value">
                            <?php if ($product['quantity'] <= 10): ?>
                                <span class="badge badge-danger"><?= $product['quantity'] ?></span>
                            <?php else: ?>
                                <span class="badge badge-success"><?= $product['quantity'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="product-card-footer">
                    <div class="product-card-status">
                        <?php
                        $statusBadge = [
                            'active' => 'success',
                            'inactive' => 'warning',
                            'out_of_stock' => 'danger'
                        ];
                        ?>
                        <span class="badge badge-<?= $statusBadge[$product['status']] ?? 'warning' ?>">
                            <?= ucfirst(str_replace('_', ' ', $product['status'])) ?>
                        </span>
                    </div>
                    <div class="product-card-actions">
                        <a href="<?= View::url('/admin/products/' . $product['id'] . '/edit') ?>" class="btn btn-primary btn-sm">Edit</a>
                        <button onclick="deleteProduct(<?= $product['id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

            <?php if ($pagination['total_pages'] > 1): ?>
            <div class="pagination">
                <!-- Previous Button -->
                <?php if ($pagination['current_page'] > 1): ?>
                    <a href="?page=<?= $pagination['current_page'] - 1 ?>&search=<?= urlencode($filters['search'] ?? '') ?>&category=<?= $filters['category'] ?? '' ?>&status=<?= $filters['status'] ?? '' ?>" class="pagination-nav" title="Previous Page">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span class="hidden sm:inline">Previous</span>
                    </a>
                <?php else: ?>
                    <span class="pagination-nav disabled" title="Previous Page">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span class="hidden sm:inline">Previous</span>
                    </span>
                <?php endif; ?>
                
                <!-- Page Numbers -->
                <?php
                $start = max(1, $pagination['current_page'] - 2);
                $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                
                // Show first page if not in range
                if ($start > 1): ?>
                    <a href="?page=1&search=<?= urlencode($filters['search'] ?? '') ?>&category=<?= $filters['category'] ?? '' ?>&status=<?= $filters['status'] ?? '' ?>">1</a>
                    <?php if ($start > 2): ?>
                        <span class="disabled">...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <!-- Current range -->
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <?php if ($i == $pagination['current_page']): ?>
                        <span class="active"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>&search=<?= urlencode($filters['search'] ?? '') ?>&category=<?= $filters['category'] ?? '' ?>&status=<?= $filters['status'] ?? '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <!-- Show last page if not in range -->
                <?php if ($end < $pagination['total_pages']): ?>
                    <?php if ($end < $pagination['total_pages'] - 1): ?>
                        <span class="disabled">...</span>
                    <?php endif; ?>
                    <a href="?page=<?= $pagination['total_pages'] ?>&search=<?= urlencode($filters['search'] ?? '') ?>&category=<?= $filters['category'] ?? '' ?>&status=<?= $filters['status'] ?? '' ?>"><?= $pagination['total_pages'] ?></a>
                <?php endif; ?>
                
                <!-- Next Button -->
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="?page=<?= $pagination['current_page'] + 1 ?>&search=<?= urlencode($filters['search'] ?? '') ?>&category=<?= $filters['category'] ?? '' ?>&status=<?= $filters['status'] ?? '' ?>" class="pagination-nav" title="Next Page">
                        <span class="hidden sm:inline">Next</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                <?php else: ?>
                    <span class="pagination-nav disabled" title="Next Page">
                        <span class="hidden sm:inline">Next</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </span>
                <?php endif; ?>
            </div>
            
            <!-- Pagination Info -->
            <div class="pagination-info">
                Showing <?= (($pagination['current_page'] - 1) * ($pagination['per_page'] ?? 10)) + 1 ?> to 
                <?= min($pagination['current_page'] * ($pagination['per_page'] ?? 10), $pagination['total_items'] ?? 0) ?> 
                of <?= $pagination['total_items'] ?? 0 ?> products
            </div>
            <?php endif; ?>

        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <p>No products found.</p>
            <a href="<?= View::url('/admin/products/create') ?>">Create your first product</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
        function deleteProduct(id) {
            if (!confirm('Are you sure you want to delete this product?')) {
                return;
            }

            fetch('<?= View::url('/admin/products/') ?>' + id + '/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'csrf_token=<?= $csrf_token ?? '' ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.error);
                }
            });
        }
</script>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

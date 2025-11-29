<?php
/**
 * Admin Categories Management - List View
 */
$pageTitle = 'Categories';
$breadcrumb = 'Home / Categories';
ob_start();
?>

<style>
    /* Add any custom styles here if needed */
</style>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-folder"></i>
            <?= htmlspecialchars($title) ?>
        </h2>
        <a href="<?= View::url('/admin/categories/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>

    <div class="section-content">
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Slug</th>
                        <th>Parent</th>
                        <th>Products</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="9" class="text-center">No categories found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><span class="product-id"><?= $category['id'] ?></span></td>
                                <td><?= htmlspecialchars($category['name']) ?></td>
                                <td>
                                    <span class="badge badge-<?= ($category['type'] ?? 'general') === 'general' ? 'primary' : 'success' ?>">
                                        <?= htmlspecialchars(ucfirst(str_replace('-', ' ', $category['type'] ?? 'general'))) ?>
                                    </span>
                                </td>
                                <td><span class="product-sku"><?= htmlspecialchars($category['slug']) ?></span></td>
                                <td><?= htmlspecialchars($category['parent_name'] ?? '-') ?></td>
                                <td><?= $category['product_count'] ?></td>
                                <td><?= $category['sort_order'] ?></td>
                                <td>
                                    <span class="badge badge-<?= $category['status'] === 'active' ? 'success' : 'warning' ?>">
                                        <?= htmlspecialchars(ucfirst($category['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="<?= View::url('/admin/categories/' . $category['id'] . '/edit') ?>" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= View::url('/admin/categories/' . $category['id'] . '/toggle-status') ?>" 
                                           class="btn btn-sm btn-success" title="Toggle Status"
                                           onclick="return confirm('Are you sure you want to toggle this category status?')">
                                            <i class="fas fa-power-off"></i>
                                        </a>
                                        <form action="<?= View::url('/admin/categories/' . $category['id'] . '/delete') ?>" 
                                              method="POST" style="display: inline;">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .badge-primary {
        background: #dbeafe;
        color: #1e40af;
    }
    
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
        margin: 0;
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
    }
    
    .product-id {
        font-weight: 600;
        color: #6366f1;
        font-size: 0.875rem;
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
    
    .actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .text-center {
        text-align: center;
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
</style>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

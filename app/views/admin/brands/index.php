<?php
$pageTitle = 'Brands Management';
ob_start();
?>

<style>
    /* Reuse the same styles from products page */
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .page-title { font-size: 24px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 12px; }
    .page-title svg { width: 28px; height: 28px; color: #6366f1; }
    .btn { padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-success { background: #10b981; color: white; }
    .btn-success:hover { background: #059669; }
    .brands-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
    .brand-card { background: white; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; transition: all 0.3s; }
    .brand-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .brand-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; }
    .brand-logo { width: 80px; height: 80px; border-radius: 8px; object-fit: contain; background: #f8fafc; padding: 10px; }
    .brand-name { font-size: 18px; font-weight: 600; color: #1e293b; margin: 0 0 8px 0; }
    .brand-description { font-size: 14px; color: #64748b; margin-bottom: 12px; }
    .brand-meta { display: flex; gap: 15px; font-size: 13px; color: #94a3b8; margin-bottom: 15px; }
    .brand-status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .brand-status.active { background: #d1fae5; color: #065f46; }
    .brand-status.inactive { background: #fee2e2; color: #991b1b; }
    .brand-actions { display: flex; gap: 8px; margin-top: 15px; }
    .btn-sm { padding: 6px 12px; font-size: 13px; }
    .btn-primary { background: #6366f1; color: white; }
    .btn-primary:hover { background: #4f46e5; }
    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; }
    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
</style>

<div class="page-header">
    <h1 class="page-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        Brands Management
    </h1>
    <a href="<?= View::url('/admin/brands/create') ?>" class="btn btn-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Add Brand
    </a>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<?php if (!empty($brands)): ?>
<div class="brands-grid">
    <?php foreach ($brands as $brand): ?>
    <div class="brand-card">
        <div class="brand-header">
            <?php if (!empty($brand['logo'])): ?>
                <img src="<?= BASE_URL . '/' . $brand['logo'] ?>" alt="<?= htmlspecialchars($brand['name']) ?>" class="brand-logo">
            <?php else: ?>
                <div class="brand-logo" style="display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700; color: #94a3b8;">
                    <?= strtoupper(substr($brand['name'], 0, 2)) ?>
                </div>
            <?php endif; ?>
            <span class="brand-status <?= $brand['status'] ?>">
                <?= ucfirst($brand['status']) ?>
            </span>
        </div>
        
        <h3 class="brand-name"><?= htmlspecialchars($brand['name']) ?></h3>
        
        <?php if (!empty($brand['description'])): ?>
            <p class="brand-description"><?= htmlspecialchars(substr($brand['description'], 0, 80)) ?>...</p>
        <?php endif; ?>
        
        <div class="brand-meta">
            <span>📦 <?= $brand['product_count'] ?> Products</span>
            <?php if (!empty($brand['country'])): ?>
                <span>🌍 <?= htmlspecialchars($brand['country']) ?></span>
            <?php endif; ?>
        </div>
        
        <div class="brand-actions">
            <a href="<?= View::url('/admin/brands/' . $brand['id'] . '/edit') ?>" class="btn btn-primary btn-sm">
                Edit
            </a>
            <?php if ($brand['product_count'] == 0): ?>
                <button onclick="deleteBrand(<?= $brand['id'] ?>)" class="btn btn-danger btn-sm">
                    Delete
                </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="empty-state">
    <div style="font-size: 48px; margin-bottom: 20px;">🏢</div>
    <p style="font-size: 16px; margin-bottom: 20px;">No brands found.</p>
    <a href="<?= View::url('/admin/brands/create') ?>" class="btn btn-success">Add Your First Brand</a>
</div>
<?php endif; ?>

<script>
    function deleteBrand(id) {
        if (!confirm('Are you sure you want to delete this brand?')) {
            return;
        }
        
        fetch('<?= View::url('/admin/brands/') ?>' + id + '/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(() => location.reload())
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the brand');
        });
    }
</script>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

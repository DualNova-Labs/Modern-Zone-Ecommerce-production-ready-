<?php
ob_start();
?>

<div class="admin-header">
    <div class="admin-header-content">
        <h1 class="admin-title">
            <svg class="admin-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
            Manage Banners
        </h1>
        <a href="<?= View::url('admin/banners/create') ?>" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add New Banner
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success'] ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?= $_SESSION['error'] ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">All Banners (<?= $totalBanners ?>)</h2>
        <p class="admin-card-subtitle">Manage hero slider banners displayed on the homepage</p>
    </div>

    <?php if (empty($banners)): ?>
        <div class="empty-state">
            <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
            <h3 class="empty-state-title">No Banners Yet</h3>
            <p class="empty-state-text">Get started by creating your first banner for the hero slider</p>
            <a href="<?= View::url('admin/banners/create') ?>" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Create First Banner
            </a>
        </div>
    <?php else: ?>
        <!-- Desktop Table View -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Title</th>
                        <th>Badge</th>
                        <th style="width: 120px;">Sort Order</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($banners as $banner): ?>
                    <tr>
                        <td>
                            <div class="product-image-cell">
                                <img src="<?= BASE_URL . '/' . htmlspecialchars($banner['image']) ?>" 
                                     alt="<?= htmlspecialchars($banner['title']) ?>"
                                     onerror="this.src='<?= View::asset('images/placeholder.svg') ?>'">
                            </div>
                        </td>
                        <td>
                            <div class="product-name-cell">
                                <strong><?= htmlspecialchars($banner['title']) ?></strong>
                                <?php if (!empty($banner['subtitle'])): ?>
                                    <small><?= htmlspecialchars(substr($banner['subtitle'], 0, 60)) ?><?= strlen($banner['subtitle']) > 60 ? '...' : '' ?></small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($banner['badge'])): ?>
                                <span class="badge badge-info"><?= htmlspecialchars($banner['badge']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-secondary"><?= $banner['sort_order'] ?></span>
                        </td>
                        <td>
                            <button class="status-badge status-<?= $banner['status'] ?>" 
                                    onclick="toggleStatus(<?= $banner['id'] ?>, this)"
                                    title="Click to toggle">
                                <?= ucfirst($banner['status']) ?>
                            </button>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?= View::url('admin/banners/edit/' . $banner['id']) ?>" 
                                   class="btn-action btn-action-edit" 
                                   title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <button onclick="deleteBanner(<?= $banner['id'] ?>, '<?= htmlspecialchars($banner['title']) ?>')" 
                                        class="btn-action btn-action-delete" 
                                        title="Delete">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-cards">
            <?php foreach ($banners as $banner): ?>
            <div class="product-card">
                <div class="product-card-image">
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($banner['image']) ?>" 
                         alt="<?= htmlspecialchars($banner['title']) ?>"
                         onerror="this.src='<?= View::asset('images/placeholder.svg') ?>'">
                </div>
                <div class="product-card-content">
                    <div class="product-card-header">
                        <h3 class="product-card-title"><?= htmlspecialchars($banner['title']) ?></h3>
                        <button class="status-badge status-<?= $banner['status'] ?>" 
                                onclick="toggleStatus(<?= $banner['id'] ?>, this)">
                            <?= ucfirst($banner['status']) ?>
                        </button>
                    </div>
                    
                    <?php if (!empty($banner['subtitle'])): ?>
                    <p class="product-card-description"><?= htmlspecialchars(substr($banner['subtitle'], 0, 80)) ?><?= strlen($banner['subtitle']) > 80 ? '...' : '' ?></p>
                    <?php endif; ?>
                    
                    <div class="product-card-meta">
                        <?php if (!empty($banner['badge'])): ?>
                        <span class="meta-item">
                            <span class="meta-label">Badge:</span>
                            <span class="badge badge-info"><?= htmlspecialchars($banner['badge']) ?></span>
                        </span>
                        <?php endif; ?>
                        <span class="meta-item">
                            <span class="meta-label">Sort Order:</span>
                            <span class="badge badge-secondary"><?= $banner['sort_order'] ?></span>
                        </span>
                    </div>
                    
                    <div class="product-card-actions">
                        <a href="<?= View::url('admin/banners/edit/' . $banner['id']) ?>" class="btn btn-secondary btn-sm">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Edit
                        </a>
                        <button onclick="deleteBanner(<?= $banner['id'] ?>, '<?= htmlspecialchars($banner['title']) ?>')" 
                                class="btn btn-danger btn-sm">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination-container">
            <div class="pagination-info">
                Showing page <?= $currentPage ?> of <?= $totalPages ?>
            </div>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?= $currentPage - 1 ?>" class="pagination-btn pagination-prev">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Previous
                    </a>
                <?php endif; ?>

                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <a href="?page=<?= $i ?>" class="pagination-btn <?= $i === $currentPage ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?= $currentPage + 1 ?>" class="pagination-btn pagination-next">
                        Next
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
function toggleStatus(bannerId, button) {
    if (!confirm('Are you sure you want to change the banner status?')) {
        return;
    }
    
    fetch('<?= View::url('admin/banners/toggle-status/') ?>' + bannerId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.className = 'status-badge status-' + data.status;
            button.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
        } else {
            alert('Failed to update status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status');
    });
}

function deleteBanner(bannerId, bannerTitle) {
    if (!confirm(`Are you sure you want to delete "${bannerTitle}"? This action cannot be undone.`)) {
        return;
    }
    
    window.location.href = '<?= View::url('admin/banners/delete/') ?>' + bannerId;
}
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/admin/layouts/main.php';
?>

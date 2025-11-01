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
        <button onclick="openCreateModal()" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add New Banner
        </button>
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
            <button onclick="openCreateModal()" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Create First Banner
            </button>
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
                                <button onclick="openEditModal(<?= $banner['id'] ?>)" 
                                        class="btn-action btn-action-edit" 
                                        title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
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

// Modal Functions
function openCreateModal() {
    document.getElementById('createBannerModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createBannerModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('createBannerForm').reset();
    // Clear any error messages
    const errorElements = document.querySelectorAll('.form-error');
    errorElements.forEach(el => el.remove());
    // Reset image upload area
    clearImageUpload();
}

// Edit Modal Functions
function openEditModal(bannerId) {
    // Fetch banner data
    fetch(`<?= View::url('admin/banners/edit/') ?>${bannerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const banner = data.banner;
                
                // Populate form fields
                document.getElementById('edit_title').value = banner.title || '';
                document.getElementById('edit_subtitle').value = banner.subtitle || '';
                document.getElementById('edit_badge').value = banner.badge || '';
                document.getElementById('edit_sort_order').value = banner.sort_order || 0;
                document.getElementById('edit_link_url').value = banner.link_url || '';
                document.getElementById('edit_link_text').value = banner.link_text || '';
                document.getElementById('edit_status').value = banner.status || 'active';
                document.getElementById('edit_banner_id').value = banner.id;
                
                // Show current image
                const currentImageDiv = document.getElementById('currentImage');
                if (banner.image) {
                    currentImageDiv.innerHTML = `
                        <div style="margin-bottom: 1rem;">
                            <label class="form-label">Current Image:</label>
                            <div style="border: 1px solid var(--gray-300); border-radius: 8px; padding: 1rem; background: var(--gray-50);">
                                <img src="<?= BASE_URL ?>/${banner.image}" alt="Current banner" style="max-width: 200px; max-height: 150px; border-radius: 8px; object-fit: contain;">
                            </div>
                        </div>
                    `;
                } else {
                    currentImageDiv.innerHTML = '';
                }
                
                // Reset edit image upload area
                clearEditImageUpload();
                
                // Open modal
                document.getElementById('editBannerModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            } else {
                alert('Error loading banner data: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading banner data. Please try again.');
        });
}

function closeEditModal() {
    document.getElementById('editBannerModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('editBannerForm').reset();
    // Clear any error messages
    const errorElements = document.querySelectorAll('.form-error');
    errorElements.forEach(el => el.remove());
    // Reset image upload area
    clearEditImageUpload();
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        if (e.target.id === 'createBannerModal') {
            closeCreateModal();
        } else if (e.target.id === 'editBannerModal') {
            closeEditModal();
        }
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const createModal = document.getElementById('createBannerModal');
        const editModal = document.getElementById('editBannerModal');
        
        if (createModal && createModal.classList.contains('active')) {
            closeCreateModal();
        } else if (editModal && editModal.classList.contains('active')) {
            closeEditModal();
        }
    }
});

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission
    const createBannerForm = document.getElementById('createBannerForm');
    if (createBannerForm) {
        createBannerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<svg class="animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg> Creating...';
            submitBtn.disabled = true;
            
            fetch('<?= View::url('admin/banners/store') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // If successful, reload the page
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Handle edit form submission
    const editBannerForm = document.getElementById('editBannerForm');
    if (editBannerForm) {
        editBannerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('editSubmitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<svg class="animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg> Updating...';
            submitBtn.disabled = true;
            
            const bannerId = document.getElementById('edit_banner_id').value;
            
            fetch(`<?= View::url('admin/banners/update/') ?>${bannerId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // If successful, reload the page
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // File upload preview for create modal
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const uploadLabel = document.querySelector('.file-upload-label');
            
            if (file && uploadLabel) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Replace the upload area with the image preview
                    uploadLabel.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: contain;">
                        <div style="margin-top: 1rem; font-size: 0.875rem; color: var(--gray-600);">
                            <strong>${file.name}</strong><br>
                            <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small><br>
                            <button type="button" onclick="clearImageUpload()" style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: var(--danger-color); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">
                                Remove Image
                            </button>
                        </div>
                    `;
                    uploadLabel.style.border = '2px solid var(--success-color)';
                    uploadLabel.style.background = 'rgba(16, 185, 129, 0.05)';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // File upload preview for edit modal
    const editImageInput = document.getElementById('edit_image');
    if (editImageInput) {
        editImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const uploadLabel = document.querySelector('#editBannerModal .file-upload-label');
            
            if (file && uploadLabel) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Replace the upload area with the image preview
                    uploadLabel.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: contain;">
                        <div style="margin-top: 1rem; font-size: 0.875rem; color: var(--gray-600);">
                            <strong>${file.name}</strong><br>
                            <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small><br>
                            <button type="button" onclick="clearEditImageUpload()" style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: var(--danger-color); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">
                                Remove Image
                            </button>
                        </div>
                    `;
                    uploadLabel.style.border = '2px solid var(--success-color)';
                    uploadLabel.style.background = 'rgba(16, 185, 129, 0.05)';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

// Clear image upload
function clearImageUpload() {
    const fileInput = document.getElementById('image');
    const uploadLabel = document.querySelector('.file-upload-label');
    
    if (fileInput) {
        fileInput.value = '';
    }
    
    if (uploadLabel) {
        // Restore original upload area
        uploadLabel.innerHTML = `
            <svg class="file-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21,15 16,10 5,21"></polyline>
            </svg>
            <div class="file-upload-text">
                <strong>Click to upload</strong> or drag and drop<br>
                <small>PNG, JPG, GIF up to 10MB</small>
            </div>
        `;
        uploadLabel.style.border = '2px dashed var(--gray-300)';
        uploadLabel.style.background = 'var(--gray-50)';
    }
}

// Clear edit image upload
function clearEditImageUpload() {
    const fileInput = document.getElementById('edit_image');
    const uploadLabel = document.querySelector('#editBannerModal .file-upload-label');
    
    if (fileInput) {
        fileInput.value = '';
    }
    
    if (uploadLabel) {
        // Restore original upload area
        uploadLabel.innerHTML = `
            <svg class="file-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21,15 16,10 5,21"></polyline>
            </svg>
            <div class="file-upload-text">
                <strong>Click to upload</strong> or drag and drop<br>
                <small>PNG, JPG, GIF up to 10MB<br>Images will be automatically resized to 1920x600px<br>(Optional - leave empty to keep current image)</small>
            </div>
        `;
        uploadLabel.style.border = '2px dashed var(--gray-300)';
        uploadLabel.style.background = 'var(--gray-50)';
    }
}
</script>

<!-- Create Banner Modal -->
<div id="createBannerModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h2 class="modal-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                Create New Banner
            </h2>
            <button type="button" class="modal-close" onclick="closeCreateModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <form id="createBannerForm" enctype="multipart/form-data">
            <?= View::csrfField() ?>
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="title" class="form-label required">Banner Title</label>
                    <input type="text" id="title" name="title" class="form-input" required 
                           placeholder="Enter banner title">
                    <div class="form-help">This will be the main heading displayed on the banner</div>
                </div>
                
                <div class="form-group">
                    <label for="subtitle" class="form-label">Subtitle</label>
                    <textarea id="subtitle" name="subtitle" class="form-textarea" 
                              placeholder="Enter banner subtitle or description"></textarea>
                    <div class="form-help">Optional subtitle or description text</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="badge" class="form-label">Badge Text</label>
                        <input type="text" id="badge" name="badge" class="form-input" 
                               placeholder="e.g., NEW, SALE, LIMITED">
                        <div class="form-help">Optional badge text</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-input" 
                               value="0" min="0">
                        <div class="form-help">Lower numbers appear first</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="link_url" class="form-label">Link URL</label>
                    <input type="url" id="link_url" name="link_url" class="form-input" 
                           placeholder="https://example.com">
                    <div class="form-help">Optional URL to link when banner is clicked</div>
                </div>
                
                <div class="form-group">
                    <label for="link_text" class="form-label">Link Button Text</label>
                    <input type="text" id="link_text" name="link_text" class="form-input" 
                           placeholder="e.g., Shop Now, Learn More">
                    <div class="form-help">Text for the call-to-action button</div>
                </div>
                
                <div class="form-group">
                    <label for="image" class="form-label required">Banner Image</label>
                    <div class="file-upload">
                        <input type="file" id="image" name="image" class="file-upload-input" 
                               accept="image/*" required>
                        <label for="image" class="file-upload-label">
                            <svg class="file-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21,15 16,10 5,21"></polyline>
                            </svg>
                            <div class="file-upload-text">
                                <strong>Click to upload</strong> or drag and drop<br>
                                <small>PNG, JPG, GIF up to 10MB<br>Images will be automatically resized to 1920x600px</small>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div class="form-help">Only active banners will be displayed on the website</div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCreateModal()">
                    Cancel
                </button>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Create Banner
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Banner Modal -->
<div id="editBannerModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h2 class="modal-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit Banner
            </h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <form id="editBannerForm" enctype="multipart/form-data">
            <?= View::csrfField() ?>
            <input type="hidden" id="edit_banner_id" name="id">
            
            <div class="modal-body">
                <div id="currentImage"></div>
                
                <div class="form-group">
                    <label for="edit_title" class="form-label required">Banner Title</label>
                    <input type="text" id="edit_title" name="title" class="form-input" required 
                           placeholder="Enter banner title">
                    <div class="form-help">This will be the main heading displayed on the banner</div>
                </div>
                
                <div class="form-group">
                    <label for="edit_subtitle" class="form-label">Subtitle</label>
                    <textarea id="edit_subtitle" name="subtitle" class="form-textarea" 
                              placeholder="Enter banner subtitle or description"></textarea>
                    <div class="form-help">Optional subtitle or description text</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_badge" class="form-label">Badge Text</label>
                        <input type="text" id="edit_badge" name="badge" class="form-input" 
                               placeholder="e.g., NEW, SALE, LIMITED">
                        <div class="form-help">Optional badge text</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="edit_sort_order" name="sort_order" class="form-input" 
                               value="0" min="0">
                        <div class="form-help">Lower numbers appear first</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_link_url" class="form-label">Link URL</label>
                    <input type="url" id="edit_link_url" name="link_url" class="form-input" 
                           placeholder="https://example.com">
                    <div class="form-help">Optional URL to link when banner is clicked</div>
                </div>
                
                <div class="form-group">
                    <label for="edit_link_text" class="form-label">Link Button Text</label>
                    <input type="text" id="edit_link_text" name="link_text" class="form-input" 
                           placeholder="e.g., Shop Now, Learn More">
                    <div class="form-help">Text for the call-to-action button</div>
                </div>
                
                <div class="form-group">
                    <label for="edit_image" class="form-label">New Banner Image</label>
                    <div class="file-upload">
                        <input type="file" id="edit_image" name="image" class="file-upload-input" 
                               accept="image/*">
                        <label for="edit_image" class="file-upload-label">
                            <svg class="file-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21,15 16,10 5,21"></polyline>
                            </svg>
                            <div class="file-upload-text">
                                <strong>Click to upload</strong> or drag and drop<br>
                                <small>PNG, JPG, GIF up to 10MB<br>Images will be automatically resized to 1920x600px<br>(Optional - leave empty to keep current image)</small>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_status" class="form-label">Status</label>
                    <select id="edit_status" name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div class="form-help">Only active banners will be displayed on the website</div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                    Cancel
                </button>
                <button type="submit" id="editSubmitBtn" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Update Banner
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/admin/layouts/main.php';
?>

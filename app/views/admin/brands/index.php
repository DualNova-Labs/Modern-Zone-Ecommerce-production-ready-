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
    
    /* Modal Styles */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; backdrop-filter: blur(4px); animation: fadeIn 0.2s ease; }
    .modal-overlay.active { display: flex; align-items: center; justify-content: center; }
    .modal-container { background: white; border-radius: 16px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease; }
    .modal-header { padding: 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
    .modal-title { font-size: 20px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px; }
    .modal-close { background: none; border: none; font-size: 24px; color: #94a3b8; cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px; transition: all 0.2s; }
    .modal-close:hover { background: #f1f5f9; color: #1e293b; }
    .modal-body { padding: 24px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 14px; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #6366f1; }
    .form-group textarea { min-height: 100px; resize: vertical; font-family: inherit; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group small { color: #64748b; font-size: 12px; }
    .form-actions { display: flex; gap: 10px; padding: 20px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc; border-radius: 0 0 16px 16px; }
    .btn-modal-primary { background: #6366f1; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .btn-modal-primary:hover { background: #4f46e5; }
    .btn-modal-secondary { background: #e2e8f0; color: #475569; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .btn-modal-secondary:hover { background: #cbd5e1; }
    .current-logo-preview { max-width: 120px; margin: 10px 0; border: 2px solid #e2e8f0; border-radius: 8px; padding: 8px; background: #f8fafc; }
    
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="page-header">
    <h1 class="page-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        Brands Management
    </h1>
    <button onclick="openCreateModal()" class="btn btn-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Add Brand
    </button>
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
            <button onclick="openEditModal(<?= $brand['id'] ?>)" class="btn btn-primary btn-sm">
                Edit
            </button>
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
    <button onclick="openCreateModal()" class="btn btn-success">Add Your First Brand</button>
</div>
<?php endif; ?>

<!-- Create Brand Modal -->
<div id="createModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add New Brand
            </h3>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        
        <form id="createBrandForm" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="create_name">Brand Name *</label>
                        <input type="text" id="create_name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="create_slug">Slug</label>
                        <input type="text" id="create_slug" name="slug">
                        <small>Leave empty to auto-generate</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="create_description">Description</label>
                    <input type="text" id="create_description" name="description">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="create_website">Website</label>
                        <input type="url" id="create_website" name="website" placeholder="https://brand.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="create_country">Country</label>
                        <input type="text" id="create_country" name="country">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="create_founded_year">Founded Year</label>
                        <input type="number" id="create_founded_year" name="founded_year" min="1800" max="<?= date('Y') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="create_sort_order">Sort Order</label>
                        <input type="number" id="create_sort_order" name="sort_order" min="0" value="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="create_logo">Brand Logo</label>
                    <input type="file" id="create_logo" name="logo" accept="image/*">
                    <small>PNG, JPG, SVG recommended (max 2MB)</small>
                </div>
                
                <div class="form-group">
                    <label for="create_about">About Brand</label>
                    <textarea id="create_about" name="about"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="create_specialties">Specialties</label>
                    <textarea id="create_specialties" name="specialties"></textarea>
                    <small>e.g., Cutting Tools, Measuring Instruments, etc.</small>
                </div>
                
                <div class="form-group">
                    <label for="create_status">Status</label>
                    <select id="create_status" name="status">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-modal-primary">Create Brand</button>
                <button type="button" class="btn-modal-secondary" onclick="closeCreateModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Brand Modal -->
<div id="editModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit Brand
            </h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        
        <form id="editBrandForm" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" id="edit_brand_id" name="brand_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_name">Brand Name *</label>
                        <input type="text" id="edit_name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_slug">Slug</label>
                        <input type="text" id="edit_slug" name="slug">
                        <small>Leave empty to auto-generate</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_description">Description</label>
                    <input type="text" id="edit_description" name="description">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_website">Website</label>
                        <input type="url" id="edit_website" name="website" placeholder="https://brand.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_country">Country</label>
                        <input type="text" id="edit_country" name="country">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_founded_year">Founded Year</label>
                        <input type="number" id="edit_founded_year" name="founded_year" min="1800" max="<?= date('Y') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_sort_order">Sort Order</label>
                        <input type="number" id="edit_sort_order" name="sort_order" min="0" value="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_logo">Brand Logo</label>
                    <div id="current_logo_container" style="display: none;">
                        <small style="display: block; margin-bottom: 5px; color: #64748b;">Current Logo:</small>
                        <img id="current_logo" src="" alt="Current Logo" class="current-logo-preview">
                    </div>
                    <input type="file" id="edit_logo" name="logo" accept="image/*">
                    <small>PNG, JPG, SVG recommended (max 2MB) - Leave empty to keep current logo</small>
                </div>
                
                <div class="form-group">
                    <label for="edit_about">About Brand</label>
                    <textarea id="edit_about" name="about"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="edit_specialties">Specialties</label>
                    <textarea id="edit_specialties" name="specialties"></textarea>
                    <small>e.g., Cutting Tools, Measuring Instruments, etc.</small>
                </div>
                
                <div class="form-group">
                    <label for="edit_status">Status</label>
                    <select id="edit_status" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-modal-primary">Update Brand</button>
                <button type="button" class="btn-modal-secondary" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Store all brands data for easy lookup
    const brandsData = <?= json_encode($brands ?? []) ?>;
    
    // Create Brand Modal Functions
    function openCreateModal() {
        document.getElementById('createModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeCreateModal() {
        document.getElementById('createModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('createBrandForm').reset();
    }
    
    // Close create modal when clicking outside
    document.getElementById('createModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });
    
    // Handle create form submission
    document.getElementById('createBrandForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show loading state
        const submitBtn = this.querySelector('.btn-modal-primary');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Creating...';
        submitBtn.disabled = true;
        
        fetch('<?= View::url('/admin/brands') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                // Success - reload page to show new brand
                window.location.reload();
            } else {
                throw new Error('Creation failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the brand');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // Edit Brand Modal Functions
    function openEditModal(brandId) {
        // Find the brand data
        const brand = brandsData.find(b => b.id == brandId);
        if (!brand) {
            alert('Brand not found');
            return;
        }
        
        // Populate form fields
        document.getElementById('edit_brand_id').value = brand.id;
        document.getElementById('edit_name').value = brand.name || '';
        document.getElementById('edit_slug').value = brand.slug || '';
        document.getElementById('edit_description').value = brand.description || '';
        document.getElementById('edit_website').value = brand.website || '';
        document.getElementById('edit_country').value = brand.country || '';
        document.getElementById('edit_founded_year').value = brand.founded_year || '';
        document.getElementById('edit_sort_order').value = brand.sort_order || '0';
        document.getElementById('edit_about').value = brand.about || '';
        document.getElementById('edit_specialties').value = brand.specialties || '';
        document.getElementById('edit_status').value = brand.status || 'active';
        
        // Show current logo if exists
        const logoContainer = document.getElementById('current_logo_container');
        const logoImg = document.getElementById('current_logo');
        if (brand.logo) {
            logoImg.src = '<?= BASE_URL ?>/' + brand.logo;
            logoContainer.style.display = 'block';
        } else {
            logoContainer.style.display = 'none';
        }
        
        // Set form action
        document.getElementById('editBrandForm').action = '<?= View::url('/admin/brands/') ?>' + brandId;
        
        // Show modal
        document.getElementById('editModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('editBrandForm').reset();
    }
    
    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });
    
    // Handle form submission
    document.getElementById('editBrandForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const brandId = document.getElementById('edit_brand_id').value;
        
        // Show loading state
        const submitBtn = this.querySelector('.btn-modal-primary');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Updating...';
        submitBtn.disabled = true;
        
        fetch('<?= View::url('/admin/brands/') ?>' + brandId, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                // Success - reload page to show updated data
                window.location.reload();
            } else {
                throw new Error('Update failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the brand');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
    
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

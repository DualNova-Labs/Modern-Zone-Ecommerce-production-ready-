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
    
    /* Modal Styles - Matching Product Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        animation: fadeIn 0.3s ease-out;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 800px;
        width: 100%;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        animation: slideUp 0.3s ease-out;
    }
    
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px 16px 0 0;
    }
    
    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }
    
    .modal-title svg {
        width: 24px;
        height: 24px;
        color: #6366f1;
    }
    
    .modal-close {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #64748b;
        width: 32px;
        height: 32px;
    }
    
    .modal-close:hover {
        background: #f1f5f9;
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 2rem;
        overflow-y: auto;
        flex: 1;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-group:not(.form-row .form-group) {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    .form-label.required::after {
        content: ' *';
        color: #ef4444;
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: inherit;
        width: 100%;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .form-help {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: -0.25rem;
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1.5rem 2rem;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 0 0 16px 16px;
    }
    
    .btn-modal-primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.875rem;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }
    
    .btn-modal-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .btn-modal-secondary {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.875rem;
    }
    
    .btn-modal-secondary:hover {
        background: #e2e8f0;
        color: #475569;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .current-logo-preview {
        max-width: 150px;
        margin: 0.5rem 0;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem;
        background: #f8fafc;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 768px) {
        .modal-container {
            max-width: 95%;
            max-height: 95vh;
        }
        
        .modal-header,
        .modal-body,
        .form-actions {
            padding: 1rem;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .modal-title {
            font-size: 1.25rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-modal-primary,
        .btn-modal-secondary {
            width: 100%;
        }
    }
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
                        <label for="create_name" class="form-label required">Brand Name</label>
                        <input type="text" id="create_name" name="name" class="form-input" required placeholder="Enter brand name">
                    </div>
                    
                    <div class="form-group">
                        <label for="create_slug" class="form-label">Slug</label>
                        <input type="text" id="create_slug" name="slug" class="form-input" placeholder="auto-generated">
                        <small class="form-help">Leave empty to auto-generate</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="create_description" class="form-label">Description</label>
                    <input type="text" id="create_description" name="description" class="form-input" placeholder="Brief description of the brand">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="create_website" class="form-label">Website</label>
                        <input type="url" id="create_website" name="website" class="form-input" placeholder="https://brand.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="create_country" class="form-label">Country</label>
                        <input type="text" id="create_country" name="country" class="form-input" placeholder="e.g., United States">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="create_founded_year" class="form-label">Founded Year</label>
                        <input type="number" id="create_founded_year" name="founded_year" class="form-input" min="1800" max="<?= date('Y') ?>" placeholder="e.g., 2004">
                    </div>
                    
                    <div class="form-group">
                        <label for="create_sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="create_sort_order" name="sort_order" class="form-input" min="0" value="0" placeholder="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="create_logo" class="form-label">Brand Logo</label>
                    <input type="file" id="create_logo" name="logo" class="form-input" accept="image/*">
                    <small class="form-help">PNG, JPG, SVG recommended (max 2MB)</small>
                </div>
                
                <div class="form-group">
                    <label for="create_about" class="form-label">About Brand</label>
                    <textarea id="create_about" name="about" class="form-textarea" placeholder="Detailed information about the brand"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="create_specialties" class="form-label">Specialties</label>
                    <textarea id="create_specialties" name="specialties" class="form-textarea" placeholder="e.g., Cutting Tools, Measuring Instruments, etc."></textarea>
                    <small class="form-help">e.g., Cutting Tools, Measuring Instruments, etc.</small>
                </div>
                
                <div class="form-group">
                    <label for="create_status" class="form-label">Status</label>
                    <select id="create_status" name="status" class="form-select">
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
                        <label for="edit_name" class="form-label required">Brand Name</label>
                        <input type="text" id="edit_name" name="name" class="form-input" required placeholder="Enter brand name">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_slug" class="form-label">Slug</label>
                        <input type="text" id="edit_slug" name="slug" class="form-input" placeholder="auto-generated">
                        <small class="form-help">Leave empty to auto-generate</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_description" class="form-label">Description</label>
                    <input type="text" id="edit_description" name="description" class="form-input" placeholder="Brief description of the brand">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_website" class="form-label">Website</label>
                        <input type="url" id="edit_website" name="website" class="form-input" placeholder="https://brand.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_country" class="form-label">Country</label>
                        <input type="text" id="edit_country" name="country" class="form-input" placeholder="e.g., United States">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_founded_year" class="form-label">Founded Year</label>
                        <input type="number" id="edit_founded_year" name="founded_year" class="form-input" min="1800" max="<?= date('Y') ?>" placeholder="e.g., 2004">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="edit_sort_order" name="sort_order" class="form-input" min="0" value="0" placeholder="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_logo" class="form-label">Brand Logo</label>
                    <div id="current_logo_container" style="display: none;">
                        <small style="display: block; margin-bottom: 5px; color: #64748b;">Current Logo:</small>
                        <img id="current_logo" src="" alt="Current Logo" class="current-logo-preview">
                    </div>
                    <input type="file" id="edit_logo" name="logo" class="form-input" accept="image/*">
                    <small class="form-help">PNG, JPG, SVG recommended (max 2MB) - Leave empty to keep current logo</small>
                </div>
                
                <div class="form-group">
                    <label for="edit_about" class="form-label">About Brand</label>
                    <textarea id="edit_about" name="about" class="form-textarea" placeholder="Detailed information about the brand"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="edit_specialties" class="form-label">Specialties</label>
                    <textarea id="edit_specialties" name="specialties" class="form-textarea" placeholder="e.g., Cutting Tools, Measuring Instruments, etc."></textarea>
                    <small class="form-help">e.g., Cutting Tools, Measuring Instruments, etc.</small>
                </div>
                
                <div class="form-group">
                    <label for="edit_subcategories" class="form-label">Linked Subcategories</label>
                    <select id="edit_subcategories" name="subcategory_ids[]" multiple class="form-select" size="8" style="height: auto;">
                        <?php
                        require_once APP_PATH . '/models/Category.php';
                        $db = Database::getInstance();
                        // Get all parent categories
                        $parentCategories = $db->select("SELECT * FROM categories WHERE parent_id IS NULL AND status = 'active' ORDER BY name");
                        foreach ($parentCategories as $parent):
                            // Get subcategories for this parent
                            $subcategories = $db->select(
                                "SELECT * FROM categories WHERE parent_id = :parent_id AND status = 'active' ORDER BY name",
                                ['parent_id' => $parent['id']]
                            );
                            if (!empty($subcategories)):
                        ?>
                            <optgroup label="<?= htmlspecialchars($parent['name']) ?>">
                                <?php foreach ($subcategories as $subcat): ?>
                                    <option value="<?= $subcat['id'] ?>"><?= htmlspecialchars($subcat['name']) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </select>
                    <small class="form-help">Hold Ctrl/Cmd to select multiple subcategories</small>
                </div>
                
                <div class="form-group">
                    <label for="edit_status" class="form-label">Status</label>
                    <select id="edit_status" name="status" class="form-select">
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
        
        // Pre-select subcategories
        const subcategorySelect = document.getElementById('edit_subcategories');
        if (subcategorySelect && brand.subcategory_ids) {
            // Clear all selections first
            Array.from(subcategorySelect.options).forEach(option => {
                option.selected = false;
            });
            
            // Select the brand's subcategories
            Array.from(subcategorySelect.options).forEach(option => {
                if (brand.subcategory_ids.includes(parseInt(option.value))) {
                    option.selected = true;
                }
            });
        }
        
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

<?php
/**
 * Admin Categories Management - List View with Modal
 */
$pageTitle = 'Categories';
$breadcrumb = 'Home / Categories';
ob_start();
?>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-folder"></i>
            <?= htmlspecialchars($title) ?>
        </h2>
        <button onclick="openCreateModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Category
        </button>
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
                                        <button onclick="openEditModal(<?= $category['id'] ?>)" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="<?= View::url('/admin/categories/' . $category['id'] . '/toggle-status') ?>" 
                                           class="btn btn-sm btn-success" title="Toggle Status"
                                           onclick="return confirm('Are you sure you want to toggle this category status?')">
                                            <i class="fas fa-power-off"></i> Toggle
                                        </a>
                                        <button onclick="deleteCategory(<?= $category['id'] ?>)" 
                                                class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
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

<!-- Create Category Modal -->
<div id="createModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-plus-circle"></i>
                Add New Category
            </h3>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        
        <form id="createCategoryForm" method="POST" action="<?= View::url('/admin/categories') ?>" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="create_name" class="form-label required">Category Name</label>
                        <input type="text" id="create_name" name="name" class="form-input" required placeholder="Enter category name">
                    </div>
                    
                    <div class="form-group">
                        <label for="create_slug" class="form-label">Slug</label>
                        <input type="text" id="create_slug" name="slug" class="form-input" placeholder="auto-generated">
                        <small class="form-help">Leave empty to auto-generate from name</small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="create_type" class="form-label required">Category Type</label>
                        <select id="create_type" name="type" class="form-select" required>
                            <option value="general">General Categories</option>
                            <option value="our-products">Our Products</option>
                        </select>
                        <small class="form-help">General Categories: Hand Tools, Safety, etc.<br>Our Products: Ball Cages, Drill Bits, etc.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="create_parent_id" class="form-label">Parent Category</label>
                        <select id="create_parent_id" name="parent_id" class="form-select">
                            <option value="">None (Top Level)</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="create_description" class="form-label">Description</label>
                    <textarea id="create_description" name="description" class="form-textarea" placeholder="Brief description of the category"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="create_icon" class="form-label">Icon (FontAwesome class)</label>
                        <input type="text" id="create_icon" name="icon" class="form-input" placeholder="e.g., fa-tools">
                        <small class="form-help">FontAwesome icon class without 'fas'</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="create_sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="create_sort_order" name="sort_order" class="form-input" min="0" value="0" placeholder="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="create_image" class="form-label">Category Image</label>
                    <input type="file" id="create_image" name="image" class="form-input" accept="image/*">
                    <small class="form-help">PNG, JPG recommended (max 2MB)</small>
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
                <button type="submit" class="btn-modal-primary">Create Category</button>
                <button type="button" class="btn-modal-secondary" onclick="closeCreateModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-edit"></i>
                Edit Category
            </h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        
        <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" id="edit_category_id" name="category_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_name" class="form-label required">Category Name</label>
                        <input type="text" id="edit_name" name="name" class="form-input" required placeholder="Enter category name">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_slug" class="form-label">Slug</label>
                        <input type="text" id="edit_slug" name="slug" class="form-input" placeholder="auto-generated">
                        <small class="form-help">Leave empty to auto-generate from name</small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_type" class="form-label required">Category Type</label>
                        <select id="edit_type" name="type" class="form-select" required>
                            <option value="general">General Categories</option>
                            <option value="our-products">Our Products</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_parent_id" class="form-label">Parent Category</label>
                        <select id="edit_parent_id" name="parent_id" class="form-select">
                            <option value="">None (Top Level)</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_description" class="form-label">Description</label>
                    <textarea id="edit_description" name="description" class="form-textarea" placeholder="Brief description of the category"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_icon" class="form-label">Icon (FontAwesome class)</label>
                        <input type="text" id="edit_icon" name="icon" class="form-input" placeholder="e.g., fa-tools">
                        <small class="form-help">FontAwesome icon class without 'fas'</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="edit_sort_order" name="sort_order" class="form-input" min="0" value="0" placeholder="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_image" class="form-label">Category Image</label>
                    <div id="current_image_container" style="display: none;">
                        <small style="display: block; margin-bottom: 5px; color: #64748b;">Current Image:</small>
                        <img id="current_image" src="" alt="Current Image" class="current-image-preview">
                    </div>
                    <input type="file" id="edit_image" name="image" class="form-input" accept="image/*">
                    <small class="form-help">PNG, JPG recommended (max 2MB) - Leave empty to keep current image</small>
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
                <button type="submit" class="btn-modal-primary">Update Category</button>
                <button type="button" class="btn-modal-secondary" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Modal Styles */
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
    
    .modal-title i {
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
    
    .current-image-preview {
        max-width: 150px;
        margin: 0.5rem 0;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem;
        background: #f8fafc;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @media (max-width: 768px) {
        .modal-container {
            max-width: 95%;
            max-height: 95vh;
        }
        
        .modal-header, .modal-body, .form-actions {
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
        
        .btn-modal-primary, .btn-modal-secondary {
            width: 100%;
        }
    }

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

<script>
    // Store all categories data for easy lookup
    const categoriesData = <?= json_encode($categories ?? []) ?>;
    
    // Create Category Modal Functions
    function openCreateModal() {
        document.getElementById('createModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeCreateModal() {
        document.getElementById('createModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('createCategoryForm').reset();
    }
    
    // Close create modal when clicking outside
    document.getElementById('createModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });
    
    // Handle create form submission
    document.getElementById('createCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show loading state
        const submitBtn = this.querySelector('.btn-modal-primary');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Creating...';
        submitBtn.disabled = true;
        
        fetch('<?= View::url('/admin/categories') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                throw new Error('Creation failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the category');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // Edit Category Modal Functions
    function openEditModal(categoryId) {
        // Find the category data
        const category = categoriesData.find(c => c.id == categoryId);
        if (!category) {
            alert('Category not found');
            return;
        }
        
        // Populate form fields
        document.getElementById('edit_category_id').value = category.id;
        document.getElementById('edit_name').value = category.name || '';
        document.getElementById('edit_slug').value = category.slug || '';
        document.getElementById('edit_type').value = category.type || 'general';
        document.getElementById('edit_parent_id').value = category.parent_id || '';
        document.getElementById('edit_description').value = category.description || '';
        document.getElementById('edit_icon').value = category.icon || '';
        document.getElementById('edit_sort_order').value = category.sort_order || '0';
        document.getElementById('edit_status').value = category.status || 'active';
        
        // Show current image if exists
        const imageContainer = document.getElementById('current_image_container');
        const imageImg = document.getElementById('current_image');
        if (category.image) {
            imageImg.src = '<?= BASE_URL ?>/' + category.image;
            imageContainer.style.display = 'block';
        } else {
            imageContainer.style.display = 'none';
        }
        
        // Set form action
        document.getElementById('editCategoryForm').action = '<?= View::url('/admin/categories/') ?>' + categoryId;
        
        // Show modal
        document.getElementById('editModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('editCategoryForm').reset();
    }
    
    // Close edit modal when clicking outside
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
    
    // Handle edit form submission
    document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const categoryId = document.getElementById('edit_category_id').value;
        
        // Show loading state
        const submitBtn = this.querySelector('.btn-modal-primary');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Updating...';
        submitBtn.disabled = true;
        
        fetch('<?= View::url('/admin/categories/') ?>' + categoryId, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                throw new Error('Update failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the category');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // Delete category
    function deleteCategory(id) {
        if (!confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            return;
        }
        
        fetch('<?= View::url('/admin/categories/') ?>' + id + '/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(() => location.reload())
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the category');
        });
    }
</script>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

<?php
$pageTitle = 'Brands Management';
ob_start();
?>

<style>
    /* Page Header Styles */
    .page-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .page-title { 
        font-size: 1.5rem; 
        font-weight: 700; 
        color: #1e293b; 
        display: flex; 
        align-items: center; 
        gap: 0.75rem; 
    }
    .page-title svg { width: 28px; height: 28px; color: #6366f1; }
    
    /* Button Styles */
    .btn { 
        padding: 0.75rem 1.5rem; 
        border-radius: 8px; 
        font-weight: 600; 
        cursor: pointer; 
        border: none; 
        display: inline-flex; 
        align-items: center; 
        gap: 0.5rem; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.875rem;
        white-space: nowrap;
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
    .btn-primary { 
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); 
        color: white;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }
    .btn-primary:hover { 
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
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
    .btn-sm { padding: 0.5rem 1rem; font-size: 0.75rem; }
    
    /* Brands Grid */
    .brands-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
        gap: 1.25rem; 
    }
    .brand-card { 
        background: white; 
        border-radius: 12px; 
        padding: 1.25rem; 
        border: 1px solid #e2e8f0; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .brand-card:hover { 
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
        transform: translateY(-2px); 
    }
    .brand-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: start; 
        margin-bottom: 1rem; 
    }
    .brand-logo { 
        width: 70px; 
        height: 70px; 
        border-radius: 8px; 
        object-fit: contain; 
        background: #f8fafc; 
        padding: 0.5rem; 
    }
    .brand-name { 
        font-size: 1.125rem; 
        font-weight: 600; 
        color: #1e293b; 
        margin: 0 0 0.5rem 0; 
    }
    .brand-description { 
        font-size: 0.875rem; 
        color: #64748b; 
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }
    .brand-meta { 
        display: flex; 
        gap: 1rem; 
        font-size: 0.8125rem; 
        color: #94a3b8; 
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    .brand-status { 
        display: inline-block; 
        padding: 0.25rem 0.75rem; 
        border-radius: 20px; 
        font-size: 0.75rem; 
        font-weight: 600; 
    }
    .brand-status.active { background: #d1fae5; color: #065f46; }
    .brand-status.inactive { background: #fee2e2; color: #991b1b; }
    .brand-actions { 
        display: flex; 
        gap: 0.5rem; 
        margin-top: 1rem; 
    }
    
    /* Subsections Styles */
    .brand-subsections {
        margin-top: 1rem;
        border-top: 1px solid #e2e8f0;
        padding-top: 1rem;
    }
    .subsections-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        cursor: pointer;
    }
    .subsections-title {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .subsections-toggle {
        font-size: 0.75rem;
        color: #6366f1;
        transition: transform 0.3s;
    }
    .subsections-toggle.expanded {
        transform: rotate(180deg);
    }
    .subsections-list {
        display: none;
        flex-direction: column;
        gap: 0.5rem;
    }
    .subsections-list.expanded {
        display: flex;
    }
    .subsection-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        background: #f8fafc;
        border-radius: 6px;
        font-size: 0.8125rem;
    }
    .subsection-name {
        color: #1e293b;
        font-weight: 500;
    }
    .subsection-count {
        color: #94a3b8;
        font-size: 0.75rem;
    }
    .subsection-actions {
        display: flex;
        gap: 0.25rem;
    }
    .subsection-btn {
        padding: 0.25rem 0.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.6875rem;
        transition: all 0.2s;
    }
    .subsection-btn-assign {
        background: #d1fae5;
        color: #065f46;
    }
    .subsection-btn-assign:hover {
        background: #a7f3d0;
    }
    .subsection-btn-edit {
        background: #e0e7ff;
        color: #4338ca;
    }
    .subsection-btn-edit:hover {
        background: #c7d2fe;
    }
    .subsection-btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }
    .subsection-btn-delete:hover {
        background: #fecaca;
    }
    .add-subsection-form {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    .add-subsection-input {
        flex: 1;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.8125rem;
    }
    .add-subsection-input:focus {
        outline: none;
        border-color: #6366f1;
    }
    .add-subsection-btn {
        padding: 0.5rem 0.75rem;
        background: #10b981;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .add-subsection-btn:hover {
        background: #059669;
    }
    .no-subsections {
        font-size: 0.75rem;
        color: #94a3b8;
        font-style: italic;
        padding: 0.5rem 0;
    }
    
    /* Empty State & Alerts */
    .empty-state { text-align: center; padding: 4rem 1.5rem; color: #94a3b8; }
    .alert { padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.25rem; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

    /* Tablet Responsive */
    @media (max-width: 1024px) {
        .brands-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.25rem;
        }

        .page-header .btn {
            width: 100%;
            justify-content: center;
        }

        .brands-grid {
            grid-template-columns: 1fr;
        }

        .brand-card {
            padding: 1rem;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
        }

        .brand-name {
            font-size: 1rem;
        }

        .brand-actions {
            flex-direction: column;
        }

        .brand-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.1rem;
        }

        .page-title svg {
            width: 24px;
            height: 24px;
        }

        .brand-meta {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    
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

<input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

<?php if (!empty($brands)): ?>
<div class="brands-grid">
    <?php foreach ($brands as $brand): ?>
    <div class="brand-card">
        <div class="brand-header">
            <?php if (!empty($brand['logo'])): ?>
                <img src="<?= View::url('/public/assets/images/brands/' . basename($brand['logo'])) ?>" alt="<?= htmlspecialchars($brand['name']) ?>" class="brand-logo">
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
        
        <?php $brandSubcatCount = count($subcategoriesByBrand[$brand['id']] ?? []); ?>
        <div class="brand-meta">
            <span>📦 <?= $brand['product_count'] ?> Products</span>
            <span>📁 <?= $brandSubcatCount ?> Subsections</span>
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
        
        <!-- Brand Subsections -->
        <?php $brandSubcats = $subcategoriesByBrand[$brand['id']] ?? []; ?>
        <div class="brand-subsections">
            <div class="subsections-header" onclick="toggleSubsections(<?= $brand['id'] ?>)">
                <span class="subsections-title">
                    📁 Subsections (<?= $brandSubcatCount ?>)
                </span>
                <span class="subsections-toggle" id="toggle-<?= $brand['id'] ?>">▼</span>
            </div>
            
            <div class="subsections-list" id="subsections-<?= $brand['id'] ?>">
                <?php if (!empty($brandSubcats)): ?>
                    <?php foreach ($brandSubcats as $subcat): ?>
                        <div class="subsection-item" id="subcat-<?= $subcat['id'] ?>">
                            <div>
                                <span class="subsection-name"><?= htmlspecialchars($subcat['name']) ?></span>
                                <span class="subsection-count">(<?= $subcat['product_count'] ?> products)</span>
                            </div>
                            <div class="subsection-actions">
                                <button class="subsection-btn subsection-btn-assign" onclick="openAddProductModal(<?= $brand['id'] ?>, <?= $subcat['id'] ?>, '<?= htmlspecialchars($brand['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($subcat['name'], ENT_QUOTES) ?>')">
                                    + Add Product
                                </button>
                                <button class="subsection-btn subsection-btn-edit" onclick="editSubsection(<?= $brand['id'] ?>, <?= $subcat['id'] ?>, '<?= htmlspecialchars($subcat['name'], ENT_QUOTES) ?>')">
                                    Edit
                                </button>
                                <?php if ($subcat['product_count'] == 0): ?>
                                    <button class="subsection-btn subsection-btn-delete" onclick="deleteSubsection(<?= $brand['id'] ?>, <?= $subcat['id'] ?>)">
                                        Delete
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-subsections">No subsections yet. Add a subsection first, then assign products to it.</div>
                <?php endif; ?>
                
                <!-- Add Subsection Form -->
                <div class="add-subsection-form">
                    <input type="text" class="add-subsection-input" id="new-subcat-<?= $brand['id'] ?>" placeholder="New subsection name...">
                    <button class="add-subsection-btn" onclick="addSubsection(<?= $brand['id'] ?>)">+ Add</button>
                </div>
            </div>
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
        
        <form id="createBrandForm" method="POST" action="<?= View::url('/admin/brands/store') ?>" enctype="multipart/form-data">
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

<!-- Add Product to Brand Subcategory Modal -->
<div id="addProductModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 900px;">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span id="addProductModalTitle">Add New Product</span>
            </h3>
            <button class="modal-close" onclick="closeAddProductModal()">&times;</button>
        </div>
        
        <form id="addProductToBrandForm" method="POST" action="<?= View::url('/admin/brands/create-product') ?>" enctype="multipart/form-data">
            <div class="modal-body">
                <?= View::csrfField() ?>
                <input type="hidden" id="add_product_brand_id" name="brand_id">
                <input type="hidden" id="add_product_subcategory_id" name="brand_subcategory_id">
                
                <!-- Brand/Subcategory Info Banner -->
                <div class="brand-info-banner" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                    <div style="background: white; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" style="width: 24px; height: 24px;">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #4338ca; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Adding to</div>
                        <div id="addProductBrandInfo" style="font-size: 1rem; font-weight: 700; color: #1e293b;"></div>
                    </div>
                </div>
                
                <!-- Basic Info -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="add_product_name" class="form-label required">Product Name</label>
                        <input type="text" id="add_product_name" name="name" class="form-input" required placeholder="Enter product name">
                    </div>
                    
                    <div class="form-group">
                        <label for="add_product_sku" class="form-label required">SKU</label>
                        <input type="text" id="add_product_sku" name="sku" class="form-input" required placeholder="e.g., PROD-001">
                </div>
                
                <div class="form-group">
                    <label for="add_product_description" class="form-label">Description</label>
                    <textarea id="add_product_description" name="description" class="form-textarea" rows="3" placeholder="Enter product description"></textarea>
                </div>
                
                <!-- Pricing -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="add_product_price" class="form-label required">Price (SAR)</label>
                        <input type="number" id="add_product_price" name="price" class="form-input" required min="0" step="0.01" placeholder="0.00">
                    </div>
                    
                    <div class="form-group">
                        <label for="add_product_compare_price" class="form-label">Compare Price</label>
                        <input type="number" id="add_product_compare_price" name="compare_price" class="form-input" min="0" step="0.01" placeholder="0.00">
                        <small class="form-help">Original price (for showing discounts)</small>
                    </div>
                </div>
                
                <!-- Stock -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="add_product_quantity" class="form-label required">Stock Quantity</label>
                        <input type="number" id="add_product_quantity" name="quantity" class="form-input" required min="0" value="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="add_product_min_quantity" class="form-label">Min. Order Quantity</label>
                        <input type="number" id="add_product_min_quantity" name="min_quantity" class="form-input" min="1" value="1">
                    </div>
                </div>
                
                <!-- Main Image Upload -->
                <div class="form-group">
                    <label class="form-label">Main Product Image</label>
                    <div class="main-image-upload-container">
                        <label for="add_product_main_image" class="main-image-upload-label" id="mainImageLabel">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 48px; height: 48px; color: #94a3b8; margin-bottom: 10px;">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21,15 16,10 5,21"></polyline>
                            </svg>
                            <span style="color: #475569; font-size: 14px; font-weight: 500;">Click to upload main image</span>
                            <small style="color: #94a3b8; font-size: 12px;">PNG, JPG, GIF (max 10MB)</small>
                            <input type="file" id="add_product_main_image" name="image" accept="image/*" style="display: none;">
                        </label>
                        <div id="mainImagePreview" style="display: none; position: relative; border-radius: 12px; overflow: hidden;">
                            <img src="" alt="Main Image Preview" style="width: 100%; max-height: 200px; object-fit: contain; background: #f1f5f9; padding: 10px; border-radius: 12px;">
                            <button type="button" class="remove-main-image" onclick="removeMainImagePreview()" style="position: absolute; top: 10px; right: 10px; width: 32px; height: 32px; border-radius: 50%; background: #ef4444; color: white; border: none; font-size: 20px; cursor: pointer; line-height: 28px;">×</button>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Images -->
                <div class="form-group">
                    <label class="form-label">Additional Images (Optional - Up to 4)</label>
                    <div class="additional-images-upload-grid" id="additionalImagesGrid">
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="add-image-slot" id="addProductSlot<?= $i ?>" onclick="document.getElementById('add_product_additional_<?= $i ?>').click()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; color: #94a3b8;">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span style="font-size: 11px; color: #64748b;">Add Image</span>
                            <input type="file" id="add_product_additional_<?= $i ?>" name="additional_images[]" accept="image/*" style="display: none;">
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <!-- Status -->
                <div class="form-group">
                    <label for="add_product_status" class="form-label">Status</label>
                    <select id="add_product_status" name="status" class="form-select">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
                
                <!-- Product Flags -->
                <div class="form-group">
                    <label class="form-label">Product Attributes</label>
                    <div class="product-flags-container">
                        <label class="product-flag-option">
                            <input type="checkbox" name="featured" value="1">
                            <span class="flag-icon">⭐</span>
                            <span class="flag-label">Featured Product</span>
                        </label>
                        <label class="product-flag-option">
                            <input type="checkbox" name="best_seller" value="1">
                            <span class="flag-icon">🔥</span>
                            <span class="flag-label">Best Seller</span>
                        </label>
                        <label class="product-flag-option">
                            <input type="checkbox" name="new_arrival" value="1">
                            <span class="flag-icon">✨</span>
                            <span class="flag-label">New Arrival</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-modal-primary" id="addProductSubmitBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Create Product
                </button>
                <button type="button" class="btn-modal-secondary" onclick="closeAddProductModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Add Product Modal Specific Styles */
    .main-image-upload-container {
        margin-top: 0.5rem;
    }
    
    .main-image-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 2rem;
        cursor: pointer;
        transition: all 0.3s;
        background: #f8fafc;
    }
    
    .main-image-upload-label:hover {
        border-color: #6366f1;
        background: #ebf8ff;
    }
    
    .additional-images-upload-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-top: 0.5rem;
    }
    
    .add-image-slot {
        aspect-ratio: 1;
        border: 2px dashed #cbd5e0;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #f8fafc;
        position: relative;
        overflow: hidden;
    }
    
    .add-image-slot:hover {
        border-color: #6366f1;
        background: #ebf8ff;
    }
    
    .add-image-slot.has-image {
        border-style: solid;
        border-color: #10b981;
    }
    
    .add-image-slot img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    .add-image-slot .remove-slot-image {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #ef4444;
        color: white;
        border: none;
        font-size: 14px;
        cursor: pointer;
        line-height: 18px;
        display: none;
    }
    
    .add-image-slot.has-image .remove-slot-image {
        display: block;
    }
    
    .product-flags-container {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }
    
    .product-flag-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .product-flag-option:hover {
        border-color: #6366f1;
        background: #f0f9ff;
    }
    
    .product-flag-option input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .product-flag-option input[type="checkbox"]:checked + .flag-icon + .flag-label {
        color: #4338ca;
        font-weight: 600;
    }
    
    .product-flag-option:has(input:checked) {
        border-color: #6366f1;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
    }
    
    .flag-icon {
        font-size: 1.25rem;
    }
    
    .flag-label {
        font-size: 0.875rem;
        color: #475569;
    }
    
    @media (max-width: 768px) {
        .additional-images-upload-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .product-flags-container {
            flex-direction: column;
        }
        
        .product-flag-option {
            width: 100%;
        }
    }
</style>

<!-- Assign Existing Products Modal -->
<div id="assignModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                </svg>
                <span id="assignModalTitle">Assign Existing Products</span>
            </h3>
            <button class="modal-close" onclick="closeAssignModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <input type="hidden" id="assign_brand_id">
            <input type="hidden" id="assign_subcategory_id">
            
            <div class="form-group">
                <label class="form-label">Select Products to Assign</label>
                <p style="font-size: 0.8125rem; color: #64748b; margin-bottom: 1rem;">
                    Select existing products from the list below to assign to this brand subsection.
                </p>
                
                <div style="max-height: 300px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <?php if (!empty($allProducts)): ?>
                        <?php foreach ($allProducts as $product): ?>
                            <label class="product-checkbox-item" style="display: flex; align-items: center; padding: 0.75rem 1rem; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.2s;">
                                <input type="checkbox" class="assign-product-checkbox" value="<?= $product['id'] ?>" 
                                       data-brand="<?= $product['brand_id'] ?>" 
                                       data-subcat="<?= $product['brand_subcategory_id'] ?>"
                                       style="margin-right: 0.75rem; width: 18px; height: 18px;">
                                <div style="flex: 1;">
                                    <div style="font-weight: 500; color: #1e293b;"><?= htmlspecialchars($product['name']) ?></div>
                                    <div style="font-size: 0.75rem; color: #94a3b8;">SKU: <?= htmlspecialchars($product['sku']) ?></div>
                                </div>
                                <?php if ($product['brand_id']): ?>
                                    <span style="font-size: 0.6875rem; background: #fef3c7; color: #92400e; padding: 0.25rem 0.5rem; border-radius: 4px;">
                                        Already assigned
                                    </span>
                                <?php endif; ?>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 2rem; text-align: center; color: #94a3b8;">
                            No products available. Create products first.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn-modal-primary" onclick="assignSelectedProducts()">Assign Selected</button>
            <button type="button" class="btn-modal-secondary" onclick="closeAssignModal()">Cancel</button>
        </div>
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
    
    // Handle create form submission - use native form submit
    document.getElementById('createBrandForm').addEventListener('submit', function(e) {
        // Show loading state
        const submitBtn = this.querySelector('.btn-modal-primary');
        submitBtn.textContent = 'Creating...';
        submitBtn.disabled = true;
        // Let the form submit naturally
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
    
    // ==========================================
    // SUBSECTION MANAGEMENT FUNCTIONS
    // ==========================================
    
    // Toggle subsections visibility
    function toggleSubsections(brandId) {
        const list = document.getElementById('subsections-' + brandId);
        const toggle = document.getElementById('toggle-' + brandId);
        
        list.classList.toggle('expanded');
        toggle.classList.toggle('expanded');
    }
    
    // Add new subsection
    function addSubsection(brandId) {
        const input = document.getElementById('new-subcat-' + brandId);
        const name = input.value.trim();
        
        if (!name) {
            alert('Please enter a subsection name');
            input.focus();
            return;
        }
        
        const csrfToken = document.querySelector('input[name="csrf_token"]').value;
        
        const formData = new FormData();
        formData.append('csrf_token', csrfToken);
        formData.append('name', name);
        
        fetch('<?= View::url('/admin/brands/') ?>' + brandId + '/subcategories', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to add subsection');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
    
    // Edit subsection
    function editSubsection(brandId, subcatId, currentName) {
        const newName = prompt('Edit subsection name:', currentName);
        
        if (newName === null) return; // Cancelled
        if (!newName.trim()) {
            alert('Subsection name cannot be empty');
            return;
        }
        if (newName.trim() === currentName) return; // No change
        
        const csrfToken = document.querySelector('input[name="csrf_token"]').value;
        
        const formData = new FormData();
        formData.append('csrf_token', csrfToken);
        formData.append('name', newName.trim());
        
        fetch('<?= View::url('/admin/brands/') ?>' + brandId + '/subcategories/' + subcatId, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to update subsection');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
    
    // Delete subsection
    function deleteSubsection(brandId, subcatId) {
        if (!confirm('Are you sure you want to delete this subsection?')) {
            return;
        }
        
        const csrfToken = document.querySelector('input[name="csrf_token"]').value;
        
        const formData = new FormData();
        formData.append('csrf_token', csrfToken);
        
        fetch('<?= View::url('/admin/brands/') ?>' + brandId + '/subcategories/' + subcatId + '/delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to delete subsection');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
    
    // Handle Enter key in add subsection input
    document.querySelectorAll('.add-subsection-input').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const brandId = this.id.replace('new-subcat-', '');
                addSubsection(parseInt(brandId));
            }
        });
    });
    
    // ==========================================
    // ASSIGN PRODUCTS MODAL FUNCTIONS
    // ==========================================
    
    function openAssignModal(brandId, subcatId, brandName, subcatName) {
        document.getElementById('assign_brand_id').value = brandId;
        document.getElementById('assign_subcategory_id').value = subcatId;
        document.getElementById('assignModalTitle').textContent = 'Assign Products to ' + brandName + ' → ' + subcatName;
        
        // Uncheck all checkboxes first
        document.querySelectorAll('.assign-product-checkbox').forEach(cb => {
            cb.checked = false;
        });
        
        // Pre-check products already in this subsection
        document.querySelectorAll('.assign-product-checkbox').forEach(cb => {
            if (cb.dataset.brand == brandId && cb.dataset.subcat == subcatId) {
                cb.checked = true;
            }
        });
        
        document.getElementById('assignModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeAssignModal() {
        document.getElementById('assignModal').classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Close assign modal when clicking outside
    document.getElementById('assignModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAssignModal();
        }
    });
    
    function assignSelectedProducts() {
        const brandId = document.getElementById('assign_brand_id').value;
        const subcatId = document.getElementById('assign_subcategory_id').value;
        
        const selectedProducts = [];
        document.querySelectorAll('.assign-product-checkbox:checked').forEach(cb => {
            selectedProducts.push(cb.value);
        });
        
        if (selectedProducts.length === 0) {
            alert('Please select at least one product');
            return;
        }
        
        // Assign each product
        let completed = 0;
        let errors = [];
        
        selectedProducts.forEach(productId => {
            const csrfToken = document.querySelector('input[name="csrf_token"]').value;
            
            const formData = new FormData();
            formData.append('csrf_token', csrfToken);
            formData.append('product_id', productId);
            formData.append('subcategory_id', subcatId);
            
            fetch('<?= View::url('/admin/brands/') ?>' + brandId + '/products', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                completed++;
                if (!data.success) {
                    errors.push(data.message);
                }
                
                if (completed === selectedProducts.length) {
                    if (errors.length > 0) {
                        alert('Some products could not be assigned: ' + errors.join(', '));
                    }
                    location.reload();
                }
            })
            .catch(error => {
                completed++;
                errors.push(error.message);
                
                if (completed === selectedProducts.length) {
                    location.reload();
                }
            });
        });
    }
    
    // Hover effect for product checkboxes
    document.querySelectorAll('.product-checkbox-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.background = '#f8fafc';
        });
        item.addEventListener('mouseleave', function() {
            this.style.background = '';
        });
    });
    
    // ==========================================
    // ADD PRODUCT TO BRAND MODAL FUNCTIONS
    // ==========================================
    
    function openAddProductModal(brandId, subcatId, brandName, subcatName) {
        document.getElementById('add_product_brand_id').value = brandId;
        document.getElementById('add_product_subcategory_id').value = subcatId;
        document.getElementById('addProductModalTitle').textContent = 'Add New Product';
        document.getElementById('addProductBrandInfo').textContent = brandName + ' → ' + subcatName;
        
        // Reset form
        document.getElementById('addProductToBrandForm').reset();
        resetAddProductImagePreviews();
        
        document.getElementById('addProductModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeAddProductModal() {
        document.getElementById('addProductModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('addProductToBrandForm').reset();
        resetAddProductImagePreviews();
    }
    
    function resetAddProductImagePreviews() {
        // Reset main image
        const mainLabel = document.getElementById('mainImageLabel');
        const mainPreview = document.getElementById('mainImagePreview');
        if (mainLabel) mainLabel.style.display = 'flex';
        if (mainPreview) {
            mainPreview.style.display = 'none';
            const img = mainPreview.querySelector('img');
            if (img) img.src = '';
        }
        
        // Reset additional images
        for (let i = 1; i <= 4; i++) {
            const slot = document.getElementById('addProductSlot' + i);
            if (slot) {
                slot.classList.remove('has-image');
                const existingImg = slot.querySelector('img');
                const existingBtn = slot.querySelector('.remove-slot-image');
                if (existingImg) existingImg.remove();
                if (existingBtn) existingBtn.remove();
                
                // Restore default content if needed
                if (!slot.querySelector('svg')) {
                    slot.innerHTML = `
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; color: #94a3b8;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span style="font-size: 11px; color: #64748b;">Add Image</span>
                        <input type="file" id="add_product_additional_${i}" name="additional_images[]" accept="image/*" style="display: none;">
                    `;
                    // Re-attach event listener
                    attachAdditionalImageListener(i);
                }
            }
        }
    }
    
    // Close add product modal when clicking outside
    document.getElementById('addProductModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddProductModal();
        }
    });
    
    // Main image preview
    document.getElementById('add_product_main_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('mainImagePreview');
                const label = document.getElementById('mainImageLabel');
                const img = preview.querySelector('img');
                img.src = event.target.result;
                preview.style.display = 'block';
                label.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
    
    function removeMainImagePreview() {
        document.getElementById('add_product_main_image').value = '';
        document.getElementById('mainImagePreview').style.display = 'none';
        document.getElementById('mainImageLabel').style.display = 'flex';
    }
    
    // Additional images preview
    function attachAdditionalImageListener(index) {
        const input = document.getElementById('add_product_additional_' + index);
        if (input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const slot = document.getElementById('addProductSlot' + index);
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        // Keep the input, add image preview
                        const existingSvg = slot.querySelector('svg');
                        const existingSpan = slot.querySelector('span');
                        if (existingSvg) existingSvg.style.display = 'none';
                        if (existingSpan) existingSpan.style.display = 'none';
                        
                        // Remove old image if exists
                        const oldImg = slot.querySelector('img');
                        const oldBtn = slot.querySelector('.remove-slot-image');
                        if (oldImg) oldImg.remove();
                        if (oldBtn) oldBtn.remove();
                        
                        // Add new image
                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.alt = 'Additional Image ' + index;
                        slot.insertBefore(img, slot.firstChild);
                        
                        // Add remove button
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'remove-slot-image';
                        removeBtn.innerHTML = '×';
                        removeBtn.onclick = function(e) {
                            e.stopPropagation();
                            removeAdditionalImageSlot(index);
                        };
                        slot.appendChild(removeBtn);
                        
                        slot.classList.add('has-image');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }
    
    function removeAdditionalImageSlot(index) {
        const slot = document.getElementById('addProductSlot' + index);
        const input = document.getElementById('add_product_additional_' + index);
        
        if (input) input.value = '';
        
        if (slot) {
            slot.classList.remove('has-image');
            slot.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px; color: #94a3b8;">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span style="font-size: 11px; color: #64748b;">Add Image</span>
                <input type="file" id="add_product_additional_${index}" name="additional_images[]" accept="image/*" style="display: none;">
            `;
            // Re-attach event listener
            attachAdditionalImageListener(index);
        }
    }
    
    // Initialize additional image listeners
    for (let i = 1; i <= 4; i++) {
        attachAdditionalImageListener(i);
    }
    
    // Handle add product form submission
    document.getElementById('addProductToBrandForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('addProductSubmitBtn');
        submitBtn.innerHTML = '<span style="display: inline-flex; align-items: center; gap: 0.5rem;"><svg class="animate-spin" style="width: 16px; height: 16px;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creating...</span>';
        submitBtn.disabled = true;
        // Let form submit naturally
    });
    
    // Update escape key handler to include new modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeAssignModal();
            closeAddProductModal();
        }
    });
</script>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

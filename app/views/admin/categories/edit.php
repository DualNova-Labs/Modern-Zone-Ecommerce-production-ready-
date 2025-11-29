<?php
/**
 * Admin Categories - Edit View
 */
$pageTitle = 'Edit Category';
$breadcrumb = 'Home / Categories / Edit';
ob_start();
?>

<style>
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .form-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .form-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }
    
    .form-body {
        padding: 2rem;
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
    
    .form-group label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    .form-group label .text-danger {
        color: #ef4444;
    }
    
    .form-control {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: #ef4444;
    }
    
    .invalid-feedback {
        font-size: 0.75rem;
        color: #ef4444;
    }
    
    .form-text {
        font-size: 0.75rem;
        color: #64748b;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
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
        transition: all 0.3s;
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
    
    .btn-secondary {
        background: #64748b;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #475569;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-card">
    <div class="form-header">
        <h2 class="form-title">
            <i class="fas fa-folder-open"></i> <?= htmlspecialchars($title) ?>
        </h2>
        <a href="<?= View::url('/admin/categories') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="form-body">
        <form action="<?= View::url('/admin/categories/' . $category->id . '/update') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Category Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                           value="<?= htmlspecialchars($old['name'] ?? $category->name) ?>" 
                           required>
                    <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['name']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           class="form-control <?= isset($errors['slug']) ? 'is-invalid' : '' ?>" 
                           value="<?= htmlspecialchars($old['slug'] ?? $category->slug) ?>"
                           placeholder="Leave empty to auto-generate">
                    <?php if (isset($errors['slug'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['slug']) ?></div>
                    <?php endif; ?>
                    <small class="form-text">URL-friendly version of the name.</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="type">Category Type <span class="text-danger">*</span></label>
                    <select id="type" name="type" class="form-control" required>
                        <option value="general" <?= ($old['type'] ?? $category->type ?? 'general') === 'general' ? 'selected' : '' ?>>
                            General Categories
                        </option>
                        <option value="our-products" <?= ($old['type'] ?? $category->type ?? '') === 'our-products' ? 'selected' : '' ?>>
                            Our Products
                        </option>
                    </select>
                    <small class="form-text">
                        <strong>General Categories:</strong> Hand Tools, Safety, etc.<br>
                        <strong>Our Products:</strong> Ball Cages, Drill Bits, etc.
                    </small>
                </div>

                <div class="form-group">
                    <label for="parent_id">Parent Category</label>
                    <select id="parent_id" name="parent_id" class="form-control">
                        <option value="">None (Top Level)</option>
                        <?php foreach ($parentCategories as $parent): ?>
                            <option value="<?= $parent['id'] ?>" 
                                    <?= ($old['parent_id'] ?? $category->parent_id) == $parent['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($parent['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="description">Description</label>
                <textarea id="description" 
                          name="description" 
                          class="form-control" 
                          rows="3"><?= htmlspecialchars($old['description'] ?? $category->description ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="icon">Icon (FontAwesome class)</label>
                    <input type="text" 
                           id="icon" 
                           name="icon" 
                           class="form-control" 
                           value="<?= htmlspecialchars($old['icon'] ?? $category->icon ?? '') ?>"
                           placeholder="e.g., fas fa-tools">
                    <small class="form-text">FontAwesome icon class</small>
                </div>

                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           class="form-control" 
                           value="<?= htmlspecialchars($old['sort_order'] ?? $category->sort_order ?? '0') ?>"
                           min="0">
                    <small class="form-text">Lower numbers appear first</small>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="active" <?= ($old['status'] ?? $category->status ?? 'active') === 'active' ? 'selected' : '' ?>>
                            Active
                        </option>
                        <option value="inactive" <?= ($old['status'] ?? $category->status ?? '') === 'inactive' ? 'selected' : '' ?>>
                            Inactive
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Category
                </button>
                <a href="<?= View::url('/admin/categories') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

<?php
$pageTitle = 'Edit Brand';
ob_start();
?>

<style>
    .section { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; max-width: 900px; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; }
    input, select, textarea { width: 100%; padding: 10px; border: 2px solid #ecf0f1; border-radius: 6px; font-size: 14px; }
    input:focus, select:focus, textarea:focus { outline: none; border-color: #3498db; }
    textarea { min-height: 120px; resize: vertical; font-family: inherit; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .error { color: #e74c3c; font-size: 13px; margin-top: 5px; }
    .btn { padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; }
    .btn-primary { background: #3498db; color: white; }
    .btn-secondary { background: #95a5a6; color: white; margin-left: 10px; }
    .form-actions { display: flex; gap: 10px; margin-top: 30px; }
    .current-logo { max-width: 150px; margin: 10px 0; border: 2px solid #ecf0f1; border-radius: 8px; padding: 10px; }
</style>

<div class="section">
    <h2 style="font-size: 20px; margin-bottom: 25px; font-weight: 700; color: #1e293b;">✏️ Edit Brand</h2>

    <form method="POST" action="<?= View::url('/admin/brands/' . $brand->id) ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="name">Brand Name *</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($brand->name ?? '') ?>" required>
                <?php if (!empty($errors['name'])): ?>
                <div class="error"><?= $errors['name'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($brand->slug ?? '') ?>">
                <small style="color: #64748b;">Leave empty to auto-generate</small>
                <?php if (!empty($errors['slug'])): ?>
                <div class="error"><?= $errors['slug'] ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" value="<?= htmlspecialchars($brand->description ?? '') ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="website">Website</label>
                <input type="url" id="website" name="website" value="<?= htmlspecialchars($brand->website ?? '') ?>" placeholder="https://brand.com">
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" value="<?= htmlspecialchars($brand->country ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="founded_year">Founded Year</label>
                <input type="number" id="founded_year" name="founded_year" value="<?= htmlspecialchars($brand->founded_year ?? '') ?>" min="1800" max="<?= date('Y') ?>">
            </div>

            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" value="<?= htmlspecialchars($brand->sort_order ?? '0') ?>" min="0">
            </div>
        </div>

        <div class="form-group">
            <label for="logo">Brand Logo</label>
            <?php if (!empty($brand->logo)): ?>
                <div class="form-group">
                    <label style="font-size: 13px; color: #64748b;">Current Logo:</label>
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($brand->logo) ?>" alt="Current Logo" class="current-logo" 
                         onerror="this.style.display='none'">
                </div>
            <?php endif; ?>
            <input type="file" id="logo" name="logo" accept="image/*">
            <small style="color: #64748b;">PNG, JPG, SVG recommended (max 2MB) - Leave empty to keep current logo</small>
        </div>

        <div class="form-group">
            <label for="about">About Brand</label>
            <textarea id="about" name="about"><?= htmlspecialchars($brand->about ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="specialties">Specialties</label>
            <textarea id="specialties" name="specialties"><?= htmlspecialchars($brand->specialties ?? '') ?></textarea>
            <small style="color: #64748b;">e.g., Cutting Tools, Measuring Instruments, etc.</small>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="active" <?= ($brand->status ?? 'active') == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($brand->status ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Brand</button>
            <a href="<?= View::url('/admin/brands') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

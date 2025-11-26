<?php
$pageTitle = 'Add New Brand';
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
</style>

<div class="section">
    <h2 style="font-size: 20px; margin-bottom: 25px; font-weight: 700; color: #1e293b;">🏢 Add New Brand</h2>

    <form method="POST" action="<?= View::url('/admin/brands') ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="name">Brand Name *</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                <?php if (!empty($errors['name'])): ?>
                <div class="error"><?= $errors['name'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($old['slug'] ?? '') ?>">
                <small style="color: #64748b;">Leave empty to auto-generate</small>
                <?php if (!empty($errors['slug'])): ?>
                <div class="error"><?= $errors['slug'] ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" value="<?= htmlspecialchars($old['description'] ?? '') ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="website">Website</label>
                <input type="url" id="website" name="website" value="<?= htmlspecialchars($old['website'] ?? '') ?>" placeholder="https://brand.com">
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" value="<?= htmlspecialchars($old['country'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="founded_year">Founded Year</label>
                <input type="number" id="founded_year" name="founded_year" value="<?= htmlspecialchars($old['founded_year'] ?? '') ?>" min="1800" max="<?= date('Y') ?>">
            </div>

            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" value="<?= htmlspecialchars($old['sort_order'] ?? '0') ?>" min="0">
            </div>
        </div>

        <div class="form-group">
            <label for="logo">Brand Logo</label>
            <input type="file" id="logo" name="logo" accept="image/*">
            <small style="color: #64748b;">PNG, JPG, SVG recommended (max 2MB)</small>
        </div>

        <div class="form-group">
            <label for="about">About Brand</label>
            <textarea id="about" name="about"><?= htmlspecialchars($old['about'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="specialties">Specialties</label>
            <textarea id="specialties" name="specialties"><?= htmlspecialchars($old['specialties'] ?? '') ?></textarea>
            <small style="color: #64748b;">e.g., Cutting Tools, Measuring Instruments, etc.</small>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="active" <?= ($old['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($old['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Brand</button>
            <a href="<?= View::url('/admin/brands') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

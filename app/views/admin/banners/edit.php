<?php
ob_start();
?>

<div class="admin-header">
    <div class="admin-header-content">
        <h1 class="admin-title">
            <svg class="admin-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Edit Banner
        </h1>
        <a href="<?= View::url('admin/banners') ?>" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Banners
        </a>
    </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?= $_SESSION['error'] ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="admin-card">
    <form method="POST" action="<?= View::url('admin/banners/update/' . $banner['id']) ?>" enctype="multipart/form-data" class="admin-form">
        
        <div class="form-section">
            <h3 class="form-section-title">Banner Content</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="title" class="form-label required">Banner Title</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="form-control" 
                           required 
                           value="<?= htmlspecialchars($banner['title']) ?>"
                           placeholder="e.g., Industrial Tools & Equipment"
                           maxlength="255">
                    <small class="form-help">Main headline displayed on the banner</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="subtitle" class="form-label">Banner Subtitle</label>
                    <textarea id="subtitle" 
                              name="subtitle" 
                              class="form-control" 
                              rows="3"
                              placeholder="e.g., Premium Quality Tools for Professional Use"><?= htmlspecialchars($banner['subtitle']) ?></textarea>
                    <small class="form-help">Supporting text displayed below the title</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="badge" class="form-label">Badge Text</label>
                    <input type="text" 
                           id="badge" 
                           name="badge" 
                           class="form-control" 
                           value="<?= htmlspecialchars($banner['badge']) ?>"
                           placeholder="e.g., NEW ARRIVALS"
                           maxlength="100">
                    <small class="form-help">Small label displayed above the title</small>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Banner Image</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Current Image</label>
                    <div class="image-preview">
                        <img src="<?= BASE_URL . '/' . htmlspecialchars($banner['image']) ?>" 
                             alt="<?= htmlspecialchars($banner['title']) ?>" 
                             style="max-width: 100%; max-height: 300px; border-radius: 8px;"
                             onerror="this.src='<?= View::asset('images/placeholder.svg') ?>'">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="image" class="form-label">Change Image (Optional)</label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           class="form-control" 
                           accept="image/*"
                           onchange="previewImage(event)">
                    <small class="form-help">Recommended size: 1920x800px (JPG, PNG, WebP)</small>
                </div>
            </div>

            <div class="form-row" id="imagePreviewContainer" style="display: none;">
                <div class="form-group">
                    <label class="form-label">New Image Preview</label>
                    <div class="image-preview">
                        <img id="imagePreview" src="" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Call-to-Action</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="link_text" class="form-label">Button Text</label>
                    <input type="text" 
                           id="link_text" 
                           name="link_text" 
                           class="form-control" 
                           value="<?= htmlspecialchars($banner['link_text']) ?>"
                           placeholder="e.g., Shop Now, Learn More"
                           maxlength="100">
                    <small class="form-help">Text displayed on the button</small>
                </div>

                <div class="form-group">
                    <label for="link_url" class="form-label">Button Link</label>
                    <input type="text" 
                           id="link_url" 
                           name="link_url" 
                           class="form-control" 
                           value="<?= htmlspecialchars($banner['link_url']) ?>"
                           placeholder="e.g., /products, /categories/cutting-tools"
                           maxlength="255">
                    <small class="form-help">URL where the button should link to</small>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section-title">Settings</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           class="form-control" 
                           value="<?= $banner['sort_order'] ?>"
                           min="0"
                           step="1">
                    <small class="form-help">Lower numbers appear first in the slider</small>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="active" <?= $banner['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $banner['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                    <small class="form-help">Only active banners are displayed on the website</small>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Update Banner
            </button>
            <a href="<?= View::url('admin/banners') ?>" class="btn btn-secondary btn-lg">
                Cancel
            </a>
            <button type="button" 
                    onclick="if(confirm('Are you sure you want to delete this banner?')) { window.location.href='<?= View::url('admin/banners/delete/' . $banner['id']) ?>'; }" 
                    class="btn btn-danger btn-lg ml-auto">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Delete Banner
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const container = document.getElementById('imagePreviewContainer');
            preview.src = e.target.result;
            container.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/admin/layouts/main.php';
?>

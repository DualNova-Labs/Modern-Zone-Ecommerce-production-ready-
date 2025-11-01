<?php
ob_start();
?>

<div class="admin-header">
    <div class="admin-header-content">
        <h1 class="admin-title">
            <svg class="admin-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Create New Banner
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
    <form method="POST" action="<?= View::url('admin/banners/store') ?>" enctype="multipart/form-data" class="admin-form">
        
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
                              placeholder="e.g., Premium Quality Tools for Professional Use"></textarea>
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
                    <label for="image" class="form-label required">Banner Image</label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           class="form-control" 
                           accept="image/*"
                           required
                           onchange="previewImage(event)">
                    <small class="form-help">Recommended size: 1920x800px (JPG, PNG, WebP)</small>
                </div>
            </div>

            <div class="form-row" id="imagePreviewContainer" style="display: none;">
                <div class="form-group">
                    <label class="form-label">Image Preview</label>
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
                           value="Shop Now"
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
                           value="0"
                           min="0"
                           step="1">
                    <small class="form-help">Lower numbers appear first in the slider</small>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
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
                Create Banner
            </button>
            <a href="<?= View::url('admin/banners') ?>" class="btn btn-secondary btn-lg">
                Cancel
            </a>
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

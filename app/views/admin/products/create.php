<?php
$pageTitle = 'Add New Product';
$breadcrumb = 'Home / Products / Create';
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
        .checkbox-group { display: flex; align-items: center; gap: 10px; }
        .checkbox-group input[type="checkbox"] { width: auto; }
        .error { color: #e74c3c; font-size: 13px; margin-top: 5px; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; }
        .btn-primary { background: #3498db; color: white; }
        .btn-secondary { background: #95a5a6; color: white; margin-left: 10px; }
        .form-actions { display: flex; gap: 10px; margin-top: 30px; }
</style>

<div class="section">
    <h2 style="font-size: 20px; margin-bottom: 25px; font-weight: 700; color: #1e293b;">➕ Add New Product</h2>

            <form method="POST" action="<?= View::url('/admin/products') ?>" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                        <?php if (!empty($errors['name'])): ?>
                        <div class="error"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="sku">SKU *</label>
                        <input type="text" id="sku" name="sku" value="<?= htmlspecialchars($old['sku'] ?? '') ?>" required>
                        <?php if (!empty($errors['sku'])): ?>
                        <div class="error"><?= $errors['sku'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= ($old['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['category_id'])): ?>
                        <div class="error"><?= $errors['category_id'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="brand_id">Brand</label>
                        <select id="brand_id" name="brand_id">
                            <option value="">Select Brand</option>
                            <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['id'] ?>" <?= ($old['brand_id'] ?? '') == $brand['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($brand['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price (SAR) *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($old['price'] ?? '') ?>" required>
                        <?php if (!empty($errors['price'])): ?>
                        <div class="error"><?= $errors['price'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="compare_price">Compare Price (SAR)</label>
                        <input type="number" id="compare_price" name="compare_price" step="0.01" min="0" value="<?= htmlspecialchars($old['compare_price'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cost">Cost (SAR)</label>
                        <input type="number" id="cost" name="cost" step="0.01" min="0" value="<?= htmlspecialchars($old['cost'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="quantity">Stock Quantity *</label>
                        <input type="number" id="quantity" name="quantity" min="0" value="<?= htmlspecialchars($old['quantity'] ?? '0') ?>" required>
                        <?php if (!empty($errors['quantity'])): ?>
                        <div class="error"><?= $errors['quantity'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="min_quantity">Minimum Order Quantity</label>
                        <input type="number" id="min_quantity" name="min_quantity" min="1" value="<?= htmlspecialchars($old['min_quantity'] ?? '1') ?>">
                    </div>

                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" id="weight" name="weight" step="0.01" min="0" value="<?= htmlspecialchars($old['weight'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="specifications">Specifications</label>
                    <textarea id="specifications" name="specifications"><?= htmlspecialchars($old['specifications'] ?? '') ?></textarea>
                </div>

                <!-- Image Upload Section -->
                <div class="form-group">
                    <label>Product Images (Upload up to 5 images)</label>
                    <div class="image-upload-container">
                        <div class="primary-image-upload">
                            <label for="main-image" class="upload-label">
                                <i class="fas fa-image"></i>
                                <span>Main Product Image</span>
                                <input type="file" id="main-image" name="image" accept="image/*" style="display: none;">
                            </label>
                            <div id="main-image-preview" style="display: none;">
                                <img src="" alt="Main Image Preview">
                                <button type="button" class="remove-image" onclick="removeMainImage()">×</button>
                            </div>
                        </div>
                        
                        <div class="additional-images-upload">
                            <label class="section-label">Additional Images (Optional)</label>
                            <div class="upload-grid" id="additional-images-grid">
                                <div class="upload-slot" onclick="document.getElementById('additional-image-1').click()">
                                    <i class="fas fa-plus"></i>
                                    <span>Add Image</span>
                                    <input type="file" id="additional-image-1" name="additional_images[]" accept="image/*" style="display: none;">
                                </div>
                                <div class="upload-slot" onclick="document.getElementById('additional-image-2').click()">
                                    <i class="fas fa-plus"></i>
                                    <span>Add Image</span>
                                    <input type="file" id="additional-image-2" name="additional_images[]" accept="image/*" style="display: none;">
                                </div>
                                <div class="upload-slot" onclick="document.getElementById('additional-image-3').click()">
                                    <i class="fas fa-plus"></i>
                                    <span>Add Image</span>
                                    <input type="file" id="additional-image-3" name="additional_images[]" accept="image/*" style="display: none;">
                                </div>
                                <div class="upload-slot" onclick="document.getElementById('additional-image-4').click()">
                                    <i class="fas fa-plus"></i>
                                    <span>Add Image</span>
                                    <input type="file" id="additional-image-4" name="additional_images[]" accept="image/*" style="display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .image-upload-container { display: grid; grid-template-columns: 1fr; gap: 20px; margin-top: 10px; }
                    .primary-image-upload { position: relative; }
                    .upload-label { display: flex; flex-direction: column; align-items: center; justify-content: center; 
                                   border: 2px dashed #cbd5e0; border-radius: 12px; padding: 40px; cursor: pointer; 
                                   transition: all 0.3s; background: #f8fafc; }
                    .upload-label:hover { border-color: #3498db; background: #ebf8ff; }
                    .upload-label i { font-size: 48px; color: #94a3b8; margin-bottom: 10px; }
                    .upload-label span { color: #475569; font-size: 14px; font-weight: 500; }
                    #main-image-preview { position: relative; border-radius: 12px; overflow: hidden; }
                    #main-image-preview img { width: 100%; max-height: 300px; object-fit: contain; display: block; 
                                              background: #f1f5f9; padding: 10px; border-radius: 12px; }
                    .remove-image { position: absolute; top: 10px; right: 10px; width: 32px; height: 32px; 
                                   border-radius: 50%; background: #ef4444; color: white; border: none; 
                                   font-size: 20px; cursor: pointer; line-height: 28px; }
                    .remove-image:hover { background: #dc2626; }
                    .section-label { display: block; margin-bottom: 10px; font-weight: 500; color: #334155; }
                    .upload-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
                    .upload-slot { aspect-ratio: 1; border: 2px dashed #cbd5e0; border-radius: 8px; display: flex; 
                                  flex-direction: column; align-items: center; justify-content: center; cursor: pointer; 
                                  transition: all 0.3s; background: #f8fafc; position: relative; }
                    .upload-slot:hover { border-color: #3498db; background: #ebf8ff; }
                    .upload-slot i { font-size: 24px; color: #94a3b8; margin-bottom: 5px; }
                    .upload-slot span { color: #64748b; font-size: 12px; }
                    .upload-slot img { width: 100%; height: 100%; object-fit: cover; display: block; }
                    .upload-slot.has-image { border-style: solid; border-color: #10b981; }
                </style>

                <script>
                    // Main image preview
                    document.getElementById('main-image').addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                const preview = document.getElementById('main-image-preview');
                                const img = preview.querySelector('img');
                                img.src = event.target.result;
                                preview.style.display = 'block';
                                document.querySelector('.upload-label').style.display = 'none';
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    function removeMainImage() {
                        document.getElementById('main-image').value = '';
                        document.getElementById('main-image-preview').style.display = 'none';
                        document.querySelector('.upload-label').style.display = 'flex';
                    }

                    // Additional images preview
                    for (let i = 1; i <= 4; i++) {
                        document.getElementById(`additional-image-${i}`).addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            if (file) {
                                const slot = this.parentElement;
                                const reader = new FileReader();
                                reader.onload = function(event) {
                                    slot.innerHTML = `
                                        <img src="${event.target.result}" alt="Preview">
                                        <button type="button" class="remove-image" onclick="removeAdditionalImage(${i})" style="position: absolute; top: 5px; right: 5px; width: 24px; height: 24px; font-size: 16px; line-height: 20px;">×</button>
                                    `;
                                    slot.classList.add('has-image');
                                    slot.onclick = null;
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    }

                    function removeAdditionalImage(index) {
                        const input = document.getElementById(`additional-image-${index}`);
                        const slot = input.parentElement;
                        input.value = '';
                        slot.classList.remove('has-image');
                        slot.innerHTML = `
                            <i class="fas fa-plus"></i>
                            <span>Add Image</span>
                        `;
                        slot.onclick = () => input.click();
                    }
                </script>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="active" <?= ($old['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($old['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Product Flags</label>
                        <div style="display: flex; gap: 20px; margin-top: 10px;">
                            <div class="checkbox-group">
                                <input type="checkbox" id="featured" name="featured" <?= !empty($old['featured']) ? 'checked' : '' ?>>
                                <label for="featured" style="margin: 0;">Featured</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="best_seller" name="best_seller" <?= !empty($old['best_seller']) ? 'checked' : '' ?>>
                                <label for="best_seller" style="margin: 0;">Best Seller</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="new_arrival" name="new_arrival" <?= !empty($old['new_arrival']) ? 'checked' : '' ?>>
                                <label for="new_arrival" style="margin: 0;">New Arrival</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Create Product</button>
                <a href="<?= View::url('/admin/products') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
</div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

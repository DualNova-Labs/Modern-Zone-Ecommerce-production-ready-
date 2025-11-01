<?php
$pageTitle = 'Edit Product';
$breadcrumb = 'Home / Products / Edit';
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
    <h2 style="font-size: 20px; margin-bottom: 25px; font-weight: 700; color: #1e293b;">✏️ Edit Product</h2>

            <form method="POST" action="<?= View::url('/admin/products/' . $product->id) ?>">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="id" value="<?= $product->id ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($product->name) ?>" required>
                        <?php if (!empty($errors['name'])): ?>
                        <div class="error"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="sku">SKU *</label>
                        <input type="text" id="sku" name="sku" value="<?= htmlspecialchars($product->sku) ?>" required>
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
                            <option value="<?= $category['id'] ?>" <?= $product->category_id == $category['id'] ? 'selected' : '' ?>>
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
                            <option value="<?= $brand['id'] ?>" <?= $product->brand_id == $brand['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($brand['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price (SAR) *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="<?= $product->price ?>" required>
                        <?php if (!empty($errors['price'])): ?>
                        <div class="error"><?= $errors['price'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="compare_price">Compare Price (SAR)</label>
                        <input type="number" id="compare_price" name="compare_price" step="0.01" min="0" value="<?= $product->compare_price ?? '' ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cost">Cost (SAR)</label>
                        <input type="number" id="cost" name="cost" step="0.01" min="0" value="<?= $product->cost ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="quantity">Stock Quantity *</label>
                        <input type="number" id="quantity" name="quantity" min="0" value="<?= $product->quantity ?>" required>
                        <?php if (!empty($errors['quantity'])): ?>
                        <div class="error"><?= $errors['quantity'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="min_quantity">Minimum Order Quantity</label>
                        <input type="number" id="min_quantity" name="min_quantity" min="1" value="<?= $product->min_quantity ?? 1 ?>">
                    </div>

                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" id="weight" name="weight" step="0.01" min="0" value="<?= $product->weight ?? '' ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?= htmlspecialchars($product->description ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="specifications">Specifications</label>
                    <textarea id="specifications" name="specifications"><?= htmlspecialchars($product->specifications ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="active" <?= $product->status == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $product->status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="out_of_stock" <?= $product->status == 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Product Flags</label>
                        <div style="display: flex; gap: 20px; margin-top: 10px;">
                            <div class="checkbox-group">
                                <input type="checkbox" id="featured" name="featured" <?= $product->featured ? 'checked' : '' ?>>
                                <label for="featured" style="margin: 0;">Featured</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="best_seller" name="best_seller" <?= $product->best_seller ? 'checked' : '' ?>>
                                <label for="best_seller" style="margin: 0;">Best Seller</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="new_arrival" name="new_arrival" <?= $product->new_arrival ? 'checked' : '' ?>>
                                <label for="new_arrival" style="margin: 0;">New Arrival</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="<?= View::url('/admin/products') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
</div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

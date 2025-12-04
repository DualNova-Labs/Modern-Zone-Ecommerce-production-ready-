/**
 * Admin Products Page - Edit Modal Fix v6
 * Supports multiple images management
 */

window.openEditModal = function (productId) {
    console.log('=== openEditModal v6 - Multi-Image Support ===');
    console.log('Product ID:', productId);

    var baseUrl = document.querySelector('meta[name="base-url"]') ? document.querySelector('meta[name="base-url"]').content : '';
    var url = baseUrl + '/admin/products/' + productId + '/edit';

    // Remove any existing custom modal
    var existingModal = document.getElementById('customEditModal');
    if (existingModal) existingModal.remove();

    // Create loading overlay
    var overlay = document.createElement('div');
    overlay.id = 'customEditModal';
    overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:999999;display:flex;align-items:center;justify-content:center;overflow-y:auto;padding:20px;';
    overlay.innerHTML = '<div style="background:white;padding:40px;border-radius:12px;font-size:18px;">Loading product data...</div>';
    document.body.appendChild(overlay);

    fetch(url)
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success || !data.product) {
                overlay.innerHTML = '<div style="background:white;padding:40px;border-radius:12px;text-align:center;"><div style="color:red;font-size:18px;margin-bottom:20px;">Error loading product</div><button onclick="document.getElementById(\'customEditModal\').remove()" style="padding:10px 30px;cursor:pointer;border-radius:8px;border:1px solid #ccc;">Close</button></div>';
                return;
            }

            var p = data.product;
            var images = data.images || [];
            console.log('Product loaded:', p.name, 'Images:', images.length);

            // Get categories and brands from the existing form
            var catSelect = document.getElementById('edit_category_id');
            var brandSelect = document.getElementById('edit_brand_id');
            var catOptions = catSelect ? catSelect.innerHTML : '<option value="">No categories</option>';
            var brandOptions = brandSelect ? brandSelect.innerHTML : '<option value="">No brands</option>';

            // Build existing images HTML
            var existingImagesHTML = '';
            if (images.length > 0) {
                existingImagesHTML = '<div style="margin-bottom:20px;"><label style="display:block;margin-bottom:10px;font-weight:600;color:#374151;">📸 Additional Images (' + images.length + ')</label><div id="existingImagesGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:10px;">';
                images.forEach(function (img) {
                    existingImagesHTML += '<div style="position:relative;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;aspect-ratio:1;" data-image-id="' + img.id + '"><img src="' + baseUrl + '/' + img.image_path + '" style="width:100%;height:100%;object-fit:cover;" onerror="this.src=\'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%23f0f0f0%22 width=%22100%22 height=%22100%22/%3E%3Ctext x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%23999%22 font-size=%2212%22%3ENo Image%3C/text%3E%3C/svg%3E\'"><button type="button" onclick="deleteProductImageCustom(' + img.id + ', this)" style="position:absolute;top:4px;right:4px;width:24px;height:24px;border-radius:50%;background:rgba(220,38,38,0.9);color:white;border:none;cursor:pointer;font-size:14px;line-height:1;">×</button></div>';
                });
                existingImagesHTML += '</div></div>';
            }

            // Build the modal HTML
            var modalHTML = `
                <div style="background:white;border-radius:16px;width:95%;max-width:900px;max-height:95vh;overflow-y:auto;box-shadow:0 25px 50px rgba(0,0,0,0.3);">
                    <div style="padding:20px 24px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#f8fafc,#f1f5f9);border-radius:16px 16px 0 0;position:sticky;top:0;z-index:10;">
                        <h2 style="margin:0;font-size:1.5rem;color:#1e293b;">✏️ Edit Product</h2>
                        <button onclick="closeCustomEditModal()" style="background:none;border:none;font-size:28px;cursor:pointer;color:#64748b;padding:0;line-height:1;">&times;</button>
                    </div>
                    <form id="customEditForm" action="${baseUrl}/admin/products/${productId}" method="POST" enctype="multipart/form-data" style="padding:24px;">
                        <input type="hidden" name="csrf_token" value="${document.querySelector('input[name=csrf_token]') ? document.querySelector('input[name=csrf_token]').value : ''}">
                        <input type="hidden" name="id" value="${p.id}">
                        
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                            <div>
                                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">Product Name *</label>
                                <input type="text" name="name" value="${(p.name || '').replace(/"/g, '&quot;')}" required style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">SKU *</label>
                                <input type="text" name="sku" value="${(p.sku || '').replace(/"/g, '&quot;')}" required style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                        </div>
                        
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                            <div>
                                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">Category *</label>
                                <select name="category_id" required style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">${catOptions}</select>
                            </div>
                            <div>
                                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">Brand</label>
                                <select name="brand_id" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">${brandOptions}</select>
                            </div>
                        </div>
                        
                        <div style="margin-bottom:20px;">
                            <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">Description</label>
                            <textarea name="description" rows="3" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box;">${(p.description || '').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</textarea>
                        </div>
                        
                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px;">
                            <div>
                                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">Price (SAR) *</label>
                                <input type="number" name="price" value="${p.price || ''}" required min="0" step="0.01" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">Compare Price</label>
                                <input type="number" name="compare_price" value="${p.compare_price || ''}" min="0" step="0.01" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">Stock *</label>
                                <input type="number" name="quantity" value="${p.quantity || 0}" required min="0" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">Min Order</label>
                                <input type="number" name="min_quantity" value="${p.min_quantity || 1}" min="1" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                            </div>
                        </div>
                        
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                            <div>
                                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">Status</label>
                                <select name="status" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                    <option value="active" ${p.status === 'active' ? 'selected' : ''}>Active</option>
                                    <option value="inactive" ${p.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                                    <option value="out_of_stock" ${p.status === 'out_of_stock' ? 'selected' : ''}>Out of Stock</option>
                                </select>
                            </div>
                            <div>
                                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">🖼️ Main Image</label>
                                <input type="file" name="image" accept="image/*" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                                <div style="font-size:12px;color:#6b7280;margin-top:4px;">Leave empty to keep current</div>
                            </div>
                        </div>
                        
                        ${p.image ? '<div style="margin-bottom:20px;"><label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;">Current Main Image</label><div style="display:inline-block;border:2px solid #10b981;padding:8px;border-radius:10px;background:#f0fdf4;"><img src="' + baseUrl + '/' + p.image + '" style="max-width:150px;max-height:150px;border-radius:6px;display:block;" onerror="this.parentElement.innerHTML=\'<span style=color:#999>Image not found</span>\'"></div></div>' : ''}
                        
                        ${existingImagesHTML}
                        
                        <div style="margin-bottom:20px;padding:16px;border:2px dashed #d1d5db;border-radius:12px;background:#f9fafb;">
                            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;">➕ Add More Images</label>
                            <input type="file" name="additional_images[]" accept="image/*" multiple style="width:100%;padding:8px;font-size:14px;box-sizing:border-box;">
                            <div style="font-size:12px;color:#6b7280;margin-top:6px;">You can select multiple images at once (PNG, JPG, GIF up to 10MB each)</div>
                            <div id="newImagesPreview" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:8px;margin-top:12px;"></div>
                        </div>
                        
                        <div style="margin-bottom:24px;">
                            <label style="display:block;margin-bottom:10px;font-weight:600;color:#374151;">Product Attributes</label>
                            <div style="display:flex;gap:24px;flex-wrap:wrap;">
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                    <input type="checkbox" name="featured" value="1" ${p.featured == 1 ? 'checked' : ''} style="width:18px;height:18px;">
                                    <span>⭐ Featured</span>
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                    <input type="checkbox" name="best_seller" value="1" ${p.best_seller == 1 ? 'checked' : ''} style="width:18px;height:18px;">
                                    <span>🔥 Best Seller</span>
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                    <input type="checkbox" name="new_arrival" value="1" ${p.new_arrival == 1 ? 'checked' : ''} style="width:18px;height:18px;">
                                    <span>✨ New Arrival</span>
                                </label>
                            </div>
                        </div>
                        
                        <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:20px;border-top:1px solid #e5e7eb;">
                            <button type="button" onclick="closeCustomEditModal()" style="padding:12px 28px;border:1px solid #d1d5db;background:white;border-radius:8px;font-size:14px;cursor:pointer;">Cancel</button>
                            <button type="submit" style="padding:12px 28px;background:linear-gradient(135deg,#10b981,#059669);color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">💾 Update Product</button>
                        </div>
                    </form>
                </div>
            `;

            overlay.innerHTML = modalHTML;
            document.body.style.overflow = 'hidden';

            // Set selected values for dropdowns
            setTimeout(function () {
                var catSel = overlay.querySelector('select[name="category_id"]');
                var brandSel = overlay.querySelector('select[name="brand_id"]');
                if (catSel) catSel.value = p.category_id || '';
                if (brandSel) brandSel.value = p.brand_id || '';

                // Add preview for new images
                var fileInput = overlay.querySelector('input[name="additional_images[]"]');
                if (fileInput) {
                    fileInput.addEventListener('change', function () {
                        var preview = document.getElementById('newImagesPreview');
                        preview.innerHTML = '';
                        if (this.files && this.files.length > 0) {
                            Array.from(this.files).forEach(function (file, idx) {
                                var reader = new FileReader();
                                reader.onload = function (e) {
                                    var div = document.createElement('div');
                                    div.style.cssText = 'border-radius:8px;overflow:hidden;aspect-ratio:1;border:1px solid #d1d5db;';
                                    div.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
                                    preview.appendChild(div);
                                };
                                reader.readAsDataURL(file);
                            });
                        }
                    });
                }
            }, 100);

            console.log('Custom modal with multi-image support created!');
        })
        .catch(function (err) {
            overlay.innerHTML = '<div style="background:white;padding:40px;border-radius:12px;text-align:center;"><div style="color:red;font-size:18px;margin-bottom:20px;">Error: ' + err.message + '</div><button onclick="document.getElementById(\'customEditModal\').remove()" style="padding:10px 30px;cursor:pointer;border-radius:8px;border:1px solid #ccc;">Close</button></div>';
            console.error('Error:', err);
        });
};

// Close modal function
window.closeCustomEditModal = function () {
    var modal = document.getElementById('customEditModal');
    if (modal) modal.remove();
    document.body.style.overflow = '';
};

// Delete product image function for custom modal
window.deleteProductImageCustom = function (imageId, button) {
    if (!confirm('Are you sure you want to delete this image?')) {
        return;
    }

    var baseUrl = document.querySelector('meta[name="base-url"]') ? document.querySelector('meta[name="base-url"]').content : '';
    var csrfToken = document.querySelector('input[name="csrf_token"]') ? document.querySelector('input[name="csrf_token"]').value : '';

    // Show loading state
    button.disabled = true;
    button.innerHTML = '...';

    fetch(baseUrl + '/admin/products/images/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'csrf_token=' + encodeURIComponent(csrfToken) + '&image_id=' + imageId
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                // Remove the image element from DOM
                var imageItem = button.closest('[data-image-id]');
                if (imageItem) {
                    imageItem.style.transition = 'opacity 0.3s, transform 0.3s';
                    imageItem.style.opacity = '0';
                    imageItem.style.transform = 'scale(0.8)';
                    setTimeout(function () {
                        imageItem.remove();
                        // Check if grid is now empty
                        var grid = document.getElementById('existingImagesGrid');
                        if (grid && grid.children.length === 0) {
                            grid.parentElement.remove();
                        }
                    }, 300);
                }
            } else {
                alert(data.error || 'Failed to delete image');
                button.disabled = false;
                button.innerHTML = '×';
            }
        })
        .catch(function (err) {
            console.error('Error:', err);
            alert('An error occurred while deleting the image');
            button.disabled = false;
            button.innerHTML = '×';
        });
};

console.log('Admin products fix v6 loaded - Full multi-image support');

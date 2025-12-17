/**
 * VIEW PRODUCTS MODAL JAVASCRIPT  
 * Instructions: Replace the viewSubsectionProducts function around line 2140
 * Also add these helper functions to the script section
 */

// Open View Products Modal
function viewSubsectionProducts(brandId, subcatId, brandName, subcatName) {
    console.log('Opening view products modal', brandId, subcatId);

    const modal = document.getElementById('viewProductsModal');
    if (!modal) {
        console.error('View Products Modal not found!');
        alert('Error: Modal not found. Please refresh the page.');
        return;
    }

    // Set hidden inputs
    document.getElementById('view_products_brand_id').value = brandId;
    document.getElementById('view_products_subcat_id').value = subcatId;
    document.getElementById('viewProductsTitle').textContent = `Products in ${subcatName}`;

    // Load products for this subsection
    loadSubsectionProducts(brandId, subcatId);

    // Show modal
    modal.classList.add('active');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Close View Products Modal
function closeViewProductsModal() {
    const modal = document.getElementById('viewProductsModal');
    if (modal) {
        modal.classList.remove('active');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Load products for a specific subsection
function loadSubsectionProducts(brandId, subcatId) {
    const container = document.getElementById('productsListContainer');

    // Show loading state
    container.innerHTML = `
        <div style="text-align: center; padding: 3rem; color: #64748b;">
            <div style="font-size: 2rem; margin-bottom: 1rem;">⏳</div>
            <div>Loading products...</div>
        </div>
    `;

    // Fetch products from server
    fetch(`<?= View::url('/admin/brands/') ?>${brandId}/subcategories/${subcatId}/products`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.products) {
                displayProducts(data.products, brandId, subcatId);
            } else {
                container.innerHTML = `
                    <div class="products-empty-state">
                        <div class="products-empty-icon">📦</div>
                        <div class="products-empty-text">No products in this subsection yet.</div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading products:', error);
            container.innerHTML = `
                <div style="text-align: center; padding: 3rem; color: #ef4444;">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">⚠️</div>
                    <div>Error loading products. Please try again.</div>
                </div>
            `;
        });
}

// Display products in the modal
function displayProducts(products, brandId, subcatId) {
    const container = document.getElementById('productsListContainer');

    if (!products || products.length === 0) {
        container.innerHTML = `
            <div class="products-empty-state">
                <div class="products-empty-icon">📦</div>
                <div class="products-empty-text">No products in this subsection yet.</div>
            </div>
        `;
        return;
    }

    container.innerHTML = products.map(product => `
        <div class="product-list-item" id="product-item-${product.id}">
            <img src="${product.image || '/public/assets/images/placeholder.png'}" 
                 alt="${escapeHtml(product.name)}" 
                 class="product-image-thumb"
                 onerror="this.src='/public/assets/images/placeholder.png'">
            
            <div class="product-details">
                <div class="product-name">${escapeHtml(product.name)}</div>
                <div class="product-sku">SKU: ${escapeHtml(product.sku)}</div>
                <div class="product-meta-row">
                    <span class="product-price">SAR ${parseFloat(product.price).toFixed(2)}</span>
                    <span class="product-stock">Stock: ${product.quantity}</span>
                    <span class="product-status-badge ${product.status}">${product.status.replace('_', ' ')}</span>
                </div>
            </div>
            
            <div class="product-actions">
                <button class="product-action-btn product-action-btn-edit" 
                        onclick="window.open('<?= View::url('/admin/products/') ?>${product.id}', '_blank')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit
                </button>
                <button class="product-action-btn product-action-btn-remove" 
                        onclick="removeProductFromSubsection(${brandId}, ${subcatId}, ${product.id}, '${escapeHtml(product.name)}')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Remove
                </button>
            </div>
        </div>
    `).join('');
}

// Remove product from subsection
function removeProductFromSubsection(brandId, subcatId, productId, productName) {
    if (!confirm(`Remove "${productName}" from this subsection?\n\nThe product will not be deleted, only unassigned from this subsection.`)) {
        return;
    }

    const csrfToken = document.querySelector('input[name="csrf_token"]').value;
    const formData = new FormData();
    formData.append('csrf_token', csrfToken);

    fetch(`<?= View::url('/admin/brands/') ?>${brandId}/subcategories/${subcatId}/products/${productId}/remove`, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the product item from DOM with animation
                const item = document.getElementById(`product-item-${productId}`);
                if (item) {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    setTimeout(() => item.remove(), 300);
                }

                // Reload products list after a moment
                setTimeout(() => {
                    loadSubsectionProducts(brandId, subcatId);
                    // Also reload the main page to update counts
                    location.reload();
                }, 500);
            } else {
                alert(data.message || 'Failed to remove product');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the product');
        });
}

// Close modal when clicking outside
document.getElementById('viewProductsModal')?.addEventListener('click', function (e) {
    if (e.target === this) {
        closeViewProductsModal();
    }
});

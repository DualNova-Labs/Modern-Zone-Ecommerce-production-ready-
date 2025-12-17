<?php
/**
 * Instructions: Copy the content below and paste it into index.php
 * LOCATION: After line 1942 (after ?> and before <!-- Manage Subsections Modal -->)
 */
?>

<!-- View Subsection Products Modal (Outside Content Buffer) -->
<div id="viewProductsModal" class="modal-overlay" style="z-index: 100001;">
    <div class="modal-container" style="max-width: 900px; z-index: 100002;">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                </svg>
                <span id="viewProductsTitle">Products</span>
            </h3>
            <button class="modal-close" onclick="closeViewProductsModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <input type="hidden" id="view_products_brand_id">
            <input type="hidden" id="view_products_subcat_id">
            
            <!-- Products List Container -->
            <div id="productsListContainer" style="display: flex; flex-direction: column; gap: 1rem; max-height: 500px; overflow-y: auto;">
                <!-- Products will be loaded here -->
            </div>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn-modal-secondary" onclick="closeViewProductsModal()">Close</button>
        </div>
    </div>
</div>

<style>
    /* View Products Modal Styles */
    #viewProductsModal.modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 100001 !important;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    #viewProductsModal.modal-overlay.active {
        display: flex !important;
    }
    
    .product-list-item {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        gap: 1rem;
        align-items: center;
        transition: all 0.3s;
    }
    
    .product-list-item:hover {
        border-color: #6366f1;
        box-shadow: 0 2px 12px rgba(99, 102, 241, 0.15);
        transform: translateY(-1px);
    }
    
    .product-image-thumb {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        background: #f1f5f9;
        flex-shrink: 0;
    }
    
    .product-details {
        flex: 1;
        min-width: 0;
    }
    
    .product-name {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    
    .product-sku {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }
    
    .product-meta-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        font-size: 0.8125rem;
    }
    
    .product-price {
        color: #10b981;
        font-weight: 600;
    }
    
    .product-stock {
        color: #64748b;
    }
    
    .product-status-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .product-status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .product-status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .product-status-badge.out_of_stock {
        background: #fef3c7;
        color: #92400e;
    }
    
    .product-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    
    .product-action-btn {
        padding: 0.5rem 0.75rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        white-space: nowrap;
    }
    
    .product-action-btn svg {
        width: 14px;
        height: 14px;
    }
    
    .product-action-btn-edit {
        background: #e0e7ff;
        color: #4338ca;
    }
    
    .product-action-btn-edit:hover {
        background: #c7d2fe;
        transform: translateY(-1px);
    }
    
    .product-action-btn-remove {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .product-action-btn-remove:hover {
        background: #fecaca;
        transform: translateY(-1px);
    }
    
    .products-empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: #94a3b8;
    }
    
    .products-empty-icon {
        font-size: 64px;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .products-empty-text {
        font-size: 0.9375rem;
        margin-bottom: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .product-list-item {
            flex-direction: column;
            align-items: stretch;
        }
        
        .product-image-thumb {
            width: 100%;
            height: 120px;
        }
        
        .product-actions {
            justify-content: stretch;
        }
        
        .product-action-btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>

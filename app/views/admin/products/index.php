<?php
$pageTitle = 'Products';
$breadcrumb = 'Home / Products';
ob_start();
?>

<style>
    .section-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-content {
        padding: 2rem;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border: 1px solid;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border-color: #10b981;
    }

    .alert-error {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border-color: #ef4444;
    }

    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .search-form {
        display: flex;
        gap: 1rem;
        flex: 1;
        max-width: 700px;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-form input,
    .search-form select {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .search-form input[type="text"] {
        flex: 1;
        min-width: 200px;
    }

    .search-form select {
        min-width: 150px;
    }

    .search-form input:focus,
    .search-form select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
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

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: #475569;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: white;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .mobile-cards {
        display: none;
    }

    .product-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .product-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .product-card-info {
        flex: 1;
    }

    .product-card-id {
        font-size: 0.75rem;
        color: #6366f1;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .product-card-name {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .product-card-brand {
        font-size: 0.875rem;
        color: #64748b;
    }

    .product-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .product-detail {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .product-detail-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .product-detail-value {
        font-size: 0.875rem;
        color: #1e293b;
        font-weight: 500;
    }

    .product-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }

    .product-card-status {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .product-card-actions {
        display: flex;
        gap: 0.5rem;
    }

    th {
        text-align: left;
        padding: 1rem;
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }

    .product-id {
        font-weight: 600;
        color: #6366f1;
        font-size: 0.875rem;
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .product-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }

    .product-brand {
        font-size: 0.75rem;
        color: #64748b;
    }

    .product-sku {
        font-family: 'Courier New', monospace;
        font-size: 0.8125rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }

    .price {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: nowrap;
        align-items: center;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
        flex-wrap: wrap;
        padding: 1rem 0;
    }

    .pagination a,
    .pagination span {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        color: #64748b;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 44px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
    }

    .pagination a:hover {
        background: #f8fafc;
        border-color: #6366f1;
        color: #6366f1;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
    }

    .pagination .active {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border-color: #6366f1;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }

    .pagination .disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .pagination-info {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 1rem;
        font-size: 0.875rem;
        color: #64748b;
        text-align: center;
    }

    .pagination-nav {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .pagination-nav svg {
        width: 16px;
        height: 16px;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 1.125rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .empty-state a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
    }

    .empty-state a:hover {
        text-decoration: underline;
    }

    /* Toggle Badge Styles */
    .toggle-badge {
        padding: 0.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #94a3b8;
        font-size: 1.125rem;
    }

    .toggle-badge:hover {
        transform: scale(1.1);
        border-color: #cbd5e1;
    }

    .toggle-badge.active {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border-color: #f59e0b;
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }

    .toggle-badge.active:hover {
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .toggle-badge i {
        display: block;
    }

    .text-center {
        text-align: center;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }

        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }

    /* Mobile Responsive */
    @media (max-width: 1024px) {
        .toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-form {
            max-width: none;
        }
    }

    @media (max-width: 768px) {
        .section-header {
            padding: 1rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .section-content {
            padding: 1rem;
        }

        .search-form {
            flex-direction: column;
            gap: 0.75rem;
        }

        .search-form input,
        .search-form select {
            width: 100%;
            min-width: auto;
        }

        .actions {
            flex-direction: column;
        }

        .pagination {
            gap: 0.25rem;
            padding: 0.75rem 0;
        }

        .pagination a,
        .pagination span {
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            min-width: 40px;
        }

        .pagination-info {
            font-size: 0.8125rem;
            margin-top: 0.75rem;
        }

        /* Hide table and show cards on mobile */
        .table-container {
            display: none;
        }

        .mobile-cards {
            display: block;
        }

        .product-card-details {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .product-card-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .product-card-actions {
            justify-content: center;
        }
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
    }

    .modal-overlay.active {
        visibility: visible;
        opacity: 1;
    }

    .modal {
        background: white;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 800px;
        width: 100%;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        animation: slideUp 0.3s ease-out;
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px 16px 0 0;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .modal-title svg {
        width: 24px;
        height: 24px;
        color: #6366f1;
    }

    .modal-close {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close svg {
        width: 20px;
        height: 20px;
        color: #64748b;
    }

    .modal-close:hover {
        background: #f1f5f9;
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 2rem;
        overflow-y: auto;
        flex: 1;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1.5rem 2rem;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 0 0 16px 16px;
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

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e293b;
    }

    .form-label.required::after {
        content: ' *';
        color: #ef4444;
    }

    .form-input,
    .form-select,
    .form-textarea {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: inherit;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-help {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: -0.25rem;
    }

    .file-upload {
        position: relative;
    }

    .file-upload-input {
        position: absolute;
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        z-index: -1;
    }

    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
    }

    .file-upload-label:hover {
        border-color: #6366f1;
        background: rgba(99, 102, 241, 0.05);
    }

    .file-upload-icon {
        width: 48px;
        height: 48px;
        color: #64748b;
        margin-bottom: 1rem;
    }

    .file-upload-text {
        color: #64748b;
    }

    .file-upload-text strong {
        color: #6366f1;
        display: inline-block;
    }

    .file-upload-text small {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.75rem;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: #475569;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .modal {
            max-width: 95%;
            max-height: 95vh;
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 1rem;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .modal-title {
            font-size: 1.25rem;
        }

        .modal-footer {
            flex-direction: column;
        }

        .modal-footer .btn {
            width: 100%;
        }
    }

    /* Additional Images Styles */
    .existing-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .existing-image-item {
        position: relative;
        aspect-ratio: 1;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
    }

    .existing-image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .existing-image-delete {
        position: absolute;
        top: 4px;
        right: 4px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        line-height: 1;
        transition: all 0.2s;
        opacity: 0;
    }

    .existing-image-item:hover .existing-image-delete {
        opacity: 1;
    }

    .existing-image-delete:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    .additional-images-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .additional-image-preview {
        position: relative;
        aspect-ratio: 1;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
    }

    .additional-image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="section-card">
    <!-- Hidden CSRF token for JavaScript functions -->
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <div class="section-header">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                style="width: 20px; height: 20px; color: #6366f1;">
                <path
                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                </path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            Product Management
        </h2>
    </div>

    <div class="section-content">

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    style="width: 16px; height: 16px;">
                    <path d="M9 12l2 2 4-4"></path>
                    <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"></path>
                </svg>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    style="width: 16px; height: 16px;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="toolbar">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search products..."
                    value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                <select name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($filters['category'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="active" <?= ($filters['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive
                    </option>
                    <option value="out_of_stock" <?= ($filters['status'] ?? '') == 'out_of_stock' ? 'selected' : '' ?>>Out
                        of Stock</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <button onclick="openCreateModal()" class="btn btn-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    style="width: 16px; height: 16px;">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Product
            </button>
        </div>

        <?php if (!empty($products)): ?>
            <!-- Desktop Table View -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Best Seller</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <div class="product-id">#<?= $product['id'] ?></div>
                                </td>
                                <td>
                                    <div class="product-info">
                                        <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                                        <div class="product-brand"><?= htmlspecialchars($product['brand_name'] ?? 'No Brand') ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-sku"><?= htmlspecialchars($product['sku']) ?></div>
                                </td>
                                <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                                <td>
                                    <div class="price">SAR <?= number_format($product['price'], 2) ?></div>
                                </td>
                                <td>
                                    <?php if ($product['quantity'] <= 10): ?>
                                        <span class="badge badge-danger"><?= $product['quantity'] ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?= $product['quantity'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $statusBadge = [
                                        'active' => 'success',
                                        'inactive' => 'warning',
                                        'out_of_stock' => 'danger'
                                    ];
                                    ?>
                                    <span class="badge badge-<?= $statusBadge[$product['status']] ?? 'warning' ?>">
                                        <?= ucfirst(str_replace('_', ' ', $product['status'])) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button onclick="toggleFeatured(<?= $product['id'] ?>, this)"
                                        class="toggle-badge <?= $product['featured'] ? 'active' : '' ?>"
                                        title="Click to toggle Featured status">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button onclick="toggleBestSeller(<?= $product['id'] ?>, this)"
                                        class="toggle-badge <?= $product['best_seller'] ? 'active' : '' ?>"
                                        title="Click to toggle Best Seller status">
                                        <i class="fas fa-fire"></i>
                                    </button>
                                </td>
                                <td class="actions">
                                    <button type="button" onclick="openEditModal(<?= $product['id'] ?>)"
                                        class="btn btn-primary btn-sm">Edit</button>
                                    <button type="button" onclick="deleteProduct(<?= $product['id'] ?>)"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="mobile-cards">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-card-header">
                            <div class="product-card-info">
                                <div class="product-card-id">#<?= $product['id'] ?></div>
                                <div class="product-card-name"><?= htmlspecialchars($product['name']) ?></div>
                                <div class="product-card-brand"><?= htmlspecialchars($product['brand_name'] ?? 'No Brand') ?>
                                </div>
                            </div>
                        </div>

                        <div class="product-card-details">
                            <div class="product-detail">
                                <div class="product-detail-label">SKU</div>
                                <div class="product-detail-value product-sku"><?= htmlspecialchars($product['sku']) ?></div>
                            </div>
                            <div class="product-detail">
                                <div class="product-detail-label">Category</div>
                                <div class="product-detail-value"><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?>
                                </div>
                            </div>
                            <div class="product-detail">
                                <div class="product-detail-label">Price</div>
                                <div class="product-detail-value price">SAR <?= number_format($product['price'], 2) ?></div>
                            </div>
                            <div class="product-detail">
                                <div class="product-detail-label">Stock</div>
                                <div class="product-detail-value">
                                    <?php if ($product['quantity'] <= 10): ?>
                                        <span class="badge badge-danger"><?= $product['quantity'] ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?= $product['quantity'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="product-card-footer">
                            <div class="product-card-status">
                                <?php
                                $statusBadge = [
                                    'active' => 'success',
                                    'inactive' => 'warning',
                                    'out_of_stock' => 'danger'
                                ];
                                ?>
                                <span class="badge badge-<?= $statusBadge[$product['status']] ?? 'warning' ?>">
                                    <?= ucfirst(str_replace('_', ' ', $product['status'])) ?>
                                </span>
                            </div>
                            <div class="product-card-actions">
                                <button type="button" onclick="openEditModal(<?= $product['id'] ?>)"
                                    class="btn btn-primary btn-sm">Edit</button>
                                <button type="button" onclick="deleteProduct(<?= $product['id'] ?>)"
                                    class="btn btn-danger btn-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($pagination['total_pages'] > 1): ?>
                <div class="pagination">
                    <!-- Previous Button -->
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="?page=<?= $pagination['current_page'] - 1 ?>&search=<?= urlencode($filters['search'] ?? '') ?>&category=<?= $filters['category'] ?? '' ?>&status=<?= $filters['status'] ?? '' ?>"
                            class="pagination-nav" title="Previous Page">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <span class="hidden sm:inline">Previous</span>
                        </a>
                    <?php else: ?>
                        <span class="pagination-nav disabled" title="Previous Page">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <span class="hidden sm:inline">Previous</span>
                        </span>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <?php
                    $start = max(1, $pagination['current_page'] - 2);
                    $end = min($pagination['total_pages'], $pagination['current_page'] + 2);

                    // Show first page if not in range
                    if ($start > 1): ?>
                        <a
                            href="?page=1&search=<?= urlencode($filters['search'] ?? '') ?>&category=<?= $filters['category'] ?? '' ?>&status=<?= $filters['status'] ?? '' ?>">1</a>
                        <?php if ($start > 2): ?>
                            <span class="disabled">...</span>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Current range -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <?php if ($i == $pagination['current_page']): ?>
                            <span class="active"><?= $i ?></span>
                        <?php else: ?>
                            <a
                                href="?page=<?= $i ?>&search=<?= urlencode($filters['search'] ?? '') ?>&category=<?= $filters['category'] ?? '' ?>&status=<?= $filters['status'] ?? '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- Show last page if not in range -->
                    <?php if ($end < $pagination['total_pages']): ?>
                        <?php if ($end < $pagination['total_pages'] - 1): ?>
                            <span class="disabled">...</span>
                        <?php endif; ?>
                        <a
                            href="?page=<?= $pagination['total_pages'] ?>&search=<?= urlencode($filters['search'] ?? '') ?>&category=<?= $filters['category'] ?? '' ?>&status=<?= $filters['status'] ?? '' ?>"><?= $pagination['total_pages'] ?></a>
                    <?php endif; ?>

                    <!-- Next Button -->
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <a href="?page=<?= $pagination['current_page'] + 1 ?>&search=<?= urlencode($filters['search'] ?? '') ?>&category=<?= $filters['category'] ?? '' ?>&status=<?= $filters['status'] ?? '' ?>"
                            class="pagination-nav" title="Next Page">
                            <span class="hidden sm:inline">Next</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    <?php else: ?>
                        <span class="pagination-nav disabled" title="Next Page">
                            <span class="hidden sm:inline">Next</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Pagination Info -->
                <div class="pagination-info">
                    Showing <?= (($pagination['current_page'] - 1) * ($pagination['per_page'] ?? 10)) + 1 ?> to
                    <?= min($pagination['current_page'] * ($pagination['per_page'] ?? 10), $pagination['total_items'] ?? 0) ?>
                    of <?= $pagination['total_items'] ?? 0 ?> products
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <p>No products found.</p>
                <button onclick="openCreateModal()" class="btn btn-success" style="cursor: pointer;">Create your first
                    product</button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Form submission handlers are in the main script block below -->

<!-- Create Product Modal -->
<div id="createProductModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h2 class="modal-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path
                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                    </path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
                Add New Product
            </h2>
            <button type="button" class="modal-close" onclick="closeCreateModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form id="createProductForm" method="POST" action="<?= View::url('/admin/products/store') ?>"
            enctype="multipart/form-data">
            <?= View::csrfField() ?>

            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label required">Product Name</label>
                        <input type="text" id="name" name="name" class="form-input" required
                            placeholder="Enter product name">
                    </div>

                    <div class="form-group">
                        <label for="sku" class="form-label required">SKU</label>
                        <input type="text" id="sku" name="sku" class="form-input" required placeholder="e.g., PROD-001">
                    </div>
                </div>

                <!-- Category Type Selection -->
                <div class="form-group">
                    <label class="form-label required">Category Type</label>
                    <input type="hidden" id="category_type" name="category_type" value="general">
                    <div class="parent-category-selector">
                        <label class="category-radio-option">
                            <input type="radio" name="category_type_radio" value="general" checked
                                onchange="document.getElementById('category_type').value='general'; loadCategoriesForCreate('general');">
                            <span class="category-radio-label">
                                <i class="fas fa-tools"></i>
                                <strong>GENERAL CATEGORIES</strong>
                                <small>Hand Tools, Safety Equipment, etc.</small>
                            </span>
                        </label>
                        <label class="category-radio-option">
                            <input type="radio" name="category_type_radio" value="our-products"
                                onchange="document.getElementById('category_type').value='our-products'; loadCategoriesForCreate('our-products');">
                            <span class="category-radio-label">
                                <i class="fas fa-box"></i>
                                <strong>OUR PRODUCTS</strong>
                                <small>Ball Cages, Drill Bits, etc.</small>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="category_id" class="form-label required">Category</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">Select category</option>
                    </select>
                    <div style="margin-top: 0.5rem; display: flex; justify-content: flex-end;">
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="openCreateCategoryModal('create')">Add Category</button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-textarea" rows="3"
                        placeholder="Enter product description"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="form-label required">Price (SAR)</label>
                        <input type="number" id="price" name="price" class="form-input" required min="0" step="0.01"
                            placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="compare_price" class="form-label">Compare Price</label>
                        <input type="number" id="compare_price" name="compare_price" class="form-input" min="0"
                            step="0.01" placeholder="0.00">
                        <div class="form-help">Original price (for showing discounts)</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quantity" class="form-label required">Stock Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-input" required min="0"
                            value="0">
                    </div>

                    <div class="form-group">
                        <label for="min_quantity" class="form-label">Min. Order Quantity</label>
                        <input type="number" id="min_quantity" name="min_quantity" class="form-input" min="1" value="1">
                    </div>
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">Product Image</label>
                    <div class="file-upload">
                        <input type="file" id="image" name="image" class="file-upload-input" accept="image/*">
                        <label for="image" class="file-upload-label">
                            <svg class="file-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21,15 16,10 5,21"></polyline>
                            </svg>
                            <div class="file-upload-text">
                                <strong>Click to upload</strong> or drag and drop<br>
                                <small>PNG, JPG, GIF up to 10MB (Optional)</small>
                            </div>
                        </label>
                    </div>

                    <!-- Additional Images Section -->
                    <div class="form-group">
                        <label class="form-label">Additional Images (Optional - Up to 4 images)</label>
                        <div class="additional-images-grid">
                            <div class="additional-image-slot" id="add-slot-1">
                                <input type="file" id="additional-image-1" name="additional_images[]"
                                    class="file-upload-input" accept="image/*" style="display: none;">
                                <label for="additional-image-1" class="add-image-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        style="width: 32px; height: 32px;">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span style="font-size: 12px; color: #64748b;">Add Image</span>
                                </label>
                            </div>
                            <div class="additional-image-slot" id="add-slot-2">
                                <input type="file" id="additional-image-2" name="additional_images[]"
                                    class="file-upload-input" accept="image/*" style="display: none;">
                                <label for="additional-image-2" class="add-image-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        style="width: 32px; height: 32px;">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span style="font-size: 12px; color: #64748b;">Add Image</span>
                                </label>
                            </div>
                            <div class="additional-image-slot" id="add-slot-3">
                                <input type="file" id="additional-image-3" name="additional_images[]"
                                    class="file-upload-input" accept="image/*" style="display: none;">
                                <label for="additional-image-3" class="add-image-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        style="width: 32px; height: 32px;">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span style="font-size: 12px; color: #64748b;">Add Image</span>
                                </label>
                            </div>
                            <div class="additional-image-slot" id="add-slot-4">
                                <input type="file" id="additional-image-4" name="additional_images[]"
                                    class="file-upload-input" accept="image/*" style="display: none;">
                                <label for="additional-image-4" class="add-image-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        style="width: 32px; height: 32px;">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span style="font-size: 12px; color: #64748b;">Add Image</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <style>
                        .additional-images-grid {
                            display: grid;
                            grid-template-columns: repeat(4, 1fr);
                            gap: 12px;
                            margin-top: 10px;
                        }

                        .additional-image-slot {
                            aspect-ratio: 1;
                            border: 2px dashed #cbd5e0;
                            border-radius: 8px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            cursor: pointer;
                            transition: all 0.3s;
                            background: #f8fafc;
                            position: relative;
                            overflow: hidden;
                        }

                        .additional-image-slot:hover {
                            border-color: #3498db;
                            background: #ebf8ff;
                        }

                        .add-image-label {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            width: 100%;
                            height: 100%;
                            cursor: pointer;
                        }

                        .additional-image-slot.has-image {
                            border-color: #10b981;
                            border-style: solid;
                        }

                        .additional-image-slot img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            display: block;
                        }

                        .remove-additional-image {
                            position: absolute;
                            top: 5px;
                            right: 5px;
                            width: 24px;
                            height: 24px;
                            border-radius: 50%;
                            background: #ef4444;
                            color: white;
                            border: none;
                            font-size: 16px;
                            cursor: pointer;
                            line-height: 20px;
                            display: none;
                        }

                        .additional-image-slot.has-image .remove-additional-image {
                            display: block;
                        }

                        .remove-additional-image:hover {
                            background: #dc2626;
                        }

                        /* Parent Category Selector Styles */
                        .parent-category-selector {
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 1rem;
                            margin-top: 0.5rem;
                        }

                        .category-radio-option {
                            cursor: pointer;
                        }

                        .category-radio-option input[type="radio"] {
                            display: none;
                        }

                        .category-radio-label {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            padding: 1.25rem 1rem;
                            border: 2px solid #e2e8f0;
                            border-radius: 12px;
                            background: #f8fafc;
                            transition: all 0.3s ease;
                            text-align: center;
                        }

                        .category-radio-label i {
                            font-size: 1.5rem;
                            color: #64748b;
                            margin-bottom: 0.5rem;
                        }

                        .category-radio-label strong {
                            color: #1e293b;
                            font-size: 0.875rem;
                            margin-bottom: 0.25rem;
                        }

                        .category-radio-label small {
                            color: #64748b;
                            font-size: 0.75rem;
                        }

                        .category-radio-option input[type="radio"]:checked+.category-radio-label {
                            border-color: #6366f1;
                            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
                            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
                        }

                        .category-radio-option input[type="radio"]:checked+.category-radio-label i {
                            color: #6366f1;
                        }

                        .category-radio-option input[type="radio"]:checked+.category-radio-label strong {
                            color: #6366f1;
                        }

                        @media (max-width: 768px) {
                            .parent-category-selector {
                                grid-template-columns: 1fr;
                            }
                        }
                    </style>

                    <script>
                        // Additional images upload preview
                        for (let i = 1; i <= 4; i++) {
                            const input = document.getElementById(`additional-image-${i}`);
                            if (input) {
                                input.addEventListener('change', function (e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function (event) {
                                            const slot = document.getElementById(`add-slot-${i}`);
                                            // Keep the input element, just add preview image
                                            const existingInput = slot.querySelector('input[type="file"]');
                                            slot.innerHTML = `
                                            <img src="${event.target.result}" alt="Additional Image ${i}">
                                            <button type="button" class="remove-additional-image" onclick="removeAdditionalImageModal(${i})">×</button>
                                        `;
                                            // Re-add the input element (hidden but with the file)
                                            if (existingInput) {
                                                slot.appendChild(existingInput);
                                            }
                                            slot.classList.add('has-image');
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                });
                            }
                        }

                        function removeAdditionalImageModal(index) {
                            const input = document.getElementById(`additional-image-${index}`);
                            const slot = document.getElementById(`add-slot-${index}`);

                            if (input) input.value = '';

                            if (slot) {
                                slot.classList.remove('has-image');
                                slot.innerHTML = `
                                <input type="file" id="additional-image-${index}" name="additional_images[]" class="file-upload-input" accept="image/*" style="display: none;">
                                <label for="additional-image-${index}" class="add-image-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px;">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span style="font-size: 12px; color: #64748b;">Add Image</span>
                                </label>
                            `;

                                // Re-attach event listener
                                const newInput = document.getElementById(`additional-image-${index}`);
                                if (newInput) {
                                    newInput.addEventListener('change', function (e) {
                                        const file = e.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function (event) {
                                                slot.innerHTML = `
                                            <img src="${event.target.result}" alt="Additional Image ${index}">
                                            <button type="button" class="remove-additional-image" onclick="removeAdditionalImageModal(${index})">×</button>
                                        `;
                                                slot.classList.add('has-image');
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                    });
                                }
                            }
                    </script>

                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Product Attributes</label>
                        <div style="display: flex; gap: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="featured" value="1">
                                <span>⭐ Featured Product</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="best_seller" value="1">
                                <span>🔥 Best Seller</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="new_arrival" value="1">
                                <span>✨ New Arrival</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCreateModal()">
                    Cancel
                </button>
                <button type="submit" id="createSubmitBtn" class="btn btn-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Create Product
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editProductModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h2 class="modal-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit Product
            </h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form id="editProductForm" method="POST" enctype="multipart/form-data">
            <?= View::csrfField() ?>
            <input type="hidden" id="edit_product_id" name="id">

            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_name" class="form-label required">Product Name</label>
                        <input type="text" id="edit_name" name="name" class="form-input" required
                            placeholder="Enter product name">
                    </div>

                    <div class="form-group">
                        <label for="edit_sku" class="form-label required">SKU</label>
                        <input type="text" id="edit_sku" name="sku" class="form-input" required
                            placeholder="e.g., PROD-001">
                    </div>
                </div>

                <!-- Category Type Selection for Edit -->
                <div class="form-group">
                    <label class="form-label required">Category Type</label>
                    <input type="hidden" id="edit_category_type" name="category_type" value="general">
                    <div class="parent-category-selector">
                        <label class="category-radio-option">
                            <input type="radio" name="edit_category_type_radio" value="general" checked
                                onchange="document.getElementById('edit_category_type').value='general'; loadCategoriesForEdit('general');">
                            <span class="category-radio-label">
                                <i class="fas fa-tools"></i>
                                <strong>GENERAL CATEGORIES</strong>
                                <small>Hand Tools, Safety Equipment, etc.</small>
                            </span>
                        </label>
                        <label class="category-radio-option">
                            <input type="radio" name="edit_category_type_radio" value="our-products"
                                onchange="document.getElementById('edit_category_type').value='our-products'; loadCategoriesForEdit('our-products');">
                            <span class="category-radio-label">
                                <i class="fas fa-box"></i>
                                <strong>OUR PRODUCTS</strong>
                                <small>Ball Cages, Drill Bits, etc.</small>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_category_id" class="form-label required">Category</label>
                    <select id="edit_category_id" name="category_id" class="form-select" required>
                        <option value="">Select category</option>
                    </select>
                    <div style="margin-top: 0.5rem; display: flex; justify-content: flex-end;">
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="openCreateCategoryModal('edit')">Add Category</button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea id="edit_description" name="description" class="form-textarea" rows="3"
                            placeholder="Enter product description"></textarea>
                    </div>

                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_price" class="form-label required">Price (SAR)</label>
                        <input type="number" id="edit_price" name="price" class="form-input" required min="0"
                            step="0.01" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="edit_compare_price" class="form-label">Compare Price</label>
                        <input type="number" id="edit_compare_price" name="compare_price" class="form-input" min="0"
                            step="0.01" placeholder="0.00">
                        <div class="form-help">Original price (for showing discounts)</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_quantity" class="form-label required">Stock Quantity</label>
                        <input type="number" id="edit_quantity" name="quantity" class="form-input" required min="0"
                            value="0">
                    </div>

                    <div class="form-group">
                        <label for="edit_min_quantity" class="form-label">Min. Order Quantity</label>
                        <input type="number" id="edit_min_quantity" name="min_quantity" class="form-input" min="1"
                            value="1">
                    </div>
                </div>

                <div id="edit_currentImage"></div>
                <!-- Main Product Image -->
                <div class="form-group">
                    <label for="edit_image" class="form-label">Update Main Image</label>
                    <div class="file-upload">
                        <input type="file" id="edit_image" name="image" class="file-upload-input" accept="image/*">
                        <label for="edit_image" class="file-upload-label">
                            <svg class="file-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21,15 16,10 5,21"></polyline>
                            </svg>
                            <div class="file-upload-text">
                                <strong>Click to upload new image</strong> or drag and drop<br>
                                <small>PNG, JPG, GIF up to 10MB (leave empty to keep current)</small>
                            </div>
                        </label>
                    </div>

                    <!-- Existing Additional Images -->
                    <div id="edit_existingImages" class="form-group" style="display: none;">
                        <label class="form-label">Current Additional Images</label>
                        <div id="edit_existingImagesGrid" class="existing-images-grid"></div>
                    </div>

                    <!-- Add More Images -->
                    <div class="form-group">
                        <label for="edit_additional_images" class="form-label">Add More Product Images</label>
                        <input type="file" id="edit_additional_images" name="additional_images[]" accept="image/*"
                            multiple class="form-input">
                        <div class="form-help">You can select multiple images at once (PNG, JPG, GIF up to 10MB each)
                        </div>
                        <div id="edit_additionalImagesPreview" class="additional-images-preview"></div>
                    </div>

                    <div class="form-group">
                        <label for="edit_status" class="form-label">Status</label>
                        <select id="edit_status" name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Product Attributes</label>
                        <div style="display: flex; gap: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="edit_featured" name="featured" value="1">
                                <span>⭐ Featured Product</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="edit_best_seller" name="best_seller" value="1">
                                <span>🔥 Best Seller</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="edit_new_arrival" name="new_arrival" value="1">
                                <span>✨ New Arrival</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                        Cancel
                    </button>
                    <button type="submit" id="editSubmitBtn" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Update Product
                    </button>
                </div>
        </form>
    </div>
</div>

<script>
        function getCsrfToken() {
            const tokenInput = document.querySelector('input[name="csrf_token"]') || document.querySelector('input[name="_csrf_token"]');
            return tokenInput ? tokenInput.value : '';
        }

        function loadCategories(type, selectId, selectedId = null) {
            const selectEl = document.getElementById(selectId);
            if (!selectEl) return;

            selectEl.innerHTML = '<option value="">Loading...</option>';

            fetch('<?= View::url('/admin/categories/api/by-type') ?>?type=' + encodeURIComponent(type))
                .then(r => r.json())
                .then(data => {
                    console.log('Categories API response:', data);

                    if (!data || !data.success || !Array.isArray(data.categories)) {
                        console.error('Invalid API response:', data);
                        selectEl.innerHTML = '<option value="">Error loading categories</option>';
                        return;
                    }

                    if (data.categories.length === 0) {
                        selectEl.innerHTML = '<option value="">No categories found - Click "Add Category" to create one</option>';
                        return;
                    }

                    const options = ['<option value="">Select category</option>'];
                    data.categories.forEach(c => {
                        const isSelected = selectedId !== null && String(c.id) === String(selectedId);
                        options.push(`<option value="${c.id}" ${isSelected ? 'selected' : ''}>${c.name}</option>`);
                    });
                    selectEl.innerHTML = options.join('');
                })
                .catch((err) => {
                    console.error('Error loading categories:', err);
                    selectEl.innerHTML = '<option value="">Error loading categories</option>';
                });
        }

        function openCreateCategoryModal(context) {
            const type = context === 'edit'
                ? (document.getElementById('edit_category_type')?.value || 'general')
                : (document.getElementById('category_type')?.value || 'general');

            const name = window.prompt('Enter new category name');
            if (!name) return;

            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                alert('CSRF token not found. Please refresh the page.');
                return;
            }

            fetch('<?= View::url('/admin/categories/api/create-inline') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `csrf_token=${encodeURIComponent(csrfToken)}&name=${encodeURIComponent(name)}&type=${encodeURIComponent(type)}`
            })
                .then(r => r.json())
                .then(data => {
                    if (!data || !data.success || !data.category) {
                        alert((data && data.message) ? data.message : 'Failed to create category');
                        return;
                    }

                    const category = data.category;

                    if (context === 'edit') {
                        loadCategoriesForEdit(type, category.id);
                    } else {
                        loadCategoriesForCreate(type);
                        const selectEl = document.getElementById('category_id');
                        if (selectEl) {
                            const opt = document.createElement('option');
                            opt.value = category.id;
                            opt.textContent = category.name;
                            opt.selected = true;
                            selectEl.appendChild(opt);
                        }
                    }

                    alert(data.message || 'Category created');
                })
                .catch(() => {
                    alert('Failed to create category');
                });
        }

        function loadCategoriesForCreate(type) {
            loadCategories(type, 'category_id');
        }

        function loadCategoriesForEdit(type, selectedId = null) {
            loadCategories(type, 'edit_category_id', selectedId);
        }

        function openCreateModal() {
            const modal = document.getElementById('createProductModal');

            const generalRadio = document.querySelector('input[name="category_type_radio"][value="general"]');
            if (generalRadio) {
                generalRadio.checked = true;
                const hiddenType = document.getElementById('category_type');
                if (hiddenType) hiddenType.value = 'general';
            }

            loadCategoriesForCreate('general');

            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeCreateModal() {
            const modal = document.getElementById('createProductModal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';

                const form = document.getElementById('createProductForm');
                if (form) form.reset();
            }
        }

        function openEditModal(productId) {
            const modal = document.getElementById('editProductModal');
            if (!modal) return;

            fetch(`<?= View::url('/admin/products/') ?>${productId}/edit`)
                .then(r => r.json())
                .then(data => {
                    if (!data || !data.success || !data.product) {
                        alert('Failed to load product data');
                        return;
                    }

                    const product = data.product;

                    document.getElementById('edit_product_id').value = product.id;
                    document.getElementById('edit_name').value = product.name || '';
                    document.getElementById('edit_sku').value = product.sku || '';

                    const categoryType = data.category_type || product.category_type || 'general';
                    const radioBtn = document.querySelector(`input[name="edit_category_type_radio"][value="${categoryType}"]`);
                    if (radioBtn) {
                        radioBtn.checked = true;
                    }
                    document.getElementById('edit_category_type').value = categoryType;

                    loadCategoriesForEdit(categoryType, product.category_id || null);

                    document.getElementById('edit_description').value = product.description || '';
                    document.getElementById('edit_price').value = product.price || '';
                    document.getElementById('edit_compare_price').value = product.compare_price || '';
                    document.getElementById('edit_quantity').value = product.quantity || 0;
                    document.getElementById('edit_min_quantity').value = product.min_quantity || 1;
                    document.getElementById('edit_status').value = product.status || 'active';

                    document.getElementById('edit_featured').checked = product.featured == 1;
                    document.getElementById('edit_best_seller').checked = product.best_seller == 1;
                    document.getElementById('edit_new_arrival').checked = product.new_arrival == 1;

                    const currentImageDiv = document.getElementById('edit_currentImage');
                    if (currentImageDiv) {
                        if (product.image) {
                            currentImageDiv.innerHTML = `
                        <div style="margin-bottom: 1rem;">
                            <label class="form-label">Current Main Image</label>
                            <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; background: #f8fafc;">
                                <img src="<?= BASE_URL ?>/${product.image}" alt="${product.name}" style="max-width: 200px; max-height: 200px; object-fit: contain;">
                            </div>
                        </div>
                    `;
                        } else {
                            currentImageDiv.innerHTML = '';
                        }
                    }

                    const existingImagesContainer = document.getElementById('edit_existingImages');
                    const existingImagesGrid = document.getElementById('edit_existingImagesGrid');
                    if (existingImagesContainer && existingImagesGrid) {
                        if (data.images && data.images.length > 0) {
                            existingImagesContainer.style.display = 'block';
                            existingImagesGrid.innerHTML = data.images.map(img => `
                        <div class="existing-image-item" data-image-id="${img.id}">
                            <img src="<?= BASE_URL ?>/${img.image_path}" alt="${img.alt_text || 'Product image'}">
                            <button type="button" class="existing-image-delete" onclick="deleteProductImage(${img.id}, this)" title="Delete image">×</button>
                        </div>
                    `).join('');
                        } else {
                            existingImagesContainer.style.display = 'none';
                            existingImagesGrid.innerHTML = '';
                        }
                    }

                    const editAdditionalPreview = document.getElementById('edit_additionalImagesPreview');
                    if (editAdditionalPreview) editAdditionalPreview.innerHTML = '';
                    const editAdditionalInput = document.getElementById('edit_additional_images');
                    if (editAdditionalInput) editAdditionalInput.value = '';

                    document.getElementById('editProductForm').action = `<?= View::url('/admin/products/') ?>${productId}`;

                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                })
                .catch(() => {
                    alert('An error occurred while loading product data.');
                });
        }

        // Add file preview for additional images
        document.addEventListener('DOMContentLoaded', function () {
            const editAdditionalImagesInput = document.getElementById('edit_additional_images');
            if (editAdditionalImagesInput) {
                editAdditionalImagesInput.addEventListener('change', function (e) {
                    const preview = document.getElementById('edit_additionalImagesPreview');

                    preview.innerHTML = '';

                    if (this.files && this.files.length > 0) {
                        Array.from(this.files).forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = function (event) {
                                const div = document.createElement('div');
                                div.className = 'additional-image-preview';
                                div.innerHTML = `<img src="${event.target.result}" alt="Preview ${index + 1}">`;
                                preview.appendChild(div);
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                });
            }
        });

        // Function to delete individual product image
        function deleteProductImage(imageId, button) {
            if (!confirm('Are you sure you want to delete this image?')) {
                return;
            }

            const csrfToken = document.querySelector('input[name="csrf_token"]').value;

            fetch('<?= View::url('/admin/products/images/delete') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `csrf_token=${encodeURIComponent(csrfToken)}&image_id=${imageId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the image element from DOM
                        const imageItem = button.closest('.existing-image-item');
                        if (imageItem) {
                            imageItem.remove();
                        }

                        // Check if there are any images left
                        const grid = document.getElementById('edit_existingImagesGrid');
                        if (grid && grid.children.length === 0) {
                            document.getElementById('edit_existingImages').style.display = 'none';
                        }

                        alert(data.message || 'Image deleted successfully');
                    } else {
                        alert(data.error || 'Failed to delete image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the image');
                });
        }

        function closeEditModal() {
            const modal = document.getElementById('editProductModal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        function deleteProduct(productId) {
            if (!confirm('Are you sure you want to delete this product?')) {
                return;
            }

            const csrfToken = document.querySelector('input[name="csrf_token"]').value;

            fetch(`<?= View::url('/admin/products/') ?>${productId}/delete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `csrf_token=${encodeURIComponent(csrfToken)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Product deleted successfully');
                        location.reload();
                    } else {
                        alert(data.error || 'Failed to delete product');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the product');
                });
        }

        function toggleFeatured(productId, button) {
            const csrfToken = document.querySelector('input[name="csrf_token"]').value;

            fetch(`<?= View::url('/admin/products/') ?>${productId}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `csrf_token=${encodeURIComponent(csrfToken)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.featured) {
                            button.classList.add('active');
                        } else {
                            button.classList.remove('active');
                        }
                    } else {
                        alert(data.error || 'Failed to update featured status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
        }

        function toggleBestSeller(productId, button) {
            const csrfToken = document.querySelector('input[name="csrf_token"]').value;

            // Fixed URL to match route: toggle-bestseller instead of toggle-best-seller
            fetch(`<?= View::url('/admin/products/') ?>${productId}/toggle-bestseller`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `csrf_token=${encodeURIComponent(csrfToken)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.best_seller) {
                            button.classList.add('active');
                        } else {
                            button.classList.remove('active');
                        }
                    } else {
                        alert(data.error || 'Failed to update best seller status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
        }

        // Close modals when clicking outside
        document.addEventListener('click', function (e) {
            const createModal = document.getElementById('createProductModal');
            const editModal = document.getElementById('editProductModal');

            if (e.target === createModal) {
                closeCreateModal();
            }
            if (e.target === editModal) {
                closeEditModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeCreateModal();
                closeEditModal();
            }
        });

        // Notification helper function
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = message;
            notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        border-radius: 8px;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
    `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Form submission handlers
        document.addEventListener('DOMContentLoaded', function () {
            // Create form submission - use standard form submission to properly show server errors
            const createForm = document.getElementById('createProductForm');
            if (createForm) {
                createForm.addEventListener('submit', function (e) {
                    // Validate required fields before submission
                    const categoryId = document.getElementById('category_id').value;
                    if (!categoryId) {
                        e.preventDefault();
                        alert('Please select a category. If no categories are available, click "Add Category" to create one first.');
                        return false;
                    }

                    const submitBtn = document.getElementById('createSubmitBtn');
                    submitBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; animation: spin 1s linear infinite;"><circle cx="12" cy="12" r="10" stroke-dasharray="32" stroke-dashoffset="32"></circle></svg> Creating...';
                    submitBtn.disabled = true;

                    // Allow normal form submission to handle errors properly via session flash
                    return true;
                });
            }

            // Edit form submission - use standard form submission
            const editForm = document.getElementById('editProductForm');
            if (editForm) {
                editForm.addEventListener('submit', function (e) {
                    // Validate required fields before submission
                    const categoryId = document.getElementById('edit_category_id').value;
                    if (!categoryId) {
                        e.preventDefault();
                        alert('Please select a category. If no categories are available, click "Add Category" to create one first.');
                        return false;
                    }

                    const submitBtn = document.getElementById('editSubmitBtn');
                    submitBtn.innerHTML = '<span style="animation: spin 1s linear infinite; display: inline-block;">⟳</span> Updating...';
                    submitBtn.disabled = true;

                    // Allow normal form submission
                    return true;
                });
            }

            // Image upload preview for create modal
            const imageInput = document.getElementById('image');
            if (imageInput) {
                imageInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    const uploadLabel = document.querySelector('#createProductModal .file-upload-label');

                    if (file && uploadLabel) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            uploadLabel.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: contain;">
                        <div style="margin-top: 1rem; font-size: 0.875rem; color: #64748b;">
                            <strong>${file.name}</strong><br>
                            <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small><br>
                            <button type="button" onclick="clearImageUpload()" style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">
                                Remove Image
                            </button>
                        </div>
                    `;
                            uploadLabel.style.border = '2px solid #10b981';
                            uploadLabel.style.background = 'rgba(16, 185, 129, 0.05)';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Image upload preview for edit modal
            const editImageInput = document.getElementById('edit_image');
            if (editImageInput) {
                editImageInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    const uploadLabel = document.querySelector('#editProductModal .file-upload-label');

                    if (file && uploadLabel) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            uploadLabel.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: contain;">
                        <div style="margin-top: 1rem; font-size: 0.875rem; color: #64748b;">
                            <strong>${file.name}</strong><br>
                            <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small><br>
                            <button type="button" onclick="clearEditImageUpload()" style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">
                                Remove Image
                            </button>
                        </div>
                    `;
                            uploadLabel.style.border = '2px solid #10b981';
                            uploadLabel.style.background = 'rgba(16, 185, 129, 0.05)';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        // Clear image upload for create modal
        function clearImageUpload() {
            const fileInput = document.getElementById('image');
            const uploadLabel = document.querySelector('#createProductModal .file-upload-label');

            if (fileInput) {
                fileInput.value = '';
            }

            if (uploadLabel) {
                uploadLabel.innerHTML = `
            <svg class="file-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21,15 16,10 5,21"></polyline>
            </svg>
            <div class="file-upload-text">
                <strong>Click to upload</strong> or drag and drop<br>
                <small>PNG, JPG, GIF up to 10MB (Optional)</small>
            </div>
        `;
                uploadLabel.style.border = '2px dashed #e2e8f0';
                uploadLabel.style.background = '#f8fafc';
            }
        }

        // Clear image upload for edit modal
        function clearEditImageUpload() {
            const fileInput = document.getElementById('edit_image');
            const uploadLabel = document.querySelector('#editProductModal .file-upload-label');

            if (fileInput) {
                fileInput.value = '';
            }

            if (uploadLabel) {
                uploadLabel.innerHTML = `
            <svg class="file-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21,15 16,10 5,21"></polyline>
            </svg>
            <div class="file-upload-text">
                <strong>Click to upload</strong> or drag and drop<br>
                <small>PNG, JPG, GIF up to 10MB (Optional - leave empty to keep current image)</small>
            </div>
        `;
                uploadLabel.style.border = '2px dashed #e2e8f0';
                uploadLabel.style.background = '#f8fafc';
            }
        }
</script>

<!-- Add base URL meta tag for JavaScript -->
<meta name="base-url" content="<?= BASE_URL ?>">

<!-- 
    Note: admin-products.js is disabled to avoid conflicts with the inline openEditModal function.
    The inline version uses the built-in modal which is more consistent with the page design.
    Uncomment the line below if you prefer the custom modal from admin-products.js
-->
<!-- <script src="<?= BASE_URL ?>/public/assets/js/admin-products.js"></script> -->

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>
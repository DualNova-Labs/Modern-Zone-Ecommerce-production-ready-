<?php
$pageTitle = 'Orders';
$breadcrumb = 'Home / Orders';
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
    
    .status-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        padding: 0.5rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    
    .status-tab {
        padding: 0.75rem 1.25rem;
        background: transparent;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        white-space: nowrap;
    }
    
    .status-tab:hover {
        background: white;
        color: #1e293b;
        border-color: #e2e8f0;
        transform: translateY(-1px);
    }
    
    .status-tab.active {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    
    .filters {
        display: flex;
        gap: 0.75rem;
        flex: 1;
        max-width: 800px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filters input,
    .filters select {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 130px;
        height: 44px;
    }

    .filters .btn {
        height: 44px;
    }
    
    .filters input:focus,
    .filters select:focus {
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
        min-width: 750px;
    }

    /* Column widths using percentages for better fit */
    table th:nth-child(1),
    table td:nth-child(1) { width: 12%; } /* Order # */
    table th:nth-child(2),
    table td:nth-child(2) { width: 18%; } /* Customer */
    table th:nth-child(3),
    table td:nth-child(3) { width: 12%; } /* Amount */
    table th:nth-child(4),
    table td:nth-child(4) { width: 14%; } /* Payment */
    table th:nth-child(5),
    table td:nth-child(5) { width: 12%; text-align: center; } /* Status */
    table th:nth-child(6),
    table td:nth-child(6) { width: 14%; } /* Date */
    table th:nth-child(7),
    table td:nth-child(7) { width: 18%; } /* Actions */
    
    th {
        text-align: left;
        padding: 0.875rem 1rem;
        background: #f8fafc;
        color: #64748b;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    
    td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    tr:last-child td {
        border-bottom: none;
    }
    
    tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }
    
    .order-number {
        font-weight: 600;
        color: #6366f1;
        font-size: 0.9rem;
    }
    
    .customer-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .customer-name {
        font-weight: 500;
        color: #1e293b;
    }
    
    .customer-email {
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .amount {
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
    
    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .badge-secondary {
        background: #f1f5f9;
        color: #64748b;
    }
    
    .payment-info,
    .date-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .payment-method,
    .time {
        font-size: 0.75rem;
        color: #64748b;
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
    
    /* Tablet Responsive */
    @media (max-width: 1200px) {
        table {
            min-width: 700px;
        }
    }

    @media (max-width: 1024px) {
        .toolbar {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }
        
        .filters {
            max-width: none;
            width: 100%;
        }
        
        .filters input,
        .filters select {
            flex: 1;
            min-width: 0;
        }

        .toolbar .btn-success {
            align-self: flex-start;
        }
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .section-header {
            padding: 1.25rem 1rem;
        }
        
        .section-content {
            padding: 1rem;
        }

        .section-title {
            font-size: 1.1rem;
        }
        
        .status-tabs {
            padding: 0.25rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .status-tab {
            padding: 0.5rem 0.75rem;
            font-size: 0.8125rem;
            flex-shrink: 0;
        }

        .toolbar {
            gap: 1rem;
        }
        
        .filters {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .filters input[type="text"] {
            grid-column: 1 / -1;
        }
        
        .filters input,
        .filters select {
            width: 100%;
            min-width: auto;
        }

        .filters .btn {
            grid-column: 1 / -1;
            justify-content: center;
        }

        .toolbar .btn-success {
            width: 100%;
            justify-content: center;
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
    }

    @media (max-width: 480px) {
        .section-header {
            padding: 1rem;
        }

        .section-title {
            font-size: 1rem;
        }

        .filters {
            grid-template-columns: 1fr;
        }

        .status-tabs {
            gap: 0.25rem;
        }

        .status-tab {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }
    }
</style>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #6366f1;">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            Order Management
        </h2>
    </div>
    
    <div class="section-content">

        <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                <path d="M9 12l2 2 4-4"></path>
                <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"></path>
            </svg>
            <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

            <!-- Status Tabs -->
            <div class="status-tabs">
                <div class="status-tab <?= empty($filters['status']) ? 'active' : '' ?>" onclick="filterByStatus('')">
                    All Orders
                </div>
                <?php foreach ($status_counts as $status_count): ?>
                <div class="status-tab <?= ($filters['status'] ?? '') == $status_count['status'] ? 'active' : '' ?>" onclick="filterByStatus('<?= $status_count['status'] ?>')">
                    <?= ucfirst($status_count['status']) ?> (<?= $status_count['count'] ?>)
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Filters -->
            <div class="toolbar">
                <form method="GET" class="filters">
                    <input type="text" name="search" placeholder="Search orders..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                    <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>" placeholder="From Date">
                    <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>" placeholder="To Date">
                    <select name="payment_status">
                        <option value="">All Payments</option>
                        <option value="pending" <?= ($filters['payment_status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="paid" <?= ($filters['payment_status'] ?? '') == 'paid' ? 'selected' : '' ?>>Paid</option>
                        <option value="failed" <?= ($filters['payment_status'] ?? '') == 'failed' ? 'selected' : '' ?>>Failed</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                <a href="<?= View::url('/admin/orders/export?' . http_build_query($filters)) ?>" class="btn btn-success">📥 Export CSV</a>
            </div>

        <?php if (!empty($orders)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <div class="order-number"><?= htmlspecialchars($order['order_number']) ?></div>
                        </td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-name"><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></div>
                                <div class="customer-email"><?= htmlspecialchars($order['customer_email'] ?? '') ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="amount">SAR <?= number_format($order['total_amount'], 2) ?></div>
                        </td>
                        <td>
                            <?php
                            $paymentBadge = [
                                'pending' => 'warning',
                                'paid' => 'success',
                                'failed' => 'danger',
                                'refunded' => 'secondary'
                            ];
                            ?>
                            <div class="payment-info">
                                <span class="badge badge-<?= $paymentBadge[$order['payment_status']] ?? 'secondary' ?>">
                                    <?= ucfirst($order['payment_status']) ?>
                                </span>
                                <div class="payment-method"><?= ucfirst($order['payment_method'] ?? 'N/A') ?></div>
                            </div>
                        </td>
                        <td>
                            <?php
                            $statusBadge = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'info',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                                'refunded' => 'secondary'
                            ];
                            ?>
                            <span class="badge badge-<?= $statusBadge[$order['status']] ?? 'secondary' ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="date-info">
                                <div><?= date('M d, Y', strtotime($order['created_at'])) ?></div>
                                <div class="time"><?= date('h:i A', strtotime($order['created_at'])) ?></div>
                            </div>
                        </td>
                        <td>
                            <a href="<?= View::url('/admin/orders/' . $order['id']) ?>" class="btn btn-primary btn-sm">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

            <?php if ($pagination['total_pages'] > 1): ?>
            <div class="pagination">
                <!-- Previous Button -->
                <?php if ($pagination['current_page'] > 1): ?>
                    <a href="?page=<?= $pagination['current_page'] - 1 ?>&<?= http_build_query($filters) ?>" class="pagination-nav" title="Previous Page">
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
                    <a href="?page=1&<?= http_build_query($filters) ?>">1</a>
                    <?php if ($start > 2): ?>
                        <span class="disabled">...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <!-- Current range -->
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <?php if ($i == $pagination['current_page']): ?>
                        <span class="active"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>&<?= http_build_query($filters) ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <!-- Show last page if not in range -->
                <?php if ($end < $pagination['total_pages']): ?>
                    <?php if ($end < $pagination['total_pages'] - 1): ?>
                        <span class="disabled">...</span>
                    <?php endif; ?>
                    <a href="?page=<?= $pagination['total_pages'] ?>&<?= http_build_query($filters) ?>"><?= $pagination['total_pages'] ?></a>
                <?php endif; ?>
                
                <!-- Next Button -->
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="?page=<?= $pagination['current_page'] + 1 ?>&<?= http_build_query($filters) ?>" class="pagination-nav" title="Next Page">
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
                of <?= $pagination['total_items'] ?? 0 ?> orders
            </div>
            <?php endif; ?>

        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <p>No orders found</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
        function filterByStatus(status) {
            const url = new URL(window.location.href);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
</script>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

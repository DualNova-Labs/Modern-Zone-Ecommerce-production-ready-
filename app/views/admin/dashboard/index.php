<?php
$pageTitle = 'Dashboard';
$breadcrumb = 'Home / Dashboard';

ob_start();
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%);
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(99, 102, 241, 0.2);
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

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    .stat-label.positive {
        color: #10b981;
    }
    
    .stat-label.warning {
        color: #f59e0b;
    }

    .section-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
        overflow: hidden;
        animation: fadeInUp 0.8s ease-out;
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

    .view-all-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        background: #6366f1;
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }

    .view-all-btn:hover {
        background: #4f46e5;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .table-container {
        overflow-x: auto;
        padding: 0 2rem 2rem;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
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

    .order-number {
        font-weight: 600;
        color: #6366f1;
        text-decoration: none;
    }

    .order-number:hover {
        text-decoration: underline;
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

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 2rem;
    }
    
    @media (max-width: 1024px) {
        .grid-2 {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .section-header {
            padding: 1rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .table-container {
            padding: 0 1rem 1rem;
        }
        
        .stat-card {
            padding: 1rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
    }
    
    /* Loading skeleton */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 4px;
    }
    
    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }
    
    /* Empty state */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: #94a3b8;
    }
    
    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state p {
        font-size: 1.125rem;
        font-weight: 500;
    }
</style>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Today's Orders</div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
        </div>
        <div class="stat-value"><?= $stats['today']['orders'] ?? 0 ?></div>
        <div class="stat-label">Revenue: SAR <?= number_format($stats['today']['revenue'] ?? 0, 2) ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">This Month</div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
            </div>
        </div>
        <div class="stat-value"><?= $stats['month']['orders'] ?? 0 ?></div>
        <div class="stat-label">Revenue: SAR <?= number_format($stats['month']['revenue'] ?? 0, 2) ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Orders</div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </div>
        </div>
        <div class="stat-value"><?= $stats['total']['orders'] ?? 0 ?></div>
        <div class="stat-label"><?= $stats['total']['pending_orders'] ?? 0 ?> Pending Orders</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Revenue</div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
        </div>
        <div class="stat-value">SAR <?= number_format($stats['total']['revenue'] ?? 0, 0) ?></div>
        <div class="stat-label"><?= $stats['total']['customers'] ?? 0 ?> Customers</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Active Products</div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="20" x2="12" y2="10"></line>
                    <line x1="18" y1="20" x2="18" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="16"></line>
                </svg>
            </div>
        </div>
        <div class="stat-value"><?= $stats['total']['products'] ?? 0 ?></div>
        <div class="stat-label warning"><?= $stats['total']['low_stock'] ?? 0 ?> Low Stock Items</div>
    </div>
</div>

<!-- Recent Orders -->
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            Recent Orders
        </h2>
        <a href="<?= View::url('/admin/orders') ?>" class="view-all-btn">View All Orders →</a>
    </div>

    <?php if (!empty($recent_orders)): ?>
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recent_orders as $order): ?>
            <tr>
                <td>
                    <a href="<?= View::url('/admin/orders/' . $order['id']) ?>" class="order-number">
                        <?= htmlspecialchars($order['order_number']) ?>
                    </a>
                </td>
                <td>
                    <div style="font-weight: 600;"><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></div>
                    <div style="font-size: 12px; color: #64748b;"><?= htmlspecialchars($order['customer_email'] ?? '') ?></div>
                </td>
                <td style="font-weight: 600;">SAR <?= number_format($order['total_amount'], 2) ?></td>
                <td>
                    <?php
                    $statusClass = [
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    ];
                    ?>
                    <span class="badge badge-<?= $statusClass[$order['status']] ?? 'info' ?>">
                        <?= ucfirst($order['status']) ?>
                    </span>
                </td>
                <td style="color: #64748b;"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon">📦</div>
        <div>No orders yet</div>
    </div>
    <?php endif; ?>
</div>

<div class="grid-2">
    <!-- Low Stock Alert -->
    <?php if (!empty($low_stock_products)): ?>
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #f59e0b;">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                Low Stock Alert
            </h2>
            <a href="<?= View::url('/admin/products?status=low_stock') ?>" class="view-all-btn">View All →</a>
        </div>

        <div class="table-container">
            <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Stock</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($low_stock_products, 0, 5) as $product): ?>
                <tr>
                    <td style="font-weight: 600;"><?= htmlspecialchars($product['name']) ?></td>
                    <td style="color: #64748b;"><?= htmlspecialchars($product['sku']) ?></td>
                    <td><span class="badge badge-danger"><?= $product['quantity'] ?></span></td>
                    <td style="font-weight: 600;">SAR <?= number_format($product['price'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Top Products -->
    <?php if (!empty($top_products)): ?>
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #10b981;">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                Top Selling Products
            </h2>
            <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Last 30 Days</span>
        </div>

        <div class="table-container">
            <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Units Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($top_products, 0, 5) as $product): ?>
                <tr>
                    <td style="font-weight: 600;"><?= htmlspecialchars($product['name']) ?></td>
                    <td><span class="badge badge-success"><?= $product['total_sold'] ?></span></td>
                    <td style="font-weight: 600;">SAR <?= number_format($product['total_revenue'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

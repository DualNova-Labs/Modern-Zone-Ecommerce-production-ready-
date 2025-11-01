<?php
$pageTitle = 'Analytics';
$breadcrumb = 'Home / Analytics';
ob_start();
?>

<style>
    .analytics-grid {
        display: grid;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
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
    
    .chart-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .chart-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    
    .chart-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .bar-chart {
        margin: 1rem 0;
    }
    
    .bar-item {
        margin: 0.75rem 0;
    }
    
    .bar-label {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .bar-container {
        background: #e2e8f0;
        height: 32px;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .bar-fill {
        background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%);
        height: 100%;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 16px;
        position: relative;
    }
    
    .bar-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.3) 0%, transparent 100%);
        border-radius: 16px 16px 0 0;
    }
    
    .bar-value {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }
    
    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }
    
    .mobile-cards {
        display: none;
    }
    
    .analytics-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .analytics-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .analytics-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .analytics-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    .analytics-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .analytics-detail {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .analytics-detail-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .analytics-detail-value {
        font-size: 0.875rem;
        color: #1e293b;
        font-weight: 500;
    }
    
    .analytics-card-metric {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .analytics-card-metric:last-child {
        border-bottom: none;
    }
    
    .metric-name {
        font-size: 0.875rem;
        color: #1e293b;
        font-weight: 500;
    }
    
    .metric-value {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 600;
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
    
    .metric {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    
    .metric:last-child {
        border-bottom: none;
    }
    
    .metric:hover {
        background: rgba(99, 102, 241, 0.05);
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        border-radius: 8px;
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }
    
    .stat-highlight {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        text-align: center;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 1rem;
        opacity: 0.9;
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
    
    /* Mobile Responsive */
    @media (max-width: 1024px) {
        .chart-grid {
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
        
        .section-content {
            padding: 1rem;
        }
        
        .chart-grid {
            gap: 1rem;
        }
        
        .chart-card {
            padding: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        /* Hide tables and show cards on mobile */
        .table-container {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .analytics-card-details {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
    }
</style>
<!-- Sales Analytics -->
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #6366f1;">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="16"></line>
            </svg>
            Sales Analytics
        </h2>
    </div>
    
    <div class="section-content">
        <div class="chart-grid">
            <div class="chart-card">
                <h3 class="chart-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <path d="M9 12l2 2 4-4"></path>
                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"></path>
                    </svg>
                    Sales by Status
                </h3>
                    <div class="bar-chart">
                        <?php 
                        $statusTotals = array_column($sales['status_breakdown'], 'total');
                        $maxTotal = !empty($statusTotals) ? max($statusTotals) : 1;
                        foreach ($sales['status_breakdown'] as $status): 
                            $percentage = $maxTotal > 0 ? ($status['total'] / $maxTotal) * 100 : 0;
                        ?>
                        <div class="bar-item">
                            <div class="bar-label">
                                <?= ucfirst($status['status']) ?> (<?= $status['count'] ?> orders)
                            </div>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: <?= $percentage ?>%"></div>
                                <div class="bar-value">SAR <?= number_format($status['total'], 0) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
            </div>

            <div class="chart-card">
                <h3 class="chart-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Payment Methods
                </h3>
                    <div class="bar-chart">
                        <?php 
                        $paymentTotals = array_column($sales['payment_breakdown'], 'total');
                        $maxPayment = !empty($paymentTotals) ? max($paymentTotals) : 1;
                        foreach ($sales['payment_breakdown'] as $payment): 
                            $percentage = $maxPayment > 0 ? ($payment['total'] / $maxPayment) * 100 : 0;
                        ?>
                        <div class="bar-item">
                            <div class="bar-label">
                                <?= ucfirst($payment['payment_method']) ?> (<?= $payment['count'] ?> orders)
                            </div>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: <?= $percentage ?>%"></div>
                                <div class="bar-value">SAR <?= number_format($payment['total'], 0) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
            </div>
        </div>

        <!-- Monthly Sales -->
        <h3 class="chart-title" style="margin: 2rem 0 1rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            Monthly Sales (<?= date('Y') ?>)
        </h3>
        
        <!-- Desktop Table View -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                        <th>Avg. Order Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sales['monthly_sales'] as $month): ?>
                    <tr>
                        <td><?= $month['month_name'] ?></td>
                        <td><?= $month['order_count'] ?></td>
                        <td>SAR <?= number_format($month['revenue'], 2) ?></td>
                        <td>SAR <?= $month['order_count'] > 0 ? number_format($month['revenue'] / $month['order_count'], 2) : '0.00' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Card View -->
        <div class="mobile-cards">
            <?php foreach ($sales['monthly_sales'] as $month): ?>
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <div class="analytics-card-title"><?= $month['month_name'] ?></div>
                </div>
                <div class="analytics-card-details">
                    <div class="analytics-detail">
                        <div class="analytics-detail-label">Orders</div>
                        <div class="analytics-detail-value"><?= $month['order_count'] ?></div>
                    </div>
                    <div class="analytics-detail">
                        <div class="analytics-detail-label">Revenue</div>
                        <div class="analytics-detail-value">SAR <?= number_format($month['revenue'], 2) ?></div>
                    </div>
                    <div class="analytics-detail">
                        <div class="analytics-detail-label">Avg. Order Value</div>
                        <div class="analytics-detail-value">SAR <?= $month['order_count'] > 0 ? number_format($month['revenue'] / $month['order_count'], 2) : '0.00' ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Product Analytics -->
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #10b981;">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            Product Performance
        </h2>
    </div>
    
    <div class="section-content">
        <div class="chart-grid">
            <div class="chart-card">
                <h3 class="chart-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    Category Performance
                </h3>
                <!-- Desktop Table View -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Products</th>
                                <th>Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($products['category_performance'], 0, 5) as $category): ?>
                            <tr>
                                <td><?= htmlspecialchars($category['category']) ?></td>
                                <td><?= $category['product_count'] ?></td>
                                <td><?= $category['units_sold'] ?? 0 ?></td>
                                <td>SAR <?= number_format($category['revenue'] ?? 0, 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Card View -->
                <div class="mobile-cards">
                    <?php foreach (array_slice($products['category_performance'], 0, 5) as $category): ?>
                    <div class="analytics-card">
                        <div class="analytics-card-header">
                            <div class="analytics-card-title"><?= htmlspecialchars($category['category']) ?></div>
                        </div>
                        <div class="analytics-card-details">
                            <div class="analytics-detail">
                                <div class="analytics-detail-label">Products</div>
                                <div class="analytics-detail-value"><?= $category['product_count'] ?></div>
                            </div>
                            <div class="analytics-detail">
                                <div class="analytics-detail-label">Units Sold</div>
                                <div class="analytics-detail-value"><?= $category['units_sold'] ?? 0 ?></div>
                            </div>
                            <div class="analytics-detail">
                                <div class="analytics-detail-label">Revenue</div>
                                <div class="analytics-detail-value">SAR <?= number_format($category['revenue'] ?? 0, 2) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="chart-card">
                <h3 class="chart-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    Most Viewed Products
                </h3>
                    <div style="padding: 10px 0;">
                        <?php 
                        $viewCounts = !empty($products['most_viewed']) ? array_column($products['most_viewed'], 'views') : [];
                        $maxViews = !empty($viewCounts) ? max($viewCounts) : 1;
                        foreach (array_slice($products['most_viewed'], 0, 10) as $product): 
                            $percentage = $maxViews > 0 ? ($product['views'] / $maxViews) * 100 : 0;
                        ?>
                        <div class="metric">
                            <span><?= htmlspecialchars(substr($product['name'], 0, 40)) ?><?= strlen($product['name']) > 40 ? '...' : '' ?></span>
                            <span class="badge"><?= number_format($product['views']) ?> views</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Analytics -->
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #f59e0b;">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            Customer Analytics
        </h2>
    </div>
    
    <div class="section-content">
        <div class="stat-highlight">
            <div class="stat-number"><?= $customers['new_customers'] ?></div>
            <div class="stat-label">New Customers (Last 30 Days)</div>
        </div>

        <h3 class="chart-title" style="margin-bottom: 1rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
            Top Customers
        </h3>
        <!-- Desktop Table View -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Last Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers['top_customers'] as $customer): ?>
                    <tr>
                        <td><?= htmlspecialchars($customer['name']) ?></td>
                        <td><?= htmlspecialchars($customer['email']) ?></td>
                        <td><?= $customer['order_count'] ?></td>
                        <td>SAR <?= number_format($customer['total_spent'], 2) ?></td>
                        <td><?= date('M d, Y', strtotime($customer['last_order'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Card View -->
        <div class="mobile-cards">
            <?php foreach ($customers['top_customers'] as $customer): ?>
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <div class="analytics-card-title"><?= htmlspecialchars($customer['name']) ?></div>
                </div>
                <div class="analytics-card-metric">
                    <span class="metric-name">Email</span>
                    <span class="metric-value"><?= htmlspecialchars($customer['email']) ?></span>
                </div>
                <div class="analytics-card-metric">
                    <span class="metric-name">Orders</span>
                    <span class="metric-value"><?= $customer['order_count'] ?></span>
                </div>
                <div class="analytics-card-metric">
                    <span class="metric-name">Total Spent</span>
                    <span class="metric-value">SAR <?= number_format($customer['total_spent'], 2) ?></span>
                </div>
                <div class="analytics-card-metric">
                    <span class="metric-name">Last Order</span>
                    <span class="metric-value"><?= date('M d, Y', strtotime($customer['last_order'])) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

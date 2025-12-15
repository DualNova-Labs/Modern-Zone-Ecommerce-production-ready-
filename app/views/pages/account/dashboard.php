<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My Account' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .stat-icon.orders {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        .stat-icon.spent {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }

        .stat-icon.delivered {
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
        }

        .section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .section-link {
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .section-link:hover {
            text-decoration: underline;
        }

        .section-content {
            padding: 24px;
        }

        .nav-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            text-decoration: none;
            color: #1e293b;
            transition: all 0.2s;
        }

        .nav-link:hover {
            border-color: #6366f1;
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .nav-link-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .nav-link-text {
            font-weight: 600;
        }

        .nav-link-desc {
            font-size: 12px;
            color: #64748b;
        }

        .order-list {}

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-info {}

        .order-number {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .order-date {
            font-size: 13px;
            color: #64748b;
        }

        .order-right {
            text-align: right;
        }

        .order-amount {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .order-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-shipped {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .status-delivered {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-text {
            font-size: 16px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .nav-links {
                grid-template-columns: 1fr;
            }

            .order-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .order-right {
                text-align: left;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="<?= View::url('/') ?>" class="back-link">← Back to Home</a>

        <div class="page-header">
            <h1 class="page-title">Welcome back, <?= htmlspecialchars($user->name ?? 'User') ?>!</h1>
            <p class="page-subtitle">Manage your account, orders, and preferences</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon orders">📦</div>
                <div class="stat-value"><?= $stats['total_orders'] ?? 0 ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon spent">💰</div>
                <div class="stat-value">SAR <?= number_format($stats['total_spent'] ?? 0, 2) ?></div>
                <div class="stat-label">Total Spent</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending">⏳</div>
                <div class="stat-value"><?= $stats['pending_orders'] ?? 0 ?></div>
                <div class="stat-label">Pending Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon delivered">✅</div>
                <div class="stat-value"><?= $stats['delivered_orders'] ?? 0 ?></div>
                <div class="stat-label">Delivered</div>
            </div>
        </div>

        <!-- Quick Navigation -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Account Menu</h2>
            </div>
            <div class="section-content">
                <div class="nav-links">
                    <a href="<?= View::url('/account/orders') ?>" class="nav-link">
                        <div class="nav-link-icon">📋</div>
                        <div>
                            <div class="nav-link-text">My Orders</div>
                            <div class="nav-link-desc">View order history and tracking</div>
                        </div>
                    </a>
                    <a href="<?= View::url('/account/profile') ?>" class="nav-link">
                        <div class="nav-link-icon">👤</div>
                        <div>
                            <div class="nav-link-text">Profile Settings</div>
                            <div class="nav-link-desc">Update personal information</div>
                        </div>
                    </a>
                    <a href="<?= View::url('/account/addresses') ?>" class="nav-link">
                        <div class="nav-link-icon">📍</div>
                        <div>
                            <div class="nav-link-text">Addresses</div>
                            <div class="nav-link-desc">Manage shipping addresses</div>
                        </div>
                    </a>
                    <a href="<?= View::url('/account/change-password') ?>" class="nav-link">
                        <div class="nav-link-icon">🔒</div>
                        <div>
                            <div class="nav-link-text">Change Password</div>
                            <div class="nav-link-desc">Update your password</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Recent Orders</h2>
                <?php if (!empty($recent_orders)): ?>
                    <a href="<?= View::url('/account/orders') ?>" class="section-link">View All →</a>
                <?php endif; ?>
            </div>
            <div class="section-content">
                <?php if (!empty($recent_orders)): ?>
                    <div class="order-list">
                        <?php foreach ($recent_orders as $order): ?>
                            <div class="order-item">
                                <div class="order-info">
                                    <div class="order-number">Order
                                        #<?= htmlspecialchars($order['order_number'] ?? $order['id']) ?></div>
                                    <div class="order-date"><?= date('M d, Y', strtotime($order['created_at'])) ?></div>
                                </div>
                                <div class="order-right">
                                    <div class="order-amount">SAR <?= number_format($order['total'] ?? 0, 2) ?></div>
                                    <?php
                                    $status = $order['status'] ?? 'pending';
                                    $statusClass = 'status-' . $status;
                                    ?>
                                    <span class="order-status <?= $statusClass ?>"><?= ucfirst($status) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <div class="empty-text">No orders yet. Start shopping to see your orders here!</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
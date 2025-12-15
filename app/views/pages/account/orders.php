<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My Orders' ?></title>
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
            max-width: 1000px;
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

        .section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .order-list {}

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: background 0.2s;
        }

        .order-item:hover {
            background: #f8fafc;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-info {}

        .order-number {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
            font-size: 16px;
        }

        .order-date {
            font-size: 13px;
            color: #64748b;
        }

        .order-items-count {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        .order-right {
            text-align: right;
        }

        .order-amount {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
            font-size: 18px;
        }

        .order-status {
            display: inline-block;
            padding: 4px 12px;
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
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-text {
            font-size: 18px;
            margin-bottom: 24px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #6366f1;
            color: white;
        }

        .btn-primary:hover {
            background: #4f46e5;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            padding: 20px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
        }

        .pagination a:hover {
            border-color: #6366f1;
            color: #6366f1;
        }

        .pagination .active {
            background: #6366f1;
            color: white;
            border-color: #6366f1;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="<?= View::url('/account') ?>" class="back-link">← Back to Account</a>

        <div class="page-header">
            <h1 class="page-title">My Orders</h1>
        </div>

        <div class="section">
            <?php if (!empty($orders)): ?>
                <div class="order-list">
                    <?php foreach ($orders as $order): ?>
                        <a href="<?= View::url('/account/orders/' . ($order['order_number'] ?? $order['id'])) ?>"
                            class="order-item" style="text-decoration: none;">
                            <div class="order-info">
                                <div class="order-number">Order #<?= htmlspecialchars($order['order_number'] ?? $order['id']) ?>
                                </div>
                                <div class="order-date"><?= date('F d, Y \a\t h:i A', strtotime($order['created_at'])) ?></div>
                            </div>
                            <div class="order-right">
                                <div class="order-amount">SAR <?= number_format($order['total'] ?? 0, 2) ?></div>
                                <?php
                                $status = $order['status'] ?? 'pending';
                                $statusClass = 'status-' . $status;
                                ?>
                                <span
                                    class="order-status <?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <?php if ($i == $pagination['current_page']): ?>
                                <span class="active"><?= $i ?></span>
                            <?php else: ?>
                                <a href="<?= View::url('/account/orders?page=' . $i) ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <div class="empty-text">You haven't placed any orders yet</div>
                    <a href="<?= View::url('/products') ?>" class="btn btn-primary">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Order Details' ?></title>
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
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
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

        .order-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .order-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .order-meta {
            display: flex;
            gap: 24px;
            color: #64748b;
            font-size: 14px;
            flex-wrap: wrap;
        }

        .section {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            background: #f1f5f9;
            object-fit: cover;
        }

        .item-name {
            font-weight: 600;
            color: #1e293b;
        }

        .item-sku {
            font-size: 12px;
            color: #64748b;
        }

        .item-qty {
            color: #64748b;
            font-size: 14px;
        }

        .item-price {
            font-weight: 600;
            color: #1e293b;
            text-align: right;
        }

        .totals {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px solid #e2e8f0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .total-row.grand {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            margin-top: 8px;
        }

        .address-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
        }

        .address-block h4 {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .address-block p {
            color: #1e293b;
            line-height: 1.6;
        }

        .order-status {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
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
    </style>
</head>

<body>
    <div class="container">
        <a href="<?= View::url('/account/orders') ?>" class="back-link">← Back to Orders</a>

        <div class="order-header">
            <h1 class="order-title">Order #<?= htmlspecialchars($order->order_number ?? $order->id) ?></h1>
            <div class="order-meta">
                <span>📅 <?= date('F d, Y', strtotime($order->created_at)) ?></span>
                <span>
                    <?php
                    $status = $order->status ?? 'pending';
                    $statusClass = 'status-' . $status;
                    ?>
                    <span class="order-status <?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                </span>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Order Items</h2>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <div class="item-row">
                        <div class="item-info">
                            <img src="<?= !empty($item['image']) ? View::url('/' . $item['image']) : View::url('/public/assets/images/placeholder.png') ?>"
                                alt="<?= htmlspecialchars($item['product_name'] ?? 'Product') ?>" class="item-image">
                            <div>
                                <div class="item-name"><?= htmlspecialchars($item['product_name'] ?? 'Product') ?></div>
                                <div class="item-sku">SKU: <?= htmlspecialchars($item['sku'] ?? 'N/A') ?></div>
                                <div class="item-qty">Qty: <?= $item['quantity'] ?? 1 ?></div>
                            </div>
                        </div>
                        <div class="item-price">SAR <?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                    </div>
                <?php endforeach; ?>

                <div class="totals">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>SAR <?= number_format($order->subtotal ?? $order->total, 2) ?></span>
                    </div>
                    <div class="total-row">
                        <span>Shipping</span>
                        <span>SAR <?= number_format($order->shipping_cost ?? 0, 2) ?></span>
                    </div>
                    <?php if (($order->discount ?? 0) > 0): ?>
                        <div class="total-row">
                            <span>Discount</span>
                            <span>-SAR <?= number_format($order->discount, 2) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="total-row grand">
                        <span>Total</span>
                        <span>SAR <?= number_format($order->total ?? 0, 2) ?></span>
                    </div>
                </div>
            <?php else: ?>
                <p style="color: #64748b; text-align: center; padding: 20px;">No items found for this order.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2 class="section-title">Delivery Information</h2>
            <div class="address-grid">
                <div class="address-block">
                    <h4>Shipping Address</h4>
                    <p>
                        <?= htmlspecialchars($order->shipping_name ?? $user->name ?? '') ?><br>
                        <?= htmlspecialchars($order->shipping_address ?? '') ?><br>
                        <?= htmlspecialchars($order->shipping_city ?? '') ?><?= !empty($order->shipping_postal_code) ? ', ' . htmlspecialchars($order->shipping_postal_code) : '' ?><br>
                        <?= htmlspecialchars($order->shipping_country ?? '') ?>
                    </p>
                </div>
                <div class="address-block">
                    <h4>Contact</h4>
                    <p>
                        <?= htmlspecialchars($order->shipping_email ?? $user->email ?? '') ?><br>
                        <?= htmlspecialchars($order->shipping_phone ?? $user->phone ?? '') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
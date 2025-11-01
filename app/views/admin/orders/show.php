<?php
$pageTitle = 'Order Details';
$breadcrumb = 'Home / Orders / Details';
ob_start();
?>

<style>
    .section { background: white; padding: 28px; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; margin-bottom: 24px; }
        .order-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px; }
        .order-title h2 { font-size: 24px; color: #2c3e50; margin-bottom: 5px; }
        .order-meta { color: #7f8c8d; font-size: 14px; }
        .status-actions { text-align: right; }
        .badge { display: inline-block; padding: 6px 16px; border-radius: 12px; font-size: 13px; font-weight: 500; margin-bottom: 10px; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .info-card { background: #f8f9fa; padding: 15px; border-radius: 6px; }
        .info-card h3 { font-size: 14px; color: #7f8c8d; margin-bottom: 10px; text-transform: uppercase; }
        .info-card p { margin: 5px 0; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ecf0f1; }
        th { background: #f8f9fa; font-weight: 600; color: #2c3e50; }
        .summary { background: #f8f9fa; padding: 20px; border-radius: 6px; margin-top: 20px; }
        .summary-row { display: flex; justify-content: space-between; margin: 10px 0; }
        .summary-row.total { font-size: 18px; font-weight: bold; border-top: 2px solid #dee2e6; padding-top: 15px; margin-top: 15px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; margin: 5px; }
        .btn-primary { background: #3498db; color: white; }
        .btn-success { background: #27ae60; color: white; }
        .btn-warning { background: #f39c12; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        select { padding: 8px 12px; border: 2px solid #ecf0f1; border-radius: 6px; }
</style>

<div class="section">
            <div class="order-header">
                <div class="order-title">
                    <h2>Order #<?= htmlspecialchars($order->order_number) ?></h2>
                    <div class="order-meta">
                        Placed on <?= date('F d, Y \a\t h:i A', strtotime($order->created_at)) ?>
                    </div>
                </div>
                <div class="status-actions">
                    <?php
                    $statusBadge = [
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    ];
                    $paymentBadge = [
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger'
                    ];
                    ?>
                    <div>
                        <span class="badge badge-<?= $statusBadge[$order->status] ?? 'info' ?>">
                            Status: <?= ucfirst($order->status) ?>
                        </span>
                    </div>
                    <div>
                        <span class="badge badge-<?= $paymentBadge[$order->payment_status] ?? 'warning' ?>">
                            Payment: <?= ucfirst($order->payment_status) ?>
                        </span>
                    </div>
                    <div style="margin-top: 10px;">
                        <select id="orderStatus" onchange="updateOrderStatus()">
                            <option value="">Update Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div style="margin-top: 5px;">
                        <a href="<?= View::url('/admin/orders/' . $order->id . '/invoice') ?>" class="btn btn-primary" target="_blank">📄 Invoice</a>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Info -->
            <div class="grid-2">
                <div class="info-card">
                    <h3>👤 Customer Information</h3>
                    <p><strong><?= htmlspecialchars($customer->name ?? 'N/A') ?></strong></p>
                    <p>📧 <?= htmlspecialchars($customer->email ?? 'N/A') ?></p>
                    <p>📱 <?= htmlspecialchars($customer->phone ?? 'N/A') ?></p>
                    <?php if (!empty($customer->company)): ?>
                    <p>🏢 <?= htmlspecialchars($customer->company) ?></p>
                    <?php endif; ?>
                </div>

                <div class="info-card">
                    <h3>📦 Shipping Address</h3>
                    <p><strong><?= htmlspecialchars($order->shipping_name) ?></strong></p>
                    <p><?= htmlspecialchars($order->shipping_address) ?></p>
                    <p><?= htmlspecialchars($order->shipping_city) ?>, <?= htmlspecialchars($order->shipping_country) ?></p>
                    <?php if ($order->shipping_postal_code): ?>
                    <p><?= htmlspecialchars($order->shipping_postal_code) ?></p>
                    <?php endif; ?>
                    <p>📱 <?= htmlspecialchars($order->shipping_phone) ?></p>
                </div>
            </div>

            <!-- Order Items -->
            <h3 style="margin: 30px 0 15px; font-size: 18px;">📋 Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                        </td>
                        <td><?= htmlspecialchars($item['product_sku']) ?></td>
                        <td>SAR <?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>SAR <?= number_format($item['total'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Order Summary -->
            <div class="summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <strong>SAR <?= number_format($order->subtotal, 2) ?></strong>
                </div>
                <div class="summary-row">
                    <span>Tax (15%):</span>
                    <strong>SAR <?= number_format($order->tax_amount, 2) ?></strong>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <strong>SAR <?= number_format($order->shipping_amount, 2) ?></strong>
                </div>
                <?php if ($order->discount_amount > 0): ?>
                <div class="summary-row">
                    <span>Discount:</span>
                    <strong>- SAR <?= number_format($order->discount_amount, 2) ?></strong>
                </div>
                <?php endif; ?>
                <div class="summary-row total">
                    <span>Total:</span>
                    <strong>SAR <?= number_format($order->total_amount, 2) ?></strong>
                </div>
            </div>

            <!-- Payment Info -->
            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 6px;">
                <strong>Payment Method:</strong> <?= ucfirst($order->payment_method ?? 'N/A') ?>
                &nbsp;|&nbsp;
                <strong>Payment Status:</strong> 
                <span class="badge badge-<?= $paymentBadge[$order->payment_status] ?? 'warning' ?>">
                    <?= ucfirst($order->payment_status) ?>
                </span>
            </div>

            <?php if ($order->notes): ?>
            <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 6px;">
                <strong>Order Notes:</strong><br>
                <?= nl2br(htmlspecialchars($order->notes)) ?>
            </div>
        <?php endif; ?>
</div>

<script>
        function updateOrderStatus() {
            const status = document.getElementById('orderStatus').value;
            if (!status) return;

            if (!confirm('Are you sure you want to update the order status?')) {
                document.getElementById('orderStatus').value = '';
                return;
            }

            fetch('<?= View::url('/admin/orders/' . $order->id . '/status') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'csrf_token=<?= $csrf_token ?>&status=' + status
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.error);
                }
            });
        }
</script>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/admin/layouts/main.php';
?>

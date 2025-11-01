<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?= htmlspecialchars($order->order_number) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .invoice-header { text-align: center; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 3px solid #2c3e50; }
        .invoice-header h1 { color: #2c3e50; font-size: 32px; margin-bottom: 5px; }
        .invoice-header p { color: #7f8c8d; }
        .invoice-details { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
        .detail-box h3 { font-size: 14px; color: #7f8c8d; margin-bottom: 10px; text-transform: uppercase; }
        .detail-box p { margin: 5px 0; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ecf0f1; }
        th { background: #f8f9fa; font-weight: 600; color: #2c3e50; }
        .summary { margin-top: 30px; text-align: right; }
        .summary-row { display: flex; justify-content: flex-end; margin: 10px 0; }
        .summary-row span:first-child { width: 150px; text-align: right; margin-right: 20px; }
        .summary-row.total { font-size: 20px; font-weight: bold; border-top: 2px solid #2c3e50; padding-top: 15px; margin-top: 15px; }
        .footer { margin-top: 60px; text-align: center; color: #7f8c8d; font-size: 12px; border-top: 1px solid #ecf0f1; padding-top: 20px; }
        .print-btn { background: #3498db; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; margin-bottom: 20px; }
        .print-btn:hover { background: #2980b9; }
        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Print Invoice</button>

    <div class="invoice-header">
        <h1>🏪 Modern Zone Trading</h1>
        <p>Industrial Tools & Equipment</p>
        <p>Riyadh, Saudi Arabia | +966 XX XXX XXXX</p>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin-bottom: 10px;">INVOICE</h2>
        <p><strong>Invoice Number:</strong> <?= htmlspecialchars($order->order_number) ?></p>
        <p><strong>Date:</strong> <?= date('F d, Y', strtotime($order->created_at)) ?></p>
    </div>

    <div class="invoice-details">
        <div class="detail-box">
            <h3>Bill To:</h3>
            <p><strong><?= htmlspecialchars($customer->name ?? 'N/A') ?></strong></p>
            <p><?= htmlspecialchars($customer->email ?? '') ?></p>
            <p><?= htmlspecialchars($customer->phone ?? '') ?></p>
            <?php if (!empty($customer->company)): ?>
            <p><?= htmlspecialchars($customer->company) ?></p>
            <?php endif; ?>
        </div>

        <div class="detail-box">
            <h3>Ship To:</h3>
            <p><strong><?= htmlspecialchars($order->shipping_name) ?></strong></p>
            <p><?= htmlspecialchars($order->shipping_address) ?></p>
            <p><?= htmlspecialchars($order->shipping_city) ?>, <?= htmlspecialchars($order->shipping_country) ?></p>
            <?php if ($order->shipping_postal_code): ?>
            <p><?= htmlspecialchars($order->shipping_postal_code) ?></p>
            <?php endif; ?>
            <p><?= htmlspecialchars($order->shipping_phone) ?></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item Description</th>
                <th>SKU</th>
                <th style="text-align: right;">Unit Price</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= htmlspecialchars($item['product_sku']) ?></td>
                <td style="text-align: right;">SAR <?= number_format($item['price'], 2) ?></td>
                <td style="text-align: center;"><?= $item['quantity'] ?></td>
                <td style="text-align: right;">SAR <?= number_format($item['total'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span><strong>SAR <?= number_format($order->subtotal, 2) ?></strong></span>
        </div>
        <div class="summary-row">
            <span>Tax (15% VAT):</span>
            <span><strong>SAR <?= number_format($order->tax_amount, 2) ?></strong></span>
        </div>
        <div class="summary-row">
            <span>Shipping:</span>
            <span><strong>SAR <?= number_format($order->shipping_amount, 2) ?></strong></span>
        </div>
        <?php if ($order->discount_amount > 0): ?>
        <div class="summary-row">
            <span>Discount:</span>
            <span><strong>- SAR <?= number_format($order->discount_amount, 2) ?></strong></span>
        </div>
        <?php endif; ?>
        <div class="summary-row total">
            <span>Total Amount:</span>
            <span>SAR <?= number_format($order->total_amount, 2) ?></span>
        </div>
    </div>

    <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
        <p><strong>Payment Method:</strong> <?= ucfirst($order->payment_method ?? 'N/A') ?></p>
        <p><strong>Payment Status:</strong> <?= ucfirst($order->payment_status) ?></p>
        <p><strong>Order Status:</strong> <?= ucfirst($order->status) ?></p>
    </div>

    <?php if ($order->notes): ?>
    <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px;">
        <strong>Notes:</strong><br>
        <?= nl2br(htmlspecialchars($order->notes)) ?>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p><strong>Thank you for your business!</strong></p>
        <p>For questions about this invoice, please contact us at info@modernzonetrading.com</p>
        <p>VAT Registration: XXX-XXX-XXX | CR: XXXXXXX</p>
    </div>
</body>
</html>

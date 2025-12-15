<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My Addresses' ?></title>
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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
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

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #6366f1;
            color: white;
        }

        .btn-primary:hover {
            background: #4f46e5;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .address-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .address-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .address-card.default {
            border: 2px solid #6366f1;
        }

        .default-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #6366f1;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .address-type {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .address-name {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .address-details {
            color: #475569;
            line-height: 1.6;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .address-actions {
            display: flex;
            gap: 12px;
        }

        .address-action {
            color: #6366f1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .address-action:hover {
            text-decoration: underline;
        }

        .address-action.delete {
            color: #ef4444;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-text {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 24px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="<?= View::url('/account') ?>" class="back-link">← Back to Account</a>

        <div class="page-header">
            <h1 class="page-title">My Addresses</h1>
        </div>

        <?php
        // Get user address from profile
        $hasAddress = !empty($user->address) || !empty($user->city) || !empty($user->country);
        ?>

        <?php if ($hasAddress): ?>
            <div class="address-grid">
                <div class="address-card default">
                    <span class="default-badge">Default</span>
                    <div class="address-type">Shipping / Billing</div>
                    <div class="address-name"><?= htmlspecialchars($user->name ?? '') ?></div>
                    <div class="address-details">
                        <?= htmlspecialchars($user->address ?? '') ?><br>
                        <?= htmlspecialchars($user->city ?? '') ?>    <?= !empty($user->postal_code) ? ', ' . htmlspecialchars($user->postal_code) : '' ?><br>
                        <?= htmlspecialchars($user->country ?? '') ?><br>
                        <?php if (!empty($user->phone)): ?>
                            📞 <?= htmlspecialchars($user->phone) ?>
                        <?php endif; ?>
                    </div>
                    <div class="address-actions">
                        <a href="<?= View::url('/account/profile') ?>" class="address-action">Edit</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📍</div>
                <div class="empty-text">You haven't added any addresses yet</div>
                <a href="<?= View::url('/account/profile') ?>" class="btn btn-primary">Add Address in Profile</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
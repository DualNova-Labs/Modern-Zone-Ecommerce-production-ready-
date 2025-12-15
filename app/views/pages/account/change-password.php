<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Change Password' ?></title>
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
            max-width: 600px;
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

        .section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .section-desc {
            color: #64748b;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #475569;
            font-weight: 500;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
        }

        .btn-primary {
            background: #6366f1;
            color: white;
        }

        .btn-primary:hover {
            background: #4f46e5;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .password-requirements {
            margin-top: 8px;
            font-size: 12px;
            color: #64748b;
        }

        .password-requirements li {
            margin-left: 20px;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="<?= View::url('/account') ?>" class="back-link">← Back to Account</a>

        <div class="section">
            <h1 class="section-title">Change Password</h1>
            <p class="section-desc">Update your account password. Make sure to use a strong password.</p>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($errors['general']) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= View::url('/account/update-password') ?>">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?? '' ?>">

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                    <?php if (!empty($errors['current_password'])): ?>
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">
                            <?= htmlspecialchars($errors['current_password']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <?php if (!empty($errors['new_password'])): ?>
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">
                            <?= htmlspecialchars($errors['new_password']) ?></div>
                    <?php endif; ?>
                    <ul class="password-requirements">
                        <li>At least 8 characters</li>
                        <li>At least one uppercase letter</li>
                        <li>At least one lowercase letter</li>
                        <li>At least one number</li>
                    </ul>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <?php if (!empty($errors['confirm_password'])): ?>
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">
                            <?= htmlspecialchars($errors['confirm_password']) ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
</body>

</html>
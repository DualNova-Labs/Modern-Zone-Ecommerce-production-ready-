<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - Modern Zone Trading</title>
    <link rel="stylesheet" href="<?= View::url('/public/assets/css/admin.css') ?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .content-wrapper {
            flex: 1;
            padding: 2rem;
            max-width: 100%;
            overflow-x: hidden;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding-top: 70px; /* Account for fixed header */
            }
            
            .content-wrapper {
                padding: 0.5rem;
                padding-top: 0.5rem; /* Reduced since main-content has padding-top */
            }
        }
        
        @media (max-width: 480px) {
            .content-wrapper {
                padding: 0.25rem;
                padding-top: 0.25rem;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    <?= $additionalStyles ?? '' ?>
</head>
<body>
    <?php require_once APP_PATH . '/views/admin/layouts/sidebar.php'; ?>
    <?php require_once APP_PATH . '/views/admin/layouts/header.php'; ?>

    <div class="main-content">
        <div class="content-wrapper">
            <?= $content ?? '' ?>
        </div>
    </div>

    <?= $additionalScripts ?? '' ?>
</body>
</html>

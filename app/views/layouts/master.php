<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Primary Meta Tags -->
    <title><?= $title ?? 'Modern Zone Trading - Leading Industrial Tools Supplier in Saudi Arabia' ?></title>
    <meta name="title" content="<?= $title ?? 'Modern Zone Trading - Leading Industrial Tools Supplier in Saudi Arabia' ?>">
    <meta name="description" content="<?= $description ?? 'Modern Zone Trading is a leading industrial tools supplier in Saudi Arabia. We provide cutting tools, CNC machine holders, measuring tools, power tools, and comprehensive industrial solutions.' ?>">
    <meta name="keywords" content="industrial tools, cutting tools, CNC tools, machine holders, measuring tools, power tools, Saudi Arabia, Jeddah, Dormer, Sandvik Coromant, Seco, Pramet, Kyocera, YG-1, tool suppliers">
    <meta name="author" content="Modern Zone Trading">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:title" content="<?= $title ?? 'Modern Zone Trading - Leading Industrial Tools Supplier in Saudi Arabia' ?>">
    <meta property="og:description" content="<?= $description ?? 'Modern Zone Trading is a leading industrial tools supplier in Saudi Arabia. We provide cutting tools, CNC machine holders, measuring tools, and comprehensive industrial solutions.' ?>">
    <meta property="og:image" content="<?= View::asset('images/logo.png') ?>">
    <meta property="og:site_name" content="Modern Zone Trading">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
    <meta property="twitter:title" content="<?= $title ?? 'Modern Zone Trading - Leading Industrial Tools Supplier in Saudi Arabia' ?>">
    <meta property="twitter:description" content="<?= $description ?? 'Modern Zone Trading is a leading industrial tools supplier in Saudi Arabia. We provide cutting tools, CNC machine holders, measuring tools, and comprehensive industrial solutions.' ?>">
    <meta property="twitter:image" content="<?= View::asset('images/logo.png') ?>">
    
    <!-- Geo Tags -->
    <meta name="geo.region" content="SA-02">
    <meta name="geo.placename" content="Jeddah">
    <meta name="geo.position" content="21.543333;39.172779">
    <meta name="ICBM" content="21.543333, 39.172779">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= View::asset('images/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= View::asset('images/logo.png') ?>">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?= View::asset('css/main.css') ?>?v=<?= time() ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php View::component('header'); ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer -->
    <?php View::component('footer'); ?>
    
    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- Scripts -->
    <script src="<?= View::asset('js/main.js') ?>"></script>
</body>
</html>

<?php
ob_start();
?>

<section class="error-page">
    <div class="container">
        <div class="error-content">
            <div class="error-code">404</div>
            <h1 class="error-title">Page Not Found</h1>
            <p class="error-description">
                Sorry, the page you're looking for doesn't exist or has been moved.
            </p>
            <div class="error-actions">
                <a href="<?= View::url('') ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-home"></i>
                    Go to Homepage
                </a>
                <a href="<?= View::url('products') ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-shopping-bag"></i>
                    Browse Products
                </a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

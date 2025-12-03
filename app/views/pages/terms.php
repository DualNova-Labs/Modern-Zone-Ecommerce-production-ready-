<?php
ob_start();
?>

<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="<?= View::url('/') ?>">Home</a></li>
            <li>Terms & Conditions</li>
        </ul>
    </div>
</section>

<section class="contact-page" style="padding: 3rem 0;">
    <div class="container">
        <div class="section-header text-center">
            <h1 class="section-title">Terms & Conditions</h1>
            <p class="section-subtitle">Last updated: October 2025</p>
        </div>
        
        <div style="max-width: 800px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">1. Acceptance of Terms</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                By accessing and using APTools website, you accept and agree to be bound by these Terms and Conditions. If you do not agree to these terms, please do not use our website.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">2. Products and Services</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                We strive to display our products as accurately as possible. However, we do not guarantee that product descriptions, colors, or other content is accurate, complete, or error-free.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">3. Pricing and Payment</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                All prices are subject to change without notice. We reserve the right to refuse or cancel any order for any reason. Payment must be made in full before shipment.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">4. Shipping and Delivery</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                We aim to deliver products within the timeframe specified. However, delays may occur due to circumstances beyond our control. Risk of loss passes to you upon delivery.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">5. Returns and Refunds</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                Products may be returned within 30 days of delivery in their original condition. Refunds will be processed within 7-10 business days of receiving the returned item.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">6. Warranty</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                All products come with manufacturer warranties. Please refer to the product documentation for specific warranty terms. We facilitate warranty claims but are not responsible for manufacturer warranty policies.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">7. Limitation of Liability</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                APTools shall not be liable for any indirect, incidental, special, or consequential damages arising from the use of our products or services.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">8. Contact Information</h2>
            <p style="margin-bottom: 0; line-height: 1.8; color: var(--gray-700);">
                For questions about these Terms & Conditions, please contact us at info@aptools.com or +971 4 123 4567.
            </p>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

<?php
ob_start();
?>

<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="/host/mod/">Home</a></li>
            <li>Privacy Policy</li>
        </ul>
    </div>
</section>

<section class="contact-page" style="padding: 3rem 0;">
    <div class="container">
        <div class="section-header text-center">
            <h1 class="section-title">Privacy Policy</h1>
            <p class="section-subtitle">Last updated: October 2025</p>
        </div>
        
        <div style="max-width: 800px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">1. Information We Collect</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                We collect information that you provide directly to us, including name, email address, phone number, and shipping address when you create an account or place an order.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">2. How We Use Your Information</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                We use the information we collect to process your orders, communicate with you about your orders, send you marketing communications (with your consent), and improve our services.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">3. Information Sharing</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                We do not sell your personal information. We may share your information with service providers who help us operate our business, and with law enforcement when required by law.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">4. Data Security</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">5. Your Rights</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8; color: var(--gray-700);">
                You have the right to access, correct, or delete your personal information. You may also object to or restrict certain processing of your information. Contact us to exercise these rights.
            </p>
            
            <h2 style="color: var(--secondary-color); margin-bottom: 1rem;">6. Contact Us</h2>
            <p style="margin-bottom: 0; line-height: 1.8; color: var(--gray-700);">
                If you have any questions about this Privacy Policy, please contact us at info@aptools.com or +971 4 123 4567.
            </p>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

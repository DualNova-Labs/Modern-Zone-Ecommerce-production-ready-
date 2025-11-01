<?php
ob_start();
?>

<!-- Breadcrumb -->
<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="/host/mod/">Home</a></li>
            <li>Support</li>
        </ul>
    </div>
</section>

<!-- Support Page -->
<section class="support-page">
    <div class="container">
        <div class="section-header text-center">
            <h1 class="section-title">How Can We Help You?</h1>
            <p class="section-subtitle">Find answers to common questions or contact our support team</p>
        </div>
        
        <!-- Support Search -->
        <div class="support-search">
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input 
                    type="text" 
                    class="search-input" 
                    placeholder="Search for help articles, FAQs, or topics..."
                    id="supportSearch"
                >
            </div>
        </div>
        
        <!-- Support Categories -->
        <div class="support-categories">
            <div class="category-grid">
                <div class="support-category-card">
                    <div class="category-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3>Orders & Shipping</h3>
                    <p>Track orders, shipping info, and delivery</p>
                    <a href="#" class="category-link">View Articles <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="support-category-card">
                    <div class="category-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h3>Returns & Refunds</h3>
                    <p>Return policy, refund process, exchanges</p>
                    <a href="#" class="category-link">View Articles <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="support-category-card">
                    <div class="category-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Product Support</h3>
                    <p>Product manuals, specifications, guides</p>
                    <a href="#" class="category-link">View Articles <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="support-category-card">
                    <div class="category-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Warranty & Service</h3>
                    <p>Warranty information and service centers</p>
                    <a href="#" class="category-link">View Articles <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="faq-section">
            <h2 class="section-title text-center">Frequently Asked Questions</h2>
            
            <div class="faq-container">
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="faq-item">
                        <button class="faq-question" data-faq="<?= $index ?>">
                            <span><?= htmlspecialchars($faq['question']) ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p><?= htmlspecialchars($faq['answer']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Contact Support -->
        <div class="contact-support-section">
            <div class="contact-support-card">
                <h2>Still Need Help?</h2>
                <p>Can't find what you're looking for? Our support team is ready to assist you.</p>
                
                <div class="support-actions">
                    <a href="/host/mod/contact" class="btn btn-primary btn-lg">
                        <i class="fas fa-envelope"></i>
                        Contact Support
                    </a>
                    <a href="tel:+97141234567" class="btn btn-secondary btn-lg">
                        <i class="fas fa-phone"></i>
                        Call Us
                    </a>
                </div>
                
                <div class="support-hours">
                    <i class="fas fa-clock"></i>
                    <span>Support available: Sunday - Thursday, 9:00 AM - 6:00 PM (GST)</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

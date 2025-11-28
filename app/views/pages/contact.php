<?php
$config = require APP_PATH . '/config/config.php';
ob_start();
?>

<!-- Breadcrumb -->
<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="<?= View::url('') ?>">Home</a></li>
            <li>Contact Us</li>
        </ul>
    </div>
</section>

<!-- Contact Page -->
<section class="contact-page-modern">
    <div class="container">
        <div class="section-header text-center" style="margin-bottom: 4rem;">
            <h1 class="section-title">Contact <span style="color: #e74c3c;">Modern Zone Trading</span></h1>
            <p class="section-subtitle">We're here to help and answer any questions you might have</p>
        </div>
        
        <!-- Quick Contact Info Cards -->
        <div class="quick-contact-cards">
            <div class="quick-contact-card">
                <div class="quick-contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h4>Call Us</h4>
                <p>+966 50 061 5631 / +966 590717769</p>
            </div>
            <div class="quick-contact-card">
                <div class="quick-contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Email Us</h4>
                <p>info@modernzonetrading.com</p>
            </div>
            <div class="quick-contact-card">
                <div class="quick-contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Visit Us</h4>
                <p>Riyadh & Dammam, KSA</p>
            </div>
        </div>
        
        <div class="contact-main-grid">
            <!-- Contact Form -->
            <div class="contact-form-section">
                <div class="form-card-modern">
                    <div class="form-header">
                        <h2>Send Us a Message</h2>
                        <p>Fill out the form below and we'll get back to you as soon as possible</p>
                    </div>
                    
                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            Thank you for contacting us! We'll get back to you soon.
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <ul class="error-list">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form class="contact-form" method="POST" action="<?= View::url('contact') ?>">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name *</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input" 
                                placeholder="Enter your name"
                                value="<?= isset($old['name']) ? htmlspecialchars($old['name']) : '' ?>"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input" 
                                placeholder="Enter your email"
                                value="<?= isset($old['email']) ? htmlspecialchars($old['email']) : '' ?>"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="subject" class="form-label">Subject *</label>
                            <select id="subject" name="subject" class="form-select" required>
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="product">Product Information</option>
                                <option value="order">Order Status</option>
                                <option value="support">Technical Support</option>
                                <option value="partnership">Partnership</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="form-label">Message *</label>
                            <textarea 
                                id="message" 
                                name="message" 
                                class="form-textarea" 
                                rows="6" 
                                placeholder="Type your message here..."
                                required
                            ><?= isset($old['message']) ? htmlspecialchars($old['message']) : '' ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="contact-info-section">
                <div class="info-card-modern">
                    <div class="info-header">
                        <h2>Get In Touch</h2>
                        <p>Reach out to us through any of these channels</p>
                    </div>
                    
                    <div class="contact-items-modern">
                        <h4 style="color: #e74c3c; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">RIYADH BRANCH</h4>
                        <div class="contact-item-modern">
                            <div class="contact-icon-modern">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details-modern">
                                <h4>Address</h4>
                                <p>Building #12, Al Kadid Street, Al Rawdah Dt., Riyadh</p>
                            </div>
                        </div>
                        
                        <div class="contact-item-modern">
                            <div class="contact-icon-modern">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details-modern">
                                <h4>Phone Number</h4>
                                <p><a href="tel:+966500615631">+966 50 061 5631</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-item-modern">
                            <div class="contact-icon-modern">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details-modern">
                                <h4>Email Address</h4>
                                <p><a href="mailto:riyadh.br@modernzonetrading.com">riyadh.br@modernzonetrading.com</a></p>
                            </div>
                        </div>

                        <h4 style="color: #e74c3c; margin-top: 30px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">DAMMAM BRANCH</h4>
                        <div class="contact-item-modern">
                            <div class="contact-icon-modern">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details-modern">
                                <h4>Address</h4>
                                <p>Building #3731, 6th Street, Al Amamrah Dt. Dammam</p>
                            </div>
                        </div>
                        
                        <div class="contact-item-modern">
                            <div class="contact-icon-modern">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details-modern">
                                <h4>Phone Number</h4>
                                <p><a href="tel:+966590717769">+966 590717769</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-item-modern">
                            <div class="contact-icon-modern">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details-modern">
                                <h4>Email Address</h4>
                                <p><a href="mailto:info@modernzonetrading.com">info@modernzonetrading.com</a></p>
                            </div>
                        </div>

                        <h4 style="color: #e74c3c; margin-top: 30px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">WORKING HOURS</h4>
                        <div class="contact-item-modern">
                            <div class="contact-icon-modern">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-details-modern">
                                <h4>Office Hours</h4>
                                <p><?= $config['contact']['working_hours'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-section-modern">
                        <h4>Connect With Us</h4>
                        <div class="social-links-modern">
                            <a href="<?= $config['social']['facebook'] ?>" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="<?= $config['social']['twitter'] ?>" class="social-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="<?= $config['social']['instagram'] ?>" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="<?= $config['social']['linkedin'] ?>" class="social-link">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="map-section">
            <h2 class="text-center">Find Us on the Map</h2>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d462560.6828013629!2d54.94755393152995!3d25.076280472831792!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f43496ad9c645%3A0xbde66e5084295162!2sDubai%20-%20United%20Arab%20Emirates!5e0!3m2!1sen!2s!4v1234567890123!5m2!1sen!2s"
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

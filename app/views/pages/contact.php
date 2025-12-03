<?php
$config = require APP_PATH . '/config/config.php';
ob_start();
?>

<style>
.branch-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.branch-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    border-color: #e74c3c;
}

.branch-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.branch-header i {
    font-size: 2rem;
    color: #e74c3c;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}

.branch-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    letter-spacing: 0.5px;
}

.branch-details {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.75rem;
    border-radius: 8px;
    transition: background 0.2s;
}

.detail-item:hover {
    background: #f8fafc;
}

.detail-item i {
    font-size: 1.25rem;
    color: #64748b;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    border-radius: 8px;
    flex-shrink: 0;
    margin-top: 2px;
}

.detail-item p {
    margin: 0;
    color: #475569;
    font-size: 1rem;
    line-height: 1.6;
}

.detail-item a {
    color: #e74c3c;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.detail-item a:hover {
    color: #c0392b;
    text-decoration: underline;
}

/* Horizontal Get In Touch Section */
.contact-info-section-top {
    margin-bottom: 3rem;
}

.info-card-modern-horizontal {
    background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
    padding: 3rem;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    color: white;
    position: relative;
    overflow: hidden;
}

.info-card-modern-horizontal::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 50%;
    pointer-events: none;
}

.info-header-horizontal {
    position: relative;
    z-index: 1;
    text-align: center;
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.info-header-horizontal h2 {
    font-size: 2rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
}

.info-header-horizontal p {
    font-size: 1.125rem;
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
}

.branches-grid-horizontal {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    position: relative;
    z-index: 1;
}

.social-section-horizontal {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid rgba(255, 255, 255, 0.1);
}

.social-section-horizontal h4 {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin-bottom: 1rem;
    text-align: center;
}

.social-links-modern {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.social-links-modern .social-link {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.125rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    text-decoration: none;
}

.social-links-modern .social-link:hover {
    background: #e74c3c;
    border-color: white;
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
}

.contact-form-section-full {
    margin-bottom: 3rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-bottom: 0;
}

@media (max-width: 1024px) {
    .branches-grid-horizontal {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .branch-card {
        padding: 1.5rem;
    }
    
    .branch-header h3 {
        font-size: 1.25rem;
    }
    
    .branch-header i {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .info-header-horizontal h2 {
        font-size: 1.5rem;
    }
    
    .info-card-modern-horizontal {
        padding: 2rem;
    }
}
</style>

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
                <h4>Call Us (Riyadh)</h4>
                <p>+966 50 061 5631</p>
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
                <p>Riyadh & Dammam, Saudi Arabia</p>
            </div>
        </div>
        
        <!-- Contact Information - Get In Touch Section -->
        <div class="contact-info-section-top">
            <div class="info-card-modern-horizontal">
                <div class="info-header-horizontal">
                    <h2>Get In Touch</h2>
                    <p>Reach out to us through any of these channels</p>
                </div>
                
                <div class="branches-grid-horizontal">
                    <!-- Riyadh Branch -->
                    <div class="branch-card">
                        <div class="branch-header">
                            <i class="fas fa-map-marker-alt"></i>
                            <h3>RIYADH BRANCH</h3>
                        </div>
                        <div class="branch-details">
                            <div class="detail-item">
                                <i class="fas fa-building"></i>
                                <p>Building #12, Al Kadid Street<br>Al Rawdah Dt., Riyadh</p>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-phone"></i>
                                <p><a href="tel:+966500615631">+966 50 061 5631</a></p>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <p><a href="mailto:riyadh.br@modernzonetrading.com">riyadh.br@modernzonetrading.com</a></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dammam Branch -->
                    <div class="branch-card">
                        <div class="branch-header">
                            <i class="fas fa-map-marker-alt"></i>
                            <h3>DAMMAM BRANCH</h3>
                        </div>
                        <div class="branch-details">
                            <div class="detail-item">
                                <i class="fas fa-building"></i>
                                <p>Building #3731, 6th Street<br>Al Amamrah Dt., Dammam</p>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-phone"></i>
                                <p><a href="tel:+966590717769">+966 590717769</a></p>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <p><a href="mailto:info@modernzonetrading.com">info@modernzonetrading.com</a></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Working Hours & Social -->
                    <div class="branch-card">
                        <div class="branch-header">
                            <i class="fas fa-clock"></i>
                            <h3>WORKING HOURS</h3>
                        </div>
                        <div class="branch-details">
                            <div class="detail-item">
                                <i class="fas fa-calendar-alt"></i>
                                <p><?= $config['contact']['working_hours'] ?></p>
                            </div>
                        </div>
                        
                        <div class="social-section-horizontal">
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
        </div>
        
        <!-- Contact Form Section -->
        <div class="contact-form-section-full">
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
                    <div class="form-row">
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

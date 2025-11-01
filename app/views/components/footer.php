<?php
$config = require APP_PATH . '/config/config.php';
?>
<footer class="footer-modern">
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid-modern">
                <!-- About Column -->
                <div class="footer-col-modern">
                    <div class="footer-logo-section">
                        <h3 class="footer-brand">Modern Zone <span>Trading</span></h3>
                    </div>
                    <p class="footer-description">
                        Leading industrial tools supplier in Saudi Arabia. We provide cutting tools, CNC machine holders, measuring tools, and comprehensive industrial solutions.
                    </p>
                    <div class="footer-social-links">
                        <a href="<?= $config['social']['facebook'] ?>" class="footer-social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?= $config['social']['twitter'] ?>" class="footer-social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="<?= $config['social']['instagram'] ?>" class="footer-social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?= $config['social']['linkedin'] ?>" class="footer-social-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links Column -->
                <div class="footer-col-modern">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-menu">
                        <li><a href="<?= View::url('') ?>">Home</a></li>
                        <li><a href="<?= View::url('products') ?>">Products</a></li>
                        <li><a href="<?= View::url('about') ?>">About Us</a></li>
                        <li><a href="<?= View::url('support') ?>">Support</a></li>
                        <li><a href="<?= View::url('contact') ?>">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Products Column -->
                <div class="footer-col-modern">
                    <h3 class="footer-heading">Our Products</h3>
                    <ul class="footer-menu">
                        <li><a href="<?= View::url('products?category=cutting-tools') ?>">Cutting Tools</a></li>
                        <li><a href="<?= View::url('products?category=cnc-holders') ?>">CNC Tool Holders</a></li>
                        <li><a href="<?= View::url('products?category=measuring-tools') ?>">Measuring Tools</a></li>
                        <li><a href="<?= View::url('products?category=power-tools') ?>">Power Tools</a></li>
                        <li><a href="<?= View::url('products?category=accessories') ?>">Accessories</a></li>
                    </ul>
                </div>
                
                <!-- Contact Column -->
                <div class="footer-col-modern">
                    <h3 class="footer-heading">Contact Info</h3>
                    <ul class="footer-contact-list">
                        <li class="footer-contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jeddah, Saudi Arabia</span>
                        </li>
                        <li class="footer-contact-item">
                            <i class="fas fa-phone"></i>
                            <a href="tel:<?= str_replace(' ', '', $config['contact']['phone']) ?>"><?= $config['contact']['phone'] ?></a>
                        </li>
                        <li class="footer-contact-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:<?= $config['contact']['email'] ?>"><?= $config['contact']['email'] ?></a>
                        </li>
                        <li class="footer-contact-item">
                            <i class="fas fa-clock"></i>
                            <span><?= $config['contact']['working_hours'] ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom -->
    <div class="footer-bottom-modern">
        <div class="container">
            <div class="footer-bottom-grid">
                <p class="footer-copyright">
                    &copy; <?= date('Y') ?> <strong>Modern Zone Trading</strong>. All rights reserved.
                </p>
                <div class="footer-legal">
                    <a href="<?= View::url('privacy') ?>">Privacy Policy</a>
                    <span class="separator">|</span>
                    <a href="<?= View::url('terms') ?>">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </div>
</footer>

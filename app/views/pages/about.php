<?php
ob_start();
?>

<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="<?= View::url('') ?>">Home</a></li>
            <li>About Us</li>
        </ul>
    </div>
</section>

<!-- About Page -->
<section class="about-page">
    <div class="container">
        <!-- Page Header -->
        <div class="section-header text-center" style="margin-bottom: 4rem;">
            <h1 class="section-title">About <span style="color: #e74c3c;">Modern Zone Trading</span></h1>
            <p class="section-subtitle">Leading industrial tools supplier in Saudi Arabia</p>
        </div>
        
        <!-- Main Content Grid -->
        <div class="about-content-grid">
            <!-- Image Section -->
            <div class="about-image-wrapper">
                <img src="<?= View::asset('images/why-choose-us.jpeg') ?>" alt="Modern Zone Trading Industrial Tools" class="about-main-image">
            </div>
            
            <!-- Content Section -->
            <div class="about-text-content">
                <h2 class="about-section-title">Who We Are</h2>
                <p class="about-paragraph">
                    We are one of the leading industrial tools supplier in Saudi Arabia engaged in the sales and distribution of various brands of cutting tools, CNC & conventional machine tool holders, measuring tools, tool & die making standard parts, hack saw and band saw blades, cutting and grinding discs, hand gloves, machine shop accessories, magnetic drilling machine, core cutters, hole saws, brazed tools, gear hobs, power tools, general consumables etc.
                </p>
                
                <h3 class="about-subsection-title">Our Mission</h3>
                <p class="about-paragraph">
                    We deliver a 'one stop solution' for a wide range of business outsourcing services as well as secure and critical environments.
                </p>
                
                <h3 class="about-subsection-title">Our Commitment</h3>
                <p class="about-paragraph">
                    We provide our Clients the highest level of quality services at fair and market competitive prices.
                </p>
                
                <p class="about-paragraph">
                    To ensure the longevity of our company through repeat and referral business achieved through our Client's satisfaction.
                </p>
            </div>
        </div>
        
        <!-- Values Banner -->
        <div class="values-banner">
            <div class="values-content">
                <i class="fas fa-quote-left values-quote-icon"></i>
                <p class="values-text">
                    We believe <strong>Experience, Competence, Quality, Innovation, Sustainability</strong> – these are the keys to success.
                </p>
            </div>
        </div>
        
        <!-- Core Values Section -->
        <div class="section-header text-center" style="margin: 4rem 0 2rem;">
            <h2 class="section-title" style="font-size: 2rem;">Our Core Values</h2>
            <p class="section-subtitle">The principles that drive our success</p>
        </div>
        
        <div class="values-grid">
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3>Experience</h3>
                <p>Leading supplier in Saudi Arabia</p>
            </div>
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>Competence</h3>
                <p>Expert knowledge in industrial tools</p>
            </div>
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h3>Quality</h3>
                <p>Highest level of service standards</p>
            </div>
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3>Innovation</h3>
                <p>Latest tools and solutions</p>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

<?php
ob_start();
?>

<section class="auth-page">
    <div class="container">
        <div class="auth-grid">
            <!-- Auth Image -->
            <div class="auth-image">
                <div class="auth-image-content">
                    <h2>Welcome Back!</h2>
                    <p>Access your account to manage orders, track shipments, and enjoy exclusive benefits.</p>
                    <img src="<?= View::asset('images/login-illustration.svg') ?>" alt="Login">
                </div>
            </div>
            
            <!-- Auth Form -->
            <div class="auth-form-container">
                <div class="auth-form-wrapper">
                    <div class="auth-header">
                        <h1>Login</h1>
                        <p>Enter your credentials to access your account</p>
                    </div>
                    
                    <?php if (isset($error) && $error): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form class="auth-form" method="POST" action="<?= View::url('login') ?>">
                        <?= View::csrfField() ?>
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input" 
                                placeholder="Enter your email"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i>
                                Password
                            </label>
                            <div class="password-input-wrapper">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input" 
                                    placeholder="Enter your password"
                                    required
                                >
                                <button type="button" class="password-toggle" id="passwordToggle">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-options">
                            <label class="checkbox-label">
                                <input type="checkbox" name="remember">
                                <span>Remember me</span>
                            </label>
                            <a href="<?= View::url('forgot-password') ?>" class="forgot-link">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-sign-in-alt"></i>
                            Login
                        </button>
                        
                        <div class="auth-divider">
                            <span>or</span>
                        </div>
                        
                        <div class="social-login">
                            <button type="button" class="btn btn-social btn-google">
                                <i class="fab fa-google"></i>
                                Continue with Google
                            </button>
                            <button type="button" class="btn btn-social btn-facebook">
                                <i class="fab fa-facebook-f"></i>
                                Continue with Facebook
                            </button>
                        </div>
                        
                        <p class="auth-footer-text">
                            Don't have an account? 
                            <a href="<?= View::url('register') ?>" class="auth-link">Register here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/master.php';
?>

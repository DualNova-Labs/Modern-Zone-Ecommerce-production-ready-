<?php
ob_start();
?>

<section class="auth-page">
    <div class="container">
        <div class="auth-grid">
            <!-- Auth Image -->
            <div class="auth-image">
                <div class="auth-image-content">
                    <h2>Join APTools Today!</h2>
                    <p>Create an account to enjoy exclusive benefits, track orders, and access special offers.</p>
                    <img src="<?= View::asset('images/register-illustration.svg') ?>" alt="Register">
                </div>
            </div>
            
            <!-- Auth Form -->
            <div class="auth-form-container">
                <div class="auth-form-wrapper">
                    <div class="auth-header">
                        <h1>Create Account</h1>
                        <p>Fill in the details below to get started</p>
                    </div>
                    
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
                    
                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form class="auth-form" method="POST" action="<?= View::url('register') ?>" id="registerForm">
                        <?= View::csrfField() ?>
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i>
                                Full Name
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                placeholder="Enter your full name"
                                value="<?= isset($old['name']) ? htmlspecialchars($old['name']) : '' ?>"
                                required
                            >
                            <?php if (isset($errors['name'])): ?>
                                <span class="input-error"><?= htmlspecialchars($errors['name']) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                placeholder="Enter your email"
                                value="<?= isset($old['email']) ? htmlspecialchars($old['email']) : '' ?>"
                                required
                            >
                            <?php if (isset($errors['email'])): ?>
                                <span class="input-error"><?= htmlspecialchars($errors['email']) ?></span>
                            <?php endif; ?>
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
                                    class="form-input <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                    placeholder="Create a password"
                                    required
                                >
                                <button type="button" class="password-toggle" id="passwordToggle">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <small class="input-hint">Must be at least 8 characters</small>
                            <?php if (isset($errors['password'])): ?>
                                <span class="input-error"><?= htmlspecialchars($errors['password']) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirm" class="form-label">
                                <i class="fas fa-lock"></i>
                                Confirm Password
                            </label>
                            <div class="password-input-wrapper">
                                <input 
                                    type="password" 
                                    id="password_confirm" 
                                    name="password_confirm" 
                                    class="form-input <?= isset($errors['password_confirm']) ? 'is-invalid' : '' ?>" 
                                    placeholder="Re-enter your password"
                                    required
                                >
                                <button type="button" class="password-toggle" id="passwordConfirmToggle">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($errors['password_confirm'])): ?>
                                <span class="input-error"><?= htmlspecialchars($errors['password_confirm']) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="terms" required>
                                <span>I agree to the <a href="/host/mod/terms" target="_blank">Terms & Conditions</a> and <a href="/host/mod/privacy" target="_blank">Privacy Policy</a></span>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-user-plus"></i>
                            Create Account
                        </button>
                        
                        <div class="auth-divider">
                            <span>or</span>
                        </div>
                        
                        <div class="social-login">
                            <button type="button" class="btn btn-social btn-google">
                                <i class="fab fa-google"></i>
                                Sign up with Google
                            </button>
                            <button type="button" class="btn btn-social btn-facebook">
                                <i class="fab fa-facebook-f"></i>
                                Sign up with Facebook
                            </button>
                        </div>
                        
                        <p class="auth-footer-text">
                            Already have an account? 
                            <a href="<?= View::url('login') ?>" class="auth-link">Login here</a>
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

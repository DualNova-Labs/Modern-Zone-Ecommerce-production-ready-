<?php
ob_start();
?>

<!-- Breadcrumb -->
<section class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="<?= View::url('/') ?>">Home</a></li>
            <li><a href="<?= View::url('/cart') ?>">Cart</a></li>
            <li>Checkout</li>
        </ul>
    </div>
</section>

<!-- Checkout Page -->
<section class="checkout-page" style="padding: 60px 0; background: #f8f9fa;">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto; text-align: center; background: white; padding: 60px 40px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #FF6B35 0%, #FF8C61 100%); border-radius: 50%; margin: 0 auto 30px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-shopping-bag" style="font-size: 48px; color: white;"></i>
            </div>
            
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 20px;">
                Checkout Coming Soon!
            </h1>
            
            <p style="font-size: 18px; color: #666; margin-bottom: 40px; line-height: 1.6;">
                Our secure checkout process is currently under development.<br>
                Payment gateway integration will be available soon.
            </p>
            
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="<?= View::url('/cart') ?>" 
                   class="btn btn-secondary" 
                   style="padding: 14px 32px; font-size: 16px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: white; color: #666; border: 2px solid #e0e0e0;">
                    <i class="fas fa-arrow-left"></i>
                    Back to Cart
                </a>
                
                <a href="<?= View::url('/products') ?>" 
                   class="btn btn-primary" 
                   style="padding: 14px 32px; font-size: 16px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #FF6B35 0%, #FF8C61 100%); color: white; border: none;">
                    <i class="fas fa-shopping-cart"></i>
                    Continue Shopping
                </a>
            </div>
            
            <!-- Features Preview -->
            <div style="margin-top: 60px; padding-top: 40px; border-top: 2px solid #f0f0f0;">
                <h3 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 30px;">
                    Coming Soon Features
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; text-align: center;">
                    <div>
                        <div style="width: 60px; height: 60px; background: #fff3e0; border-radius: 12px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-credit-card" style="font-size: 28px; color: #FF6B35;"></i>
                        </div>
                        <h4 style="font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px;">Secure Payments</h4>
                        <p style="font-size: 14px; color: #666; line-height: 1.5;">Multiple payment methods</p>
                    </div>
                    
                    <div>
                        <div style="width: 60px; height: 60px; background: #e8f5e9; border-radius: 12px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-lock" style="font-size: 28px; color: #4caf50;"></i>
                        </div>
                        <h4 style="font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px;">SSL Encrypted</h4>
                        <p style="font-size: 14px; color: #666; line-height: 1.5;">Your data is protected</p>
                    </div>
                    
                    <div>
                        <div style="width: 60px; height: 60px; background: #e3f2fd; border-radius: 12px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-truck" style="font-size: 28px; color: #2196f3;"></i>
                        </div>
                        <h4 style="font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px;">Fast Delivery</h4>
                        <p style="font-size: 14px; color: #666; line-height: 1.5;">Track your orders</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
View::layout('layouts/master', compact('content', 'title', 'description'));
?>

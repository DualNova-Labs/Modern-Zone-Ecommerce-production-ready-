<header class="header">
    <!-- Top Bar -->
    <div class="header-top">
        <div class="container">
            <div class="header-top-content">
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <!-- Logo -->
                <div class="logo">
                    <a href="<?= View::url('') ?>" class="logo-link">
                        <img src="<?= View::asset('images/logo_header.png') ?>" alt="Modern Zone Trading Logo" class="logo-img">
                    </a>
                </div>
                
                <!-- Search Bar -->
                <div class="header-search">
                    <form action="<?= View::url('products') ?>" method="GET" class="search-form">
                        <input type="text" name="search" class="search-input" placeholder="Search industrial tools & equipment...">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Header Icons -->
                <div class="header-icons">
                    <!-- Cart -->
                    <div class="cart-icon-wrapper" style="position: relative;">
                        <a href="<?= View::url('cart') ?>" class="icon-btn" id="cartIconBtn">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="icon-label">Cart</span>
                            <span class="icon-badge cart-count"><?php 
                                require_once APP_PATH . '/models/Cart.php';
                                echo Cart::getInstance()->getCount();
                            ?></span>
                        </a>
                        
                        <!-- Mini Cart Dropdown -->
                        <?php
                        $cart = Cart::getInstance();
                        $miniCartData = [
                            'items' => $cart->getItems(),
                            'summary' => $cart->getSummary()
                        ];
                        View::component('mini-cart', $miniCartData);
                        ?>
                    </div>
                    
                    <!-- User Account (Desktop Only) -->
                    <div class="header-user-desktop">
                        <?php if (View::isAuth()): ?>
                            <div class="user-menu">
                                <button class="icon-btn" id="userMenuBtn">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="icon-label"><?= htmlspecialchars(View::user()->name) ?></span>
                                    <i class="fas fa-chevron-down" style="font-size: 0.7em; margin-left: 4px;"></i>
                                </button>
                                <div class="user-dropdown" id="userDropdown">
                                    <div class="user-dropdown-header">
                                        <div class="user-avatar">
                                            <img src="<?= View::user()->getAvatarUrl() ?>" alt="<?= htmlspecialchars(View::user()->name) ?>">
                                        </div>
                                        <div class="user-info">
                                            <strong><?= htmlspecialchars(View::user()->name) ?></strong>
                                            <small><?= htmlspecialchars(View::user()->email) ?></small>
                                        </div>
                                    </div>
                                    <div class="user-dropdown-divider"></div>
                                    <a href="<?= View::url('account') ?>" class="user-link">
                                        <i class="fas fa-user-circle"></i> My Account
                                    </a>
                                    <a href="<?= View::url('orders') ?>" class="user-link">
                                        <i class="fas fa-shopping-bag"></i> My Orders
                                    </a>
                                    <?php if (View::user()->isAdmin()): ?>
                                        <div class="user-dropdown-divider"></div>
                                        <a href="<?= View::url('admin') ?>" class="user-link">
                                            <i class="fas fa-cog"></i> Admin Panel
                                        </a>
                                    <?php endif; ?>
                                    <div class="user-dropdown-divider"></div>
                                    <a href="<?= View::url('logout') ?>" class="user-link logout-link">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="<?= View::url('login') ?>" class="icon-btn">
                                <i class="fas fa-user"></i>
                                <span class="icon-label">Login</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Navigation -->
    <div class="header-nav">
        <div class="container">
            <div class="header-nav-content">
                <!-- Main Menu -->
                <nav class="main-nav" id="mainNav">
                    <ul class="main-nav-list">
                        <li class="main-nav-item">
                            <a href="<?= View::url('') ?>" class="main-nav-link">HOME</a>
                        </li>
                        <li class="main-nav-item main-nav-dropdown">
                            <a href="<?= View::url('products') ?>" class="main-nav-link">
                                PRODUCTS BY BRAND
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="main-nav-dropdown-menu">
                                <?php 
                                require_once APP_PATH . '/models/Brand.php';
                                $navBrands = Brand::getActiveWithSubcategories();
                                foreach ($navBrands as $brandData):
                                    $brandSubcategories = $brandData['subcategories'] ?? [];
                                    
                                    if (!empty($brandSubcategories)):
                                ?>
                                    <div class="main-nav-dropdown-item has-submenu">
                                        <span><?= htmlspecialchars($brandData['name']) ?> <i class="fas fa-chevron-right"></i></span>
                                        <div class="main-nav-submenu">
                                            <?php foreach ($brandSubcategories as $subcat): ?>
                                                <a href="<?= View::url('products?brand=' . $brandData['slug'] . '&subcategory=' . $subcat['slug']) ?>" class="main-nav-submenu-item">
                                                    <?= htmlspecialchars($subcat['name']) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <a href="<?= View::url('products?brand=' . $brandData['slug']) ?>" class="main-nav-dropdown-item">
                                        <?= htmlspecialchars($brandData['name']) ?>
                                    </a>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </li>
                        <li class="main-nav-item main-nav-dropdown">
                            <a href="<?= View::url('products') ?>" class="main-nav-link">
                                GENERAL CATEGORIES
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="main-nav-dropdown-menu">
                                <?php 
                                require_once APP_PATH . '/models/Category.php';
                                $generalCategories = Category::getByType('general');
                                foreach ($generalCategories as $category):
                                ?>
                                    <a href="<?= View::url('products?category=' . $category['slug']) ?>" class="main-nav-dropdown-item">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </li>
                        <li class="main-nav-item main-nav-dropdown">
                            <a href="<?= View::url('products') ?>" class="main-nav-link">
                                OUR PRODUCTS
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="main-nav-dropdown-menu">
                                <?php 
                                $ourProductsCategories = Category::getByType('our-products');
                                foreach ($ourProductsCategories as $category):
                                ?>
                                    <a href="<?= View::url('products?category=' . $category['slug']) ?>" class="main-nav-dropdown-item">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </li>
                        <li class="main-nav-item">
                            <a href="<?= View::url('about') ?>" class="main-nav-link">ABOUT US</a>
                        </li>
                        <li class="main-nav-item">
                            <a href="<?= View::url('contact') ?>" class="main-nav-link">CONTACT US</a>
                        </li>
                    </ul>
                    
                    <div class="mobile-nav-lower">
                        <div class="mobile-user-shell" id="mobileUserSection">
                            <?php if (View::isAuth()): ?>
                                <button class="mobile-user-toggle" type="button" aria-expanded="false" aria-controls="mobileUserPanel" data-user-toggle>
                                    <span class="mobile-user-toggle-avatar">
                                        <img src="<?= View::user()->getAvatarUrl() ?>" alt="<?= htmlspecialchars(View::user()->name) ?>">
                                    </span>
                                    <span class="mobile-user-toggle-text">
                                        <strong><?= htmlspecialchars(View::user()->name) ?></strong>
                                        <small>Account shortcuts</small>
                                    </span>
                                    <span class="mobile-user-toggle-icon">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </button>
                                <div class="mobile-user-panel" id="mobileUserPanel" data-user-panel hidden>
                                    <div class="mobile-user-primary">
                                        <div class="mobile-user-avatar-large">
                                            <img src="<?= View::user()->getAvatarUrl() ?>" alt="<?= htmlspecialchars(View::user()->name) ?>">
                                        </div>
                                        <div class="mobile-user-details">
                                            <span class="mobile-user-name"><?= htmlspecialchars(View::user()->name) ?></span>
                                            <span class="mobile-user-email"><?= htmlspecialchars(View::user()->email) ?></span>
                                        </div>
                                    </div>
                                    <div class="mobile-user-links">
                                        <a href="<?= View::url('account') ?>" class="mobile-user-link">
                                            <i class="fas fa-user-circle"></i>
                                            <span>My Account</span>
                                        </a>
                                        <a href="<?= View::url('orders') ?>" class="mobile-user-link">
                                            <i class="fas fa-shopping-bag"></i>
                                            <span>My Orders</span>
                                        </a>
                                        <a href="<?= View::url('cart') ?>" class="mobile-user-link">
                                            <i class="fas fa-shopping-cart"></i>
                                            <span>Cart</span>
                                            <span class="mobile-cart-badge cart-count"><?= Cart::getInstance()->getCount() ?></span>
                                        </a>
                                        <?php if (View::user()->isAdmin()): ?>
                                            <a href="<?= View::url('admin') ?>" class="mobile-user-link">
                                                <i class="fas fa-cog"></i>
                                                <span>Admin Panel</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <button class="mobile-user-toggle" type="button" aria-expanded="false" aria-controls="mobileUserPanel" data-user-toggle>
                                    <span class="mobile-user-toggle-avatar guest">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <span class="mobile-user-toggle-text">
                                        <strong>Guest</strong>
                                        <small>Sign in for more</small>
                                    </span>
                                    <span class="mobile-user-toggle-icon">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </button>
                                <div class="mobile-user-panel" id="mobileUserPanel" data-user-panel hidden>
                                    <div class="mobile-user-guest">
                                        <p>Access orders, wishlist, and more.</p>
                                        <div class="mobile-auth-buttons">
                                            <a href="<?= View::url('login') ?>" class="mobile-auth-btn mobile-login-btn">
                                                <i class="fas fa-sign-in-alt"></i> Login
                                            </a>
                                            <a href="<?= View::url('register') ?>" class="mobile-auth-btn mobile-register-btn">
                                                <i class="fas fa-user-plus"></i> Register
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Mobile Logout Button -->
                        <?php if (View::isAuth()): ?>
                        <div class="mobile-logout-section">
                            <a href="<?= View::url('logout') ?>" class="mobile-logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </nav>
                
                <!-- Mobile Nav Overlay -->
                <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
            </div>
        </div>
    </div>
    
</header>

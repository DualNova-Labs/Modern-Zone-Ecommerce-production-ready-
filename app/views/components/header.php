<header class="header">
    <!-- Top Bar -->
    <div class="header-top">
        <div class="container">
            <div class="header-top-content">
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
                    <!-- Language Selector -->
                    <div class="language-selector">
                        <button class="icon-btn" id="langBtn">
                            <i class="fas fa-globe"></i>
                            <span class="icon-label">عربي</span>
                        </button>
                        <div class="language-dropdown" id="langDropdown">
                            <a href="#" class="lang-option active">English</a>
                            <a href="#" class="lang-option">العربية</a>
                        </div>
                    </div>
                    
                    <!-- Cart -->
                    <a href="<?= View::url('cart') ?>" class="icon-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="icon-label">Cart</span>
                        <span class="icon-badge">0</span>
                    </a>
                    
                    <!-- User Account -->
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
                                <a href="<?= View::url('wishlist') ?>" class="user-link">
                                    <i class="fas fa-heart"></i> Wishlist
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
                
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
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
                                $navBrands = Brand::getWithProducts();
                                foreach ($navBrands as $brandData):
                                    $brandObj = new Brand();
                                    $brandObj->id = $brandData['id'];
                                    $brandCategories = $brandObj->getCategories();
                                    
                                    if (!empty($brandCategories)):
                                ?>
                                    <div class="main-nav-dropdown-item has-submenu">
                                        <span><?= htmlspecialchars($brandData['name']) ?> <i class="fas fa-chevron-right"></i></span>
                                        <div class="main-nav-submenu">
                                            <?php foreach ($brandCategories as $cat): ?>
                                                <a href="<?= View::url('products?brand=' . $brandData['slug'] . '&category=' . $cat['slug']) ?>" class="main-nav-submenu-item">
                                                    <?= htmlspecialchars($cat['name']) ?>
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
                                <a href="<?= View::url('products?category=hand-tools') ?>" class="main-nav-dropdown-item">Hand Tools</a>
                                <a href="<?= View::url('products?category=power-tools-electrical') ?>" class="main-nav-dropdown-item">Power Tools Electrical</a>
                                <a href="<?= View::url('products?category=other-measuring-instruments') ?>" class="main-nav-dropdown-item">Other Measuring Instruments</a>
                                <a href="<?= View::url('products?category=safety') ?>" class="main-nav-dropdown-item">Safety</a>
                                <a href="<?= View::url('products?category=machine-shop') ?>" class="main-nav-dropdown-item">Machine Shop</a>
                                <a href="<?= View::url('products?category=abrasive') ?>" class="main-nav-dropdown-item">Abrasive</a>
                                <a href="<?= View::url('products?category=welding') ?>" class="main-nav-dropdown-item">Welding</a>
                                <a href="<?= View::url('products?category=plumbing') ?>" class="main-nav-dropdown-item">Plumbing</a>
                                <a href="<?= View::url('products?category=construction') ?>" class="main-nav-dropdown-item">Construction</a>
                                <a href="<?= View::url('products?category=uncategorized') ?>" class="main-nav-dropdown-item">Uncategorized/Others</a>
                            </div>
                        </li>
                        <li class="main-nav-item main-nav-dropdown">
                            <a href="<?= View::url('products') ?>" class="main-nav-link">
                                OUR PRODUCTS
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="main-nav-dropdown-menu">
                                <a href="<?= View::url('products?category=ball-cages') ?>" class="main-nav-dropdown-item">Ball Cages</a>
                                <a href="<?= View::url('products?category=band-saw-blades') ?>" class="main-nav-dropdown-item">Band Saw Blades</a>
                                <a href="<?= View::url('products?category=brazed-tool-holders') ?>" class="main-nav-dropdown-item">Brazed Tool Holders</a>
                                <a href="<?= View::url('products?category=bushes') ?>" class="main-nav-dropdown-item">Bushes</a>
                                <a href="<?= View::url('products?category=carbide-hss-drill-bits') ?>" class="main-nav-dropdown-item">Carbide & HSS Drill Bits</a>
                                <a href="<?= View::url('products?category=carbide-hss-end-mills') ?>" class="main-nav-dropdown-item">Carbide & HSS End Mills</a>
                                <a href="<?= View::url('products?category=carbide-rotary-burrs') ?>" class="main-nav-dropdown-item">Carbide Rotary Burrs</a>
                                <a href="<?= View::url('products?category=drill-chucks-lathe') ?>" class="main-nav-dropdown-item">Drill Chucks & Lathe</a>
                                <a href="<?= View::url('products?category=ejector-pins') ?>" class="main-nav-dropdown-item">Ejector Pins</a>
                                <a href="<?= View::url('products?category=fibro') ?>" class="main-nav-dropdown-item">Fibro</a>
                                <a href="<?= View::url('products?category=grooving-threading-cut') ?>" class="main-nav-dropdown-item">Grooving, Threading, Cut</a>
                                <a href="<?= View::url('products?category=hack-saw-blades') ?>" class="main-nav-dropdown-item">Hack Saw Blades</a>
                                <a href="<?= View::url('products?category=hole-saw-blades') ?>" class="main-nav-dropdown-item">Hole Saw Blades</a>
                                <a href="<?= View::url('products?category=hole-saw-core-cutters') ?>" class="main-nav-dropdown-item">Hole Saw and Core Cutters</a>
                                <a href="<?= View::url('products?category=machine-tool-accessories') ?>" class="main-nav-dropdown-item">Machine Tool Accessories</a>
                                <a href="<?= View::url('products?category=measuring-instruments') ?>" class="main-nav-dropdown-item">Measuring Instruments</a>
                                <a href="<?= View::url('products?category=milling-cutters') ?>" class="main-nav-dropdown-item">Milling Cutters</a>
                                <a href="<?= View::url('products?category=pcd-cbn-ceramic-inserts') ?>" class="main-nav-dropdown-item">PCD, CBN Ceramic Inserts</a>
                                <a href="<?= View::url('products?category=pillars') ?>" class="main-nav-dropdown-item">Pillars</a>
                                <a href="<?= View::url('products?category=punches') ?>" class="main-nav-dropdown-item">Punches</a>
                                <a href="<?= View::url('products?category=reamers-countersinks') ?>" class="main-nav-dropdown-item">Reamers & Countersinks</a>
                                <a href="<?= View::url('products?category=springs') ?>" class="main-nav-dropdown-item">Springs</a>
                                <a href="<?= View::url('products?category=standard-parts-dies-molds') ?>" class="main-nav-dropdown-item">Standard Parts for Dies & Molds</a>
                                <a href="<?= View::url('products?category=taps-dies') ?>" class="main-nav-dropdown-item">Taps & Dies</a>
                                <a href="<?= View::url('products?category=turning-holders') ?>" class="main-nav-dropdown-item">Turning Holders</a>
                                <a href="<?= View::url('products?category=turning-inserts') ?>" class="main-nav-dropdown-item">Turning Inserts</a>
                                <a href="<?= View::url('products?category=u-drills') ?>" class="main-nav-dropdown-item">U Drills</a>
                            </div>
                        </li>
                        <li class="main-nav-item">
                            <a href="<?= View::url('about') ?>" class="main-nav-link">ABOUT US</a>
                        </li>
                        <li class="main-nav-item">
                            <a href="<?= View::url('contact') ?>" class="main-nav-link">CONTACT US</a>
                        </li>
                    </ul>
                </nav>
                
            </div>
        </div>
    </div>
    
</header>

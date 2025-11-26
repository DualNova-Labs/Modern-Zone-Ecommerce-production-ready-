<?php
$currentPath = $_SERVER['REQUEST_URI'];
$user = Auth::getInstance()->user();
?>
<!-- Mobile Menu Toggle -->
<button class="mobile-menu-toggle" id="mobileMenuToggle">
    <span></span>
    <span></span>
    <span></span>
</button>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <img src="<?= View::url('/public/assets/images/logo_header.png') ?>" alt="Modern Zone Trading" class="logo-image">
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="<?= View::url('/admin') ?>" class="nav-item <?= strpos($currentPath, '/admin') !== false && strpos($currentPath, '/admin/') === false ? 'active' : '' ?>">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="<?= View::url('/admin/products') ?>" class="nav-item <?= strpos($currentPath, '/admin/products') !== false ? 'active' : '' ?>">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            <span>Products</span>
        </a>

        <a href="<?= View::url('/admin/orders') ?>" class="nav-item <?= strpos($currentPath, '/admin/orders') !== false ? 'active' : '' ?>">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Orders</span>
        </a>

        <a href="<?= View::url('/admin/analytics') ?>" class="nav-item <?= strpos($currentPath, '/admin/analytics') !== false ? 'active' : '' ?>">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="20" x2="12" y2="10"></line>
                <line x1="18" y1="20" x2="18" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="16"></line>
            </svg>
            <span>Analytics</span>
        </a>

        <a href="<?= View::url('/admin/banners') ?>" class="nav-item <?= strpos($currentPath, '/admin/banners') !== false ? 'active' : '' ?>">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
            <span>Banners</span>
        </a>

        <a href="<?= View::url('/admin/brands') ?>" class="nav-item <?= strpos($currentPath, '/admin/brands') !== false ? 'active' : '' ?>">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Brands</span>
        </a>

        <div class="nav-divider"></div>

        <a href="<?= View::url('/') ?>" class="nav-item" target="_blank">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
            <span>View Site</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <?= strtoupper(substr($user->name ?? 'A', 0, 1)) ?>
            </div>
            <div class="user-details">
                <div class="user-name"><?= htmlspecialchars($user->name ?? 'Admin') ?></div>
                <div class="user-role"><?= ucfirst($user->role ?? 'Administrator') ?></div>
            </div>
        </div>
        <a href="<?= View::url('/admin/logout') ?>" class="logout-btn" title="Logout">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </a>
    </div>
</aside>

<script>
// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    function toggleSidebar() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        mobileToggle.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    }
    
    mobileToggle.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);
    
    // Close sidebar when clicking nav links on mobile
    const navLinks = sidebar.querySelectorAll('.nav-item');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        });
    });
    
    // Handle window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            mobileToggle.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});
</script>

<style>
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        width: 280px;
        background: white;
        display: flex;
        flex-direction: column;
        z-index: 1001;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-right: 1px solid #e2e8f0;
        transform: translateX(0);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .mobile-menu-toggle {
        display: none;
        position: fixed;
        top: 11px;
        left: 11px;
        z-index: 1002;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
    }
    
    .mobile-menu-toggle:hover {
        background: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }
    
    .mobile-menu-toggle:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .mobile-menu-toggle span {
        display: block;
        width: 20px;
        height: 2px;
        background: #334155;
        margin: 2px 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 1px;
    }
    
    .mobile-menu-toggle.active span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }
    
    .mobile-menu-toggle.active span:nth-child(2) {
        opacity: 0;
    }
    
    .mobile-menu-toggle.active span:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -6px);
    }
    
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(2px);
    }
    
    .sidebar-overlay.active {
        display: block;
        opacity: 1;
    }

    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .logo {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 0.75rem;
        white-space: nowrap;
    }

    .logo-image {
        height: 40px;
        width: auto;
        object-fit: contain;
        flex-shrink: 0;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .logo-text {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: -0.3px;
        white-space: nowrap;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .sidebar-nav {
        flex: 1;
        padding: 1.5rem 1rem;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }
    
    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }
    
    .sidebar-nav::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    
    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        color: #64748b;
        text-decoration: none;
        border-radius: 10px;
        margin-bottom: 0.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.875rem;
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }
    
    .nav-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .nav-item:hover::before {
        left: 100%;
    }

    .nav-item:hover {
        background: #f8fafc;
        color: #1e293b;
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .nav-item.active {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        transform: translateX(4px);
    }
    
    .nav-item.active::before {
        display: none;
    }

    .nav-icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    .nav-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 12px 0;
    }

    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: 0 4px 8px rgba(99, 102, 241, 0.2);
        border: 2px solid white;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        color: #1e293b;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        color: #64748b;
        font-size: 12px;
    }

    .logout-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(239, 68, 68, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ef4444;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
        text-decoration: none;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .logout-btn:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .logout-btn svg {
        width: 18px;
        height: 18px;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .mobile-menu-toggle {
            display: flex !important;
        }
        
        .sidebar {
            transform: translateX(-100%);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
    }
    
    @media (max-width: 480px) {
        .sidebar {
            width: 100vw;
        }
        
        .logo-text {
            font-size: 1.125rem;
        }
        
        .nav-item {
            padding: 1rem;
            font-size: 1rem;
        }
        
        .sidebar-header {
            padding: 1rem;
        }
        
        .sidebar-footer {
            padding: 1rem;
        }
    }
</style>

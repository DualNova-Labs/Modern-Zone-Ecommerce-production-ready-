<?php
$user = Auth::getInstance()->user();
$pageTitle = $pageTitle ?? 'Dashboard';
?>
<header class="main-header">
    <div class="header-content">
        <div class="header-left">
            <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
            <div class="breadcrumb">
                <?= $breadcrumb ?? '' ?>
            </div>
        </div>

        <div class="header-right">

            <div class="header-actions">
                <button class="icon-btn" title="Notifications" onclick="toggleNotifications()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="badge-dot"></span>
                </button>

            <!-- Notifications Dropdown -->
            <div class="notifications-dropdown" id="notificationsDropdown">
                <div class="dropdown-header">
                    <h3>Notifications</h3>
                    <span class="notification-count" id="notificationCount"></span>
                </div>
                <div class="notification-list" id="notificationList">
                    <div class="empty-notifications">
                        <p>No new notifications</p>
                    </div>
                </div>
                <div class="dropdown-footer">
                    <a href="#">View all notifications</a>
                </div>
            </div>

            <div class="user-menu">
                <button class="user-menu-btn" onclick="toggleUserMenu()">
                    <div class="user-avatar-small">
                        <?= strtoupper(substr($user->name ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="user-info-header">
                        <span class="user-name-header"><?= htmlspecialchars($user->name ?? 'Admin') ?></span>
                        <span class="user-email-header"><?= htmlspecialchars($user->email ?? '') ?></span>
                    </div>
                    <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>

                <!-- User Dropdown Menu -->
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-dropdown-header">
                        <div class="user-avatar-large">
                            <?= strtoupper(substr($user->name ?? 'A', 0, 1)) ?>
                        </div>
                        <div class="user-dropdown-info">
                            <div class="user-dropdown-name"><?= htmlspecialchars($user->name ?? 'Admin') ?></div>
                            <div class="user-dropdown-email"><?= htmlspecialchars($user->email ?? '') ?></div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="<?= View::url('/admin') ?>" class="dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Dashboard
                    </a>
                    <a href="<?= View::url('/account/profile') ?>" class="dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Profile Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?= View::url('/admin/logout') ?>" class="dropdown-item logout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    .main-header {
        position: sticky;
        top: 0;
        left: 0;
        right: 0;
        height: 70px;
        background: white;
        border-bottom: 1px solid #e2e8f0;
        z-index: 999;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }
    
    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 100%;
        padding: 0 1.5rem;
        max-width: 100%;
    }

    .header-left {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        flex: 1;
        min-width: 0;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .breadcrumb {
        color: #64748b;
        font-size: 0.875rem;
        line-height: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-shrink: 0;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .icon-btn {
        position: relative;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .icon-btn:hover {
        background: #6366f1;
        color: white;
        border-color: #6366f1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .badge-dot {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
        border: 2px solid white;
        display: none;
    }

    .badge-dot.show {
        display: block;
    }

    .user-menu {
        position: relative;
    }

    .user-menu-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.625rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        max-width: 200px;
    }

    .user-menu-btn:hover {
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .user-avatar-small {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.8125rem;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
        border: 2px solid white;
    }

    /* Notifications Dropdown */
    .notifications-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 320px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        margin-top: 8px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    .notifications-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .dropdown-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .notification-count {
        font-size: 12px;
        color: #6366f1;
        font-weight: 600;
    }

    .notification-list {
        max-height: 350px;
        overflow-y: auto;
    }

    .notification-item {
        padding: 14px 20px;
        display: flex;
        gap: 12px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background 0.2s;
    }

    .notification-item:hover {
        background: #f8fafc;
    }

    .notification-item.unread {
        background: #f0f9ff;
    }

    .notification-icon {
        font-size: 24px;
        flex-shrink: 0;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .notification-time {
        font-size: 11px;
        color: #64748b;
    }

    .empty-notifications {
        padding: 40px 20px;
        text-align: center;
    }

    .empty-notifications p {
        color: #94a3b8;
        font-size: 14px;
    }

    .dropdown-footer {
        padding: 12px 20px;
        border-top: 1px solid #e2e8f0;
        text-align: center;
    }

    .dropdown-footer a {
        font-size: 13px;
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
    }

    .dropdown-footer a:hover {
        text-decoration: underline;
    }

    /* User Dropdown */
    .user-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 280px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        margin-top: 8px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    .user-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .user-dropdown-header {
        padding: 20px;
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .user-avatar-large {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
        flex-shrink: 0;
    }

    .user-dropdown-info {
        flex: 1;
        min-width: 0;
    }

    .user-dropdown-name {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-dropdown-email {
        font-size: 12px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-info-header {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
        min-width: 0;
        flex: 1;
    }

    .user-name-header {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
    }

    .user-email-header {
        font-size: 0.6875rem;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
    }

    .chevron {
        width: 14px;
        height: 14px;
        color: #64748b;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
    }

    .icon-btn svg {
        width: 18px;
        height: 18px;
    }

    .dropdown-divider {
        height: 1px;
        background: #e2e8f0;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.25rem;
        color: #64748b;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .dropdown-item:hover {
        background: #f8fafc;
        color: #1e293b;
        transform: translateX(4px);
    }

    .dropdown-item.logout {
        color: #ef4444;
        border-top: 1px solid #f1f5f9;
    }

    .dropdown-item.logout:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .dropdown-item svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }
    /* Desktop Optimizations */
    @media (min-width: 1200px) {
        .header-content {
            padding: 0 2rem;
        }
        
        .header-right {
            gap: 1.25rem;
        }
        
        .user-menu-btn {
            max-width: 250px;
        }
        
        .user-name-header,
        .user-email-header {
            max-width: 150px;
        }
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .header-content {
            padding: 0 1rem 0 4.5rem;
        }
        
        .page-title {
            font-size: 1.25rem;
        }
        
        .breadcrumb {
            font-size: 0.75rem;
        }
        
        .header-right {
            gap: 0.5rem;
        }
        
        .icon-btn {
            width: 36px;
            height: 36px;
        }
        
        .user-info-header {
            display: none;
        }
        
        .user-menu-btn {
            padding: 0.375rem;
        }
        
        .notifications-dropdown,
        .user-dropdown {
            width: 280px;
            right: -1rem;
        }
    }
    
    @media (max-width: 480px) {
        .header-content {
            padding: 0 0.5rem 0 4rem;
        }
        
        .page-title {
            font-size: 1.125rem;
        }
        
        .header-right {
            gap: 0.5rem;
        }
        
        .icon-btn {
            width: 36px;
            height: 36px;
        }
        
        .user-avatar-small {
            width: 32px;
            height: 32px;
        }
        
        .notifications-dropdown,
        .user-dropdown {
            width: calc(100vw - 2rem);
            right: -0.5rem;
        }
    }
</style>

<script>

// Notifications functionality
function toggleNotifications() {
    try {
        const dropdown = document.getElementById('notificationsDropdown');
        
        if (!dropdown) {
            console.error('Notifications dropdown not found');
            return;
        }
        
        const isVisible = dropdown.style.opacity === '1' || dropdown.classList.contains('show');
        
        if (isVisible) {
            dropdown.style.opacity = '0';
            dropdown.style.visibility = 'hidden';
            dropdown.style.transform = 'translateY(-10px)';
            dropdown.classList.remove('show');
        } else {
            dropdown.style.opacity = '1';
            dropdown.style.visibility = 'visible';
            dropdown.style.transform = 'translateY(0)';
            dropdown.classList.add('show');
        }
        
        // Close user dropdown if open
        const userDropdown = document.getElementById('userDropdown');
        if (userDropdown) {
            userDropdown.style.opacity = '0';
            userDropdown.style.visibility = 'hidden';
            userDropdown.style.transform = 'translateY(-10px)';
            userDropdown.classList.remove('show');
        }
    } catch (error) {
        console.error('Error toggling notifications:', error);
    }
}

// User menu functionality
function toggleUserMenu() {
    try {
        const dropdown = document.getElementById('userDropdown');
        
        if (!dropdown) {
            console.error('User dropdown not found');
            return;
        }
        
        const chevron = document.querySelector('.user-menu .chevron');
        const isVisible = dropdown.style.opacity === '1' || dropdown.classList.contains('show');
        
        if (isVisible) {
            dropdown.style.opacity = '0';
            dropdown.style.visibility = 'hidden';
            dropdown.style.transform = 'translateY(-10px)';
            dropdown.classList.remove('show');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        } else {
            dropdown.style.opacity = '1';
            dropdown.style.visibility = 'visible';
            dropdown.style.transform = 'translateY(0)';
            dropdown.classList.add('show');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        }
        
        // Close notifications dropdown if open
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        if (notificationsDropdown) {
            notificationsDropdown.style.opacity = '0';
            notificationsDropdown.style.visibility = 'hidden';
            notificationsDropdown.style.transform = 'translateY(-10px)';
            notificationsDropdown.classList.remove('show');
        }
    } catch (error) {
        console.error('Error toggling user menu:', error);
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    try {
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        const userDropdown = document.getElementById('userDropdown');
        const chevron = document.querySelector('.user-menu .chevron');
        
        // Close notifications dropdown if clicking outside
        if (notificationsDropdown && !event.target.closest('.header-actions')) {
            notificationsDropdown.style.opacity = '0';
            notificationsDropdown.style.visibility = 'hidden';
            notificationsDropdown.style.transform = 'translateY(-10px)';
            notificationsDropdown.classList.remove('show');
        }
        
        // Close user dropdown if clicking outside
        if (userDropdown && !event.target.closest('.user-menu')) {
            userDropdown.style.opacity = '0';
            userDropdown.style.visibility = 'hidden';
            userDropdown.style.transform = 'translateY(-10px)';
            userDropdown.classList.remove('show');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    } catch (error) {
        console.error('Error handling outside click:', error);
    }
});

// Handle escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        try {
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            const userDropdown = document.getElementById('userDropdown');
            const chevron = document.querySelector('.user-menu .chevron');
            
            if (notificationsDropdown) {
                notificationsDropdown.style.opacity = '0';
                notificationsDropdown.style.visibility = 'hidden';
                notificationsDropdown.style.transform = 'translateY(-10px)';
                notificationsDropdown.classList.remove('show');
            }
            
            if (userDropdown) {
                userDropdown.style.opacity = '0';
                userDropdown.style.visibility = 'hidden';
                userDropdown.style.transform = 'translateY(-10px)';
                userDropdown.classList.remove('show');
            }
            
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        } catch (error) {
            console.error('Error handling escape key:', error);
        }
    }
});

// Initialize dropdown states
document.addEventListener('DOMContentLoaded', function() {
    try {
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        const userDropdown = document.getElementById('userDropdown');
        
        if (notificationsDropdown) {
            notificationsDropdown.style.opacity = '0';
            notificationsDropdown.style.visibility = 'hidden';
            notificationsDropdown.style.transform = 'translateY(-10px)';
        }
        
        if (userDropdown) {
            userDropdown.style.opacity = '0';
            userDropdown.style.visibility = 'hidden';
            userDropdown.style.transform = 'translateY(-10px)';
        }
    } catch (error) {
        console.error('Error initializing header functionality:', error);
    }
});
</script>

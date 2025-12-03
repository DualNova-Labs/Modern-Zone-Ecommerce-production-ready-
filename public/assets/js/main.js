/**
 * APTools Frontend JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    // Hero Banner Slider
    initHeroSlider();

    // Brands Carousel
    initBrandsCarousel();

    // Mobile Menu Toggle
    initMobileMenu();

    // Back to Top Button
    initBackToTop();

    // Testimonials Slider
    initTestimonialsSlider();

    // Best Selling Slider
    initBestSellingSlider();

    // Password Toggle
    initPasswordToggle();

    // Product Filters
    initProductFilters();

    // Product Gallery
    initProductGallery();

    // Product Tabs
    initProductTabs();

    // FAQ Accordion
    initFAQAccordion();

    // Newsletter Form
    initNewsletterForm();

    // Product Quantity
    initQuantitySelector();

    // User Menu Dropdown
    initUserMenu();
});

/**
 * Hero Banner Slider
 */
function initHeroSlider() {
    const track = document.getElementById('heroSliderTrack');
    const slides = document.querySelectorAll('.slider-item');
    const prevBtn = document.getElementById('heroPrev');
    const nextBtn = document.getElementById('heroNext');
    const dots = document.querySelectorAll('.slider-dot');

    if (!track || slides.length === 0) return;

    let currentSlide = 0;
    const totalSlides = slides.length;

    function showSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        // Add active class to current slide and dot
        slides[index].classList.add('active');
        dots[index].classList.add('active');

        // Move track
        track.style.transform = `translateX(-${index * 100}%)`;
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    }

    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
    }

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', function () {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });

    // Auto-play slider every 5 seconds
    setInterval(nextSlide, 5000);

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') prevSlide();
        if (e.key === 'ArrowRight') nextSlide();
    });
}

/**
 * Brands Carousel
 */
function initBrandsCarousel() {
    const track = document.querySelector('.brands-track');
    const prevBtn = document.getElementById('brandsPrev');
    const nextBtn = document.getElementById('brandsNext');

    if (!track || !prevBtn || !nextBtn) return;

    const items = track.querySelectorAll('.brand-item');

    // If no items or not enough items to scroll, hide arrows
    if (items.length === 0) return;

    let currentScroll = 0;

    function getScrollParams() {
        const itemWidth = 160; // brand item width
        const gap = 24; // gap between items (1.5rem = 24px)
        const slideWidth = itemWidth + gap;
        const containerWidth = track.parentElement.offsetWidth;
        const visibleItems = Math.floor(containerWidth / slideWidth);
        const totalWidth = items.length * slideWidth - gap;
        const maxScroll = Math.max(0, totalWidth - containerWidth);

        return { slideWidth, maxScroll, visibleItems };
    }

    function updateButtons() {
        const { maxScroll } = getScrollParams();

        // Hide arrows if all items are visible
        if (maxScroll <= 0) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
            return;
        } else {
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
        }

        prevBtn.style.opacity = currentScroll <= 0 ? '0.5' : '1';
        prevBtn.style.cursor = currentScroll <= 0 ? 'not-allowed' : 'pointer';
        nextBtn.style.opacity = currentScroll >= maxScroll ? '0.5' : '1';
        nextBtn.style.cursor = currentScroll >= maxScroll ? 'not-allowed' : 'pointer';
    }

    function scrollBrands(direction) {
        const { slideWidth, maxScroll } = getScrollParams();
        const scrollAmount = slideWidth * 3; // Scroll 3 items at a time

        if (direction === 'next') {
            currentScroll = Math.min(currentScroll + scrollAmount, maxScroll);
        } else {
            currentScroll = Math.max(currentScroll - scrollAmount, 0);
        }

        track.style.transform = `translateX(-${currentScroll}px)`;
        updateButtons();
    }

    prevBtn.addEventListener('click', () => scrollBrands('prev'));
    nextBtn.addEventListener('click', () => scrollBrands('next'));

    // Update on window resize
    let resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const { maxScroll } = getScrollParams();
            currentScroll = Math.min(currentScroll, maxScroll);
            track.style.transform = `translateX(-${currentScroll}px)`;
            updateButtons();
        }, 250);
    });

    // Initial button state
    updateButtons();
}

/**
 * Mobile Menu Toggle
 */
function initMobileMenu() {
    const menuToggle = document.getElementById('mobileMenuToggle');
    const mainNav = document.getElementById('mainNav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function () {
            menuToggle.classList.toggle('active');
            mainNav.classList.toggle('active');
            document.body.style.overflow = mainNav.classList.contains('active') ? 'hidden' : '';
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!menuToggle.contains(e.target) && !mainNav.contains(e.target)) {
                menuToggle.classList.remove('active');
                mainNav.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // Category dropdown for mobile
    const categoryBtn = document.getElementById('categoryBtn');
    const categoryDropdown = document.getElementById('categoryDropdown');

    if (categoryBtn && window.innerWidth <= 768) {
        categoryBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            categoryDropdown.style.opacity = categoryDropdown.style.opacity === '1' ? '0' : '1';
            categoryDropdown.style.visibility = categoryDropdown.style.visibility === 'visible' ? 'hidden' : 'visible';
        });
    }

    // Main nav dropdowns for mobile
    const mainNavDropdowns = document.querySelectorAll('.main-nav-dropdown');

    if (window.innerWidth <= 768) {
        mainNavDropdowns.forEach(dropdown => {
            const link = dropdown.querySelector('.main-nav-link');

            link.addEventListener('click', function (e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');

                    // Close other dropdowns
                    mainNavDropdowns.forEach(other => {
                        if (other !== dropdown) {
                            other.classList.remove('active');
                        }
                    });
                }
            });
        });
    }
}

/**
 * Back to Top Button
 */
function initBackToTop() {
    const backToTop = document.getElementById('backToTop');

    if (backToTop) {
        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        backToTop.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

/**
 * Testimonials Slider
 */
function initTestimonialsSlider() {
    const track = document.getElementById('testimonialsTrack');
    const prevBtn = document.getElementById('testimonialPrev');
    const nextBtn = document.getElementById('testimonialNext');

    if (track && prevBtn && nextBtn) {
        let currentIndex = 0;
        const cards = track.children;
        const cardWidth = cards[0]?.offsetWidth || 0;

        nextBtn.addEventListener('click', function () {
            if (currentIndex < cards.length - 1) {
                currentIndex++;
                track.scrollTo({
                    left: cardWidth * currentIndex,
                    behavior: 'smooth'
                });
            }
        });

        prevBtn.addEventListener('click', function () {
            if (currentIndex > 0) {
                currentIndex--;
                track.scrollTo({
                    left: cardWidth * currentIndex,
                    behavior: 'smooth'
                });
            }
        });

        // Auto-scroll every 5 seconds
        setInterval(function () {
            if (currentIndex < cards.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            track.scrollTo({
                left: cardWidth * currentIndex,
                behavior: 'smooth'
            });
        }, 5000);
    }
}

/**
 * Best Selling Slider
 */
function initBestSellingSlider() {
    const slider = document.getElementById('bestSellingSlider');
    const prevBtn = document.getElementById('bestSellingPrev');
    const nextBtn = document.getElementById('bestSellingNext');

    if (!slider || !prevBtn || !nextBtn) return;

    const scrollAmount = 250;

    nextBtn.addEventListener('click', function () {
        slider.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    });

    prevBtn.addEventListener('click', function () {
        slider.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
    });

    // Hide/show arrows based on scroll position
    function updateArrows() {
        const maxScroll = slider.scrollWidth - slider.clientWidth;
        prevBtn.style.opacity = slider.scrollLeft <= 0 ? '0.5' : '1';
        prevBtn.style.cursor = slider.scrollLeft <= 0 ? 'not-allowed' : 'pointer';
        nextBtn.style.opacity = slider.scrollLeft >= maxScroll ? '0.5' : '1';
        nextBtn.style.cursor = slider.scrollLeft >= maxScroll ? 'not-allowed' : 'pointer';
    }

    slider.addEventListener('scroll', updateArrows);
    updateArrows();
}

/**
 * Password Toggle
 */
function initPasswordToggle() {
    const toggleButtons = document.querySelectorAll('.password-toggle');

    toggleButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const input = button.previousElementSibling || button.parentElement.querySelector('input');
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
}

/**
 * Product Filters
 */
function initProductFilters() {
    const filterToggle = document.getElementById('filterToggle');
    const sidebar = document.getElementById('productsSidebar');
    const sidebarClose = document.getElementById('sidebarClose');

    if (filterToggle && sidebar) {
        filterToggle.addEventListener('click', function () {
            sidebar.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    if (sidebarClose && sidebar) {
        sidebarClose.addEventListener('click', function () {
            sidebar.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // View toggle
    const viewButtons = document.querySelectorAll('.view-btn');
    const productsGrid = document.getElementById('productsGrid');

    viewButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            viewButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const view = button.dataset.view;
            if (productsGrid) {
                if (view === 'list') {
                    productsGrid.style.gridTemplateColumns = '1fr';
                } else {
                    productsGrid.style.gridTemplateColumns = '';
                }
            }
        });
    });
}

/**
 * Product Gallery
 */
function initProductGallery() {
    const mainImage = document.getElementById('mainImage');
    const thumbButtons = document.querySelectorAll('.gallery-thumb');

    thumbButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const imageSrc = button.dataset.image;

            if (mainImage && imageSrc) {
                mainImage.src = imageSrc;

                thumbButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            }
        });
    });
}

/**
 * Product Tabs
 */
function initProductTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const tabId = button.dataset.tab;

            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            button.classList.add('active');
            const targetPanel = document.getElementById(tabId);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });
}

/**
 * FAQ Accordion
 */
function initFAQAccordion() {
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(function (question) {
        question.addEventListener('click', function () {
            const faqItem = question.parentElement;
            const isActive = faqItem.classList.contains('active');

            // Close all FAQs
            document.querySelectorAll('.faq-item').forEach(function (item) {
                item.classList.remove('active');
            });

            // Open clicked FAQ if it wasn't active
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });
}

/**
 * Newsletter Form
 */
function initNewsletterForm() {
    const form = document.getElementById('newsletterForm');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const emailInput = form.querySelector('input[type="email"]');
            const email = emailInput.value;

            if (email) {
                alert('Thank you for subscribing! You will receive our latest updates.');
                emailInput.value = '';
            }
        });
    }
}

/**
 * Product Quantity Selector
 */
function initQuantitySelector() {
    const minusBtn = document.getElementById('qtyMinus');
    const plusBtn = document.getElementById('qtyPlus');
    const input = document.getElementById('qtyInput');

    if (minusBtn && input) {
        minusBtn.addEventListener('click', function () {
            let value = parseInt(input.value) || 1;
            if (value > 1) {
                input.value = value - 1;
            }
        });
    }

    if (plusBtn && input) {
        plusBtn.addEventListener('click', function () {
            let value = parseInt(input.value) || 1;
            input.value = value + 1;
        });
    }
}

/**
 * Add to Cart - AJAX Implementation
 */
document.addEventListener('click', function (e) {
    if (e.target.closest('.add-to-cart')) {
        e.preventDefault();
        const button = e.target.closest('.add-to-cart');
        const productId = button.dataset.id;

        // Get quantity from product detail page if available
        const qtyInput = document.getElementById('qtyInput');
        const quantity = qtyInput ? parseInt(qtyInput.value) : 1;

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        if (!productId) {
            showNotification('Error: Product ID not found', 'error');
            return;
        }

        // Disable button and show loading state
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        // Make AJAX call to add item to cart
        fetch(getBaseUrl() + '/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}&csrf_token=${csrfToken}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count from server response
                    updateCartCount(data.cart_count);

                    // Show success feedback
                    button.innerHTML = '<i class="fas fa-check"></i> Added!';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');

                    // Show success notification
                    showNotification(data.message || 'Product added to cart successfully!', 'success');

                    // Reset button after 2 seconds
                    setTimeout(function () {
                        button.innerHTML = originalHTML;
                        button.classList.remove('btn-success');
                        button.classList.add('btn-primary');
                        button.disabled = false;
                    }, 2000);
                } else {
                    // Show error
                    showNotification(data.error || 'Failed to add product to cart', 'error');
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showNotification('An error occurred. Please try again.', 'error');
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
    }
});

/**
 * Update cart count in header
 */
function updateCartCount(count) {
    const cartCounts = document.querySelectorAll('.cart-count');
    cartCounts.forEach(element => {
        element.textContent = count || 0;
        // Add animation
        element.classList.add('cart-count-updated');
        setTimeout(() => element.classList.remove('cart-count-updated'), 300);
    });
}

/**
 * Show notification toast
 */
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existing = document.querySelector('.notification-toast');
    if (existing) {
        existing.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification-toast notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Show with animation
    setTimeout(() => notification.classList.add('show'), 10);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

/**
 * Get base URL for AJAX calls
 */
function getBaseUrl() {
    // Try to get from meta tag first
    const baseUrlMeta = document.querySelector('meta[name="base-url"]');
    if (baseUrlMeta) {
        return baseUrlMeta.content;
    }

    // Fallback: construct from current location
    const path = window.location.pathname;
    const parts = path.split('/').filter(p => p);

    // If we're in /host/mod/, use that as base
    if (parts.length >= 2 && parts[0] === 'host' && parts[1] === 'mod') {
        return '/host/mod';
    }

    // Otherwise use root
    return '';
}

/**
 * Form Validation
 */
const forms = document.querySelectorAll('form');
forms.forEach(function (form) {
    form.addEventListener('submit', function (e) {
        const inputs = form.querySelectorAll('[required]');
        let isValid = true;

        inputs.forEach(function (input) {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    });
});

/**
 * Smooth Scroll for Anchor Links
 */
document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href.length > 1) {
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

/**
 * Lazy Loading Images
 */
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(function (img) {
        imageObserver.observe(img);
    });
}

/**
 * User Menu Dropdown
 */
function initUserMenu() {
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (!userMenuBtn || !userDropdown) return;

    // Toggle dropdown on button click
    userMenuBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        userDropdown.classList.toggle('active');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('active');
        }
    });

    // Close dropdown when clicking a link
    userDropdown.querySelectorAll('.user-link').forEach(function (link) {
        link.addEventListener('click', function () {
            userDropdown.classList.remove('active');
        });
    });
}

/**
 * Mini Cart Functionality
 */

// Show/hide mini cart on hover and click
const cartIcon = document.getElementById('cartIconBtn');
const miniCartDropdown = document.getElementById('miniCartDropdown');
const cartWrapper = document.querySelector('.cart-icon-wrapper');

if (cartIcon && miniCartDropdown && cartWrapper) {
    let hoverTimeout;
    let isMobile = () => window.innerWidth <= 768;

    // Show on hover (desktop)
    cartIcon.addEventListener('mouseenter', function () {
        if (!isMobile()) {
            clearTimeout(hoverTimeout);
            cartIcon.classList.add('active');
        }
    });

    // Hide on mouse leave (with delay)
    cartWrapper.addEventListener('mouseleave', function () {
        if (!isMobile()) {
            hoverTimeout = setTimeout(() => {
                cartIcon.classList.remove('active');
            }, 300);
        }
    });

    // Toggle on click (works for both mobile and desktop)
    cartIcon.addEventListener('click', function (e) {
        if (isMobile()) {
            e.preventDefault();
            e.stopPropagation();
            cartIcon.classList.toggle('active');

            // Also toggle body overflow to prevent scrolling
            if (cartIcon.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    });

    // Close mini cart when clicking outside
    document.addEventListener('click', function (e) {
        if (!cartWrapper.contains(e.target)) {
            cartIcon.classList.remove('active');
            if (isMobile()) {
                document.body.style.overflow = '';
            }
        }
    });

    // Close on mini-cart close button (add if needed)
    const miniCartClose = miniCartDropdown.querySelector('.mini-cart-close');
    if (miniCartClose) {
        miniCartClose.addEventListener('click', function () {
            cartIcon.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
}

/**
 * Remove item from mini cart
 */
function removeFromMiniCart(productId) {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Add removing animation
    const itemElement = document.querySelector(`.mini-cart-item[data-product-id="${productId}"]`);
    if (itemElement) {
        itemElement.classList.add('removing');
    }

    // Make AJAX call to remove item
    fetch(getBaseUrl() + '/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&csrf_token=${csrfToken}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count
                updateCartCount(data.cart_count);

                // Reload page to update mini cart content
                // (In a full SPA, you'd update the mini cart HTML directly)
                setTimeout(() => {
                    location.reload();
                }, 300);
            } else {
                showNotification(data.error || 'Failed to remove item', 'error');
                if (itemElement) {
                    itemElement.classList.remove('removing');
                }
            }
        })
        .catch(error => {
            console.error('Error removing from cart:', error);
            showNotification('An error occurred. Please try again.', 'error');
            if (itemElement) {
                itemElement.classList.remove('removing');
            }
        });
}

/**
 * Update mini cart after adding item
 * Called after successful add to cart
 */
function refreshMiniCart() {
    // In a full implementation, you'd fetch updated mini cart HTML
    // For now, we'll just reload the page
    setTimeout(() => {
        location.reload();
    }, 1500);
}

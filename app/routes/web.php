<?php
/**
 * Web Routes
 */

// Homepage
$router->get('/', 'HomeController@index');

// Products
$router->get('/products', 'ProductController@index');
$router->get('/products/{slug}', 'ProductController@show');

// Our Products (Category-based)
$router->get('/our-products', 'OurProductsController@index');
$router->get('/our-products/{slug}', 'OurProductsController@category');

// Authentication
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/forgot-password', 'AuthController@showForgotPassword');
$router->post('/forgot-password', 'AuthController@forgotPassword');
$router->get('/reset-password', 'AuthController@showResetPassword');
$router->post('/reset-password', 'AuthController@resetPassword');

// Shopping Cart
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');
$router->post('/cart/clear', 'CartController@clear');
$router->get('/cart/count', 'CartController@count');
$router->get('/cart/mini', 'CartController@mini');

// Checkout
$router->get('/checkout', 'CheckoutController@index');
$router->post('/checkout/process', 'CheckoutController@process');
$router->get('/checkout/success/{orderNumber}', 'CheckoutController@success');

// Search
$router->get('/search', 'SearchController@index');
$router->get('/search/suggestions', 'SearchController@suggestions');
$router->get('/search/advanced', 'SearchController@advanced');

// User Account (Protected Routes)
$router->get('/account', 'AccountController@dashboard');
$router->get('/account/profile', 'AccountController@profile');
$router->post('/account/profile', 'AccountController@updateProfile');
$router->get('/account/orders', 'AccountController@orders');
$router->get('/account/orders/{orderNumber}', 'AccountController@orderDetails');
$router->post('/account/orders/cancel', 'AccountController@cancelOrder');
$router->get('/account/change-password', 'AccountController@changePassword');
$router->post('/account/change-password', 'AccountController@updatePassword');
$router->get('/account/addresses', 'AccountController@addresses');
$router->get('/account/wishlist', 'AccountController@wishlist');

// Support & Contact
$router->get('/contact', 'ContactController@index');
$router->post('/contact', 'ContactController@submit');
$router->get('/support', 'ContactController@support');

// Static Pages
$router->get('/about', 'PageController@about');
$router->get('/privacy', 'PageController@privacy');
$router->get('/terms', 'PageController@terms');

// Admin Routes
$router->get('/admin/login', 'admin/AdminAuthController@showLogin');
$router->post('/admin/login', 'admin/AdminAuthController@login');
$router->get('/admin/logout', 'admin/AdminAuthController@logout');

// Admin Dashboard (Protected)
$router->get('/admin', 'admin/AdminDashboardController@index');
$router->get('/admin/analytics', 'admin/AdminDashboardController@analytics');

// Admin Product Management
$router->get('/admin/products', 'admin/AdminProductController@index');
$router->get('/admin/products/create', 'admin/AdminProductController@create');
$router->post('/admin/products', 'admin/AdminProductController@store');
$router->get('/admin/products/{id}/edit', 'admin/AdminProductController@edit');
$router->post('/admin/products/{id}', 'admin/AdminProductController@update');
$router->post('/admin/products/{id}/delete', 'admin/AdminProductController@destroy');
$router->post('/admin/products/{id}/toggle-featured', 'admin/AdminProductController@toggleFeatured');
$router->post('/admin/products/{id}/toggle-bestseller', 'admin/AdminProductController@toggleBestSeller');

// Admin Order Management
$router->get('/admin/orders', 'admin/AdminOrderController@index');
$router->get('/admin/orders/{id}', 'admin/AdminOrderController@show');
$router->post('/admin/orders/{id}/status', 'admin/AdminOrderController@updateStatus');
$router->post('/admin/orders/{id}/payment', 'admin/AdminOrderController@updatePaymentStatus');
$router->get('/admin/orders/{id}/invoice', 'admin/AdminOrderController@invoice');
$router->get('/admin/orders/export', 'admin/AdminOrderController@export');

// Admin Banner Management
$router->get('/admin/banners', 'admin/AdminBannerController@index');
$router->get('/admin/banners/create', 'admin/AdminBannerController@create');
$router->post('/admin/banners/store', 'admin/AdminBannerController@store');
$router->get('/admin/banners/edit/{id}', 'admin/AdminBannerController@edit');
$router->post('/admin/banners/update/{id}', 'admin/AdminBannerController@update');
$router->get('/admin/banners/delete/{id}', 'admin/AdminBannerController@delete');
$router->post('/admin/banners/toggle-status/{id}', 'admin/AdminBannerController@toggleStatus');

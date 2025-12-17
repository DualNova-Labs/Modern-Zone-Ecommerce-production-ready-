<?php
/**
 * ADD THESE ROUTES TO app/routes/web.php
 * Insert them in the Admin Brands section (around line with other brand routes)
 */

// Get products in a brand subsection (for View Products modal)
$router->get('/admin/brands/{id}/subcategories/{subcatId}/products', 'AdminBrandController@getSubcategoryProducts');

// Remove product from subsection
$router->post('/admin/brands/{id}/subcategories/{subcatId}/products/{productId}/remove', 'AdminBrandController@removeProductFromSubcategory');

<?php
/**
 * ADD THESE TWO METHODS TO AdminBrandController.php
 * Insert them after the removeProduct() method (after line 661)
 */

/**
 * Get products in a brand subcategory (for View Products modal)
 */
public function getSubcategoryProducts($brandId, $subcategoryId)
{
    header('Content-Type: application/json');
    
    try {
        $db = Database::getInstance();
        $products = $db->select(
            "SELECT p.id, p.name, p.sku, p.price, p.quantity, p.status, p.image
             FROM products p
             WHERE p.brand_id = :brand_id AND p.brand_subcategory_id = :subcategory_id
             ORDER BY p.name",
            [
                'brand_id' => $brandId,
                'subcategory_id' => $subcategoryId
            ]
        );
        
        echo json_encode([
            'success' => true,
            'products' => $products
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to fetch products: ' . $e->getMessage()
        ]);
    }
    exit;
}

/**
 * Remove product from subcategory (unassign only, don't delete)
 */
public function removeProductFromSubcategory($brandId, $subcategoryId, $productId)
{
    header('Content-Type: application/json');
    
    // Validate CSRF token
    $submittedToken = $_POST['csrf_token'] ?? $_POST['_csrf_token'] ?? null;
    $sessionToken = $_SESSION['_csrf_token'] ?? null;
    
    if (!$submittedToken || !$sessionToken || !hash_equals($sessionToken, $submittedToken)) {
        echo json_encode(['success' => false, 'message' => 'Invalid security token']);
        exit;
    }
    
    try {
        $db = Database::getInstance();
        
        // Unassign product from brand and subcategory
        $db->update('products', [
            'brand_id' => null,
            'brand_subcategory_id' => null
        ], 'id = :id AND brand_id = :brand_id AND brand_subcategory_id = :subcategory_id', [
            'id' => $productId,
            'brand_id' => $brandId,
            'subcategory_id' => $subcategoryId
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Product removed from subcategory successfully'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to remove product: ' . $e->getMessage()
        ]);
    }
    exit;
}

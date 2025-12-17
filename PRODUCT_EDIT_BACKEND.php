<?php
/**
 * ADD THESE TWO METHODS TO AdminProductController.php
 * Insert them before the jsonResponse() method (before line 826)
 */

/**
 * Get product data for inline editing (AJAX endpoint)
 */
public function getData($id)
{
    header('Content-Type: application/json');
    
    try {
        $db = Database::getInstance();
        $product = $db->selectOne(
            "SELECT id, name, sku, price, quantity, status FROM products WHERE id = :id",
            ['id' => $id]
        );
        
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }
        
        echo json_encode(['success' => true, 'product' => $product]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

/**
 * Quick update product (for inline editing from brand subsections)
 */
public function quickUpdate($id)
{
    header('Content-Type: application/json');
    
    // Get JSON data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // Validate CSRF token
    if (!isset($data['csrf_token']) || !hash_equals($_SESSION['_csrf_token'] ?? '', $data['csrf_token'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid security token']);
        exit;
    }
    
    try {
        $db = Database::getInstance();
        
        // Check if product exists
        $existing = $db->selectOne("SELECT id FROM products WHERE id = :id", ['id' => $id]);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }
        
        // Check if SKU is unique (excluding current product)
        if (isset($data['sku'])) {
            $skuCheck = $db->selectOne(
                "SELECT id FROM products WHERE sku = :sku AND id != :id",
                ['sku' => $data['sku'], 'id' => $id]
            );
            if ($skuCheck) {
                echo json_encode(['success' => false, 'message' => 'SKU already exists']);
                exit;
            }
        }
        
        // Build update data
        $updateData = [
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if (isset($data['name'])) $updateData['name'] = $this->security->cleanInput($data['name']);
        if (isset($data['sku'])) $updateData['sku'] = $this->security->cleanInput($data['sku']);
        if (isset($data['price'])) $updateData['price'] = floatval($data['price']);
        if (isset($data['quantity'])) $updateData['quantity'] = intval($data['quantity']);
        if (isset($data['status'])) $updateData['status'] = $data['status'];
        
        // Update product
        $db->update('products', $updateData, 'id = :id', ['id' => $id]);
        
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message'] => 'Error updating product: ' . $e->getMessage()]);
    }
    exit;
}


/**
 * ALSO ADD THESE ROUTES TO app/routes/web.php
 * In the admin products section:
 */

// Get product data for inline editing
$router->get('/admin/products/{id}/data', 'admin/AdminProductController@getData');

// Quick update product
$router->post('/admin/products/{id}/quick-update', 'admin/AdminProductController@quickUpdate');

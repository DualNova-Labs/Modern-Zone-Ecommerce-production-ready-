<?php
/**
 * Admin Product Controller
 * Handles product CRUD operations
 */
require_once APP_PATH . '/middleware/AdminMiddleware.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/View.php';
require_once APP_PATH . '/core/Request.php';
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/models/Product.php';
require_once APP_PATH . '/models/Category.php';
require_once APP_PATH . '/models/Brand.php';

class AdminProductController
{
    private $security;

    public function __construct()
    {
        AdminMiddleware::check();
        $this->security = Security::getInstance();
    }

    /**
     * List all products
     */
    public function index()
    {
        $page = Request::get('page', 1);
        $search = Request::get('search', '');
        $category = Request::get('category');
        $status = Request::get('status');
        $perPage = 20;

        $db = Database::getInstance();

        // Build query
        // Exclude brand products (those with a brand_subcategory_id)
        $where = "(p.brand_subcategory_id IS NULL OR p.brand_subcategory_id = 0)";
        $params = [];

        if ($search) {
            $where .= " AND (p.name LIKE :search OR p.sku LIKE :sku)";
            $params['search'] = "%{$search}%";
            $params['sku'] = "%{$search}%";
        }

        if ($category) {
            $where .= " AND p.category_id = :category";
            $params['category'] = $category;
        }

        if ($status) {
            $where .= " AND p.status = :status";
            $params['status'] = $status;
        }

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM products p WHERE {$where}";
        $totalResult = $db->selectOne($countSql, $params);
        $total = $totalResult['total'] ?? 0;

        // Get products
        $offset = ($page - 1) * $perPage;
        $params['limit'] = $perPage;
        $params['offset'] = $offset;


        $products = $db->select(
            "SELECT p.*, c.name as category_name, b.name as brand_name
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN brands b ON p.brand_id = b.id
             WHERE {$where}
             ORDER BY p.created_at DESC
             LIMIT :limit OFFSET :offset",
            $params
        );

        // Get categories for filter
        $categories = Category::getActive();

        // Get brands for edit modal
        $brands = Brand::getActive();

        $data = [
            'title' => 'Manage Products - Admin',
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage)
            ],
            'filters' => [
                'search' => $search,
                'category' => $category,
                'status' => $status
            ],
            'admin_menu' => AdminMiddleware::getAdminMenu(),
            'active_menu' => 'products',
            'success' => $_SESSION['product_success'] ?? null,
            'error' => $_SESSION['product_error'] ?? null,
            'csrf_token' => $this->security->getCsrfToken()
        ];

        unset($_SESSION['product_success']);
        unset($_SESSION['product_error']);

        View::render('admin/products/index', $data);
    }

    /**
     * Show create product form - redirects to index with modal
     */
    public function create()
    {
        // Redirect to index page - product creation is handled via modal
        header('Location: ' . View::url('/admin/products'));
        exit;
    }

    /**
     * Store new product
     */
    public function store()
    {
        // DEBUG: Write to file to confirm this method is called
        file_put_contents(PUBLIC_PATH . '/store_debug.txt', date('Y-m-d H:i:s') . " - Store method called\n", FILE_APPEND);

        // DEBUG: Check CSRF tokens
        $csrfTokenName = '_csrf_token'; // Default name used by Security class
        $sessionToken = $_SESSION[$csrfTokenName] ?? 'NOT_SET';
        $postToken = $_POST['csrf_token'] ?? $_POST[$csrfTokenName] ?? 'NOT_SET';
        $sessionTime = $_SESSION[$csrfTokenName . '_time'] ?? 'NOT_SET';
        $currentTime = time();
        $isExpired = ($sessionTime !== 'NOT_SET' && ($currentTime - $sessionTime > 7200));

        $debugInfo = [
            'timestamp' => date('Y-m-d H:i:s'),
            'session_token' => $sessionToken,
            'post_token' => $postToken,
            'session_time' => $sessionTime,
            'current_time' => $currentTime,
            'is_expired' => $isExpired ? 'YES' : 'NO',
            'match' => ($sessionToken === $postToken) ? 'YES' : 'NO'
        ];
        file_put_contents(PUBLIC_PATH . '/store_debug.txt', print_r($debugInfo, true), FILE_APPEND);

        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['product_error'] = '<strong>CSRF Token Validation Failed</strong><br>' .
                'Session Token: ' . substr($sessionToken, 0, 10) . '...<br>' .
                'POST Token: ' . substr($postToken, 0, 10) . '...<br>' .
                'Match: ' . (($sessionToken === $postToken) ? 'YES' : 'NO') . '<br>' .
                'Expired: ' . ($isExpired ? 'YES' : 'NO');
            header('Location: ' . View::url('/admin/products'));
            exit;
        }

        // Get and validate input
        $data = $this->validateProductData();

        // DEBUG: Log what we received
        error_log("=== PRODUCT CREATE DEBUG ===");
        error_log("POST Data: " . print_r(Request::all(), true));
        error_log("Validation Errors: " . print_r($data['errors'], true));
        error_log("Product Data: " . print_r($data['product'], true));

        if (!empty($data['errors'])) {
            $_SESSION['product_errors'] = $data['errors'];
            $_SESSION['product_old'] = Request::all();

            // Show detailed error
            $errorDetails = "<strong>Validation Errors:</strong><br>";
            foreach ($data['errors'] as $field => $error) {
                $errorDetails .= "• <strong>" . ucfirst($field) . ":</strong> " . $error . "<br>";
            }
            $errorDetails .= "<br><strong>Received Data:</strong><br>";
            $errorDetails .= "• Name: " . Request::post('name') . "<br>";
            $errorDetails .= "• SKU: " . Request::post('sku') . "<br>";
            $errorDetails .= "• Category Type: " . Request::post('category_type') . "<br>";
            $errorDetails .= "• Price: " . Request::post('price') . "<br>";
            $errorDetails .= "• Quantity: " . Request::post('quantity') . "<br>";

            $_SESSION['product_error'] = $errorDetails;
            error_log("Validation failed, redirecting...");
            header('Location: ' . View::url('/admin/products'));
            exit;
        }

        $db = Database::getInstance();


        // Initialize image path
        $imagePath = null;

        // Handle main image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['name'])) {
            $uploadDir = PUBLIC_PATH . '/assets/images/products/';

            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Clean filename and add timestamp
            $originalName = preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['image']['name']));
            $fileName = time() . '_main_' . $originalName;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = 'public/assets/images/products/' . $fileName;
            }
        }

        // Build product data array for direct insert (bypass fill to ensure image is saved)
        $productData = [
            'category_id' => $data['product']['category_id'],
            'name' => $data['product']['name'],
            'sku' => $data['product']['sku'],
            'slug' => $this->generateSlug($data['product']['name']),
            'brand_id' => $data['product']['brand_id'],
            'description' => $data['product']['description'] ?? null,
            'specifications' => $data['product']['specifications'] ?? null,
            'price' => $data['product']['price'],
            'compare_price' => $data['product']['compare_price'],
            'cost' => $data['product']['cost'] ?? null,
            'quantity' => $data['product']['quantity'],
            'min_quantity' => $data['product']['min_quantity'] ?? 1,
            'weight' => $data['product']['weight'] ?? null,
            'image' => $imagePath,
            'featured' => $data['product']['featured'] ?? 0,
            'best_seller' => $data['product']['best_seller'] ?? 0,
            'new_arrival' => $data['product']['new_arrival'] ?? 0,
            'status' => $data['product']['status'] ?? 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Try to add category_type if the column exists
        try {
            $columnCheck = $db->selectOne("SHOW COLUMNS FROM products LIKE 'category_type'");
            if ($columnCheck) {
                $productData['category_type'] = $data['product']['category_type'] ?? 'general';
            }
        } catch (Exception $e) {
            // Column doesn't exist, skip it
        }

        try {
            // DEBUG: Log what we're about to insert
            error_log("Product data to insert: " . print_r($productData, true));

            // Insert product directly to ensure all fields are saved
            $productId = $db->insert('products', $productData);

            error_log("Insert result - Product ID: " . ($productId ? $productId : 'FAILED'));

            if ($productId) {
                // Handle additional images
                if (isset($_FILES['additional_images']) && is_array($_FILES['additional_images']['name'])) {
                    $uploadDir = PUBLIC_PATH . '/assets/images/products/';

                    for ($i = 0; $i < count($_FILES['additional_images']['name']); $i++) {
                        if (
                            isset($_FILES['additional_images']['error'][$i]) &&
                            $_FILES['additional_images']['error'][$i] === UPLOAD_ERR_OK &&
                            !empty($_FILES['additional_images']['name'][$i])
                        ) {

                            $originalName = preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['additional_images']['name'][$i]));
                            $fileName = time() . '_add' . $i . '_' . $originalName;
                            $targetPath = $uploadDir . $fileName;

                            if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$i], $targetPath)) {
                                // Insert into product_images table
                                $db->insert('product_images', [
                                    'product_id' => $productId,
                                    'image_path' => 'public/assets/images/products/' . $fileName,
                                    'alt_text' => $productData['name'] . ' - Image ' . ($i + 1),
                                    'is_primary' => 0,
                                    'sort_order' => $i + 1
                                ]);
                            }
                        }
                    }
                }

                $_SESSION['product_success'] = 'Product created successfully';
                header('Location: ' . View::url('/admin/products'));
            } else {
                $_SESSION['product_error'] = '<strong>Database Insert Failed</strong><br>The product ID returned was empty. Check database constraints and logs.';
                $_SESSION['product_old'] = Request::all();
                error_log("Product insert returned false/null");
                header('Location: ' . View::url('/admin/products'));
            }
        } catch (Exception $e) {
            $_SESSION['product_error'] = '<strong>Database Error:</strong><br>' . $e->getMessage() . '<br><br><strong>File:</strong> ' . $e->getFile() . '<br><strong>Line:</strong> ' . $e->getLine();
            $_SESSION['product_old'] = Request::all();
            error_log("Exception during product insert: " . $e->getMessage());
            header('Location: ' . View::url('/admin/products'));
        }
        exit;
    }

    /**
     * Get product data for editing (AJAX endpoint)
     */
    public function edit($id)
    {
        header('Content-Type: application/json');

        try {
            $db = Database::getInstance();
            $product = $db->selectOne(
                "SELECT * FROM products WHERE id = :id",
                ['id' => $id]
            );

            if (!$product) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Product not found'
                ]);
                exit;
            }

            // Get product images - wrap in try-catch in case table doesn't exist
            $images = [];
            try {
                $images = $db->select(
                    "SELECT * FROM product_images 
                     WHERE product_id = :product_id 
                     ORDER BY is_primary DESC, sort_order ASC",
                    ['product_id' => $id]
                );
            } catch (Exception $e) {
                // Table might not exist, ignore error
                $images = [];
            }

            // Get category type from the product directly
            $categoryType = $product['category_type'] ?? 'general';

            echo json_encode([
                'success' => true,
                'product' => $product,
                'images' => $images ?: [],
                'category_type' => $categoryType
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Update product
     */
    public function update($id)
    {
        // Detect if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Also check if request expects JSON
        if (!$isAjax && isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            $isAjax = true;
        }

        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid security token. Please refresh the page.']);
                exit;
            }
            $_SESSION['product_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/admin/products/' . $id . '/edit'));
            exit;
        }

        $db = Database::getInstance();

        $existingProduct = $db->selectOne("SELECT * FROM products WHERE id = :id", ['id' => $id]);

        if (!$existingProduct) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Product not found']);
                exit;
            }
            $_SESSION['product_error'] = 'Product not found';
            header('Location: ' . View::url('/admin/products'));
            exit;
        }

        // Get and validate input
        $data = $this->validateProductData(true, $existingProduct);

        if (!empty($data['errors'])) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => implode(', ', $data['errors']), 'errors' => $data['errors']]);
                exit;
            }
            $_SESSION['product_errors'] = $data['errors'];
            header('Location: ' . View::url('/admin/products/' . $id . '/edit'));
            exit;
        }

        // Handle image path - start with existing image
        $imagePath = $existingProduct['image'];

        // Handle new main image upload if provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['name'])) {
            $uploadDir = PUBLIC_PATH . '/assets/images/products/';

            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Clean filename and add timestamp
            $originalName = preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['image']['name']));
            $fileName = time() . '_main_' . $originalName;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                // Delete old image if exists
                if ($existingProduct['image'] && file_exists(ROOT_PATH . '/' . $existingProduct['image'])) {
                    @unlink(ROOT_PATH . '/' . $existingProduct['image']);
                }

                $imagePath = 'public/assets/images/products/' . $fileName;
            }
        }

        // Determine if slug needs update
        $slug = $existingProduct['slug'];
        if ($data['product']['name'] !== $existingProduct['name']) {
            $slug = $this->generateSlug($data['product']['name'], $id);
        }

        // Build update data
        $updateData = [
            'category_id' => $data['product']['category_id'],
            'name' => $data['product']['name'],
            'sku' => $data['product']['sku'],
            'slug' => $slug,
            'brand_id' => $data['product']['brand_id'],
            'description' => $data['product']['description'] ?? null,
            'specifications' => $data['product']['specifications'] ?? null,
            'price' => $data['product']['price'],
            'compare_price' => $data['product']['compare_price'],
            'cost' => $data['product']['cost'] ?? null,
            'quantity' => $data['product']['quantity'],
            'min_quantity' => $data['product']['min_quantity'] ?? 1,
            'weight' => $data['product']['weight'] ?? null,
            'image' => $imagePath,
            'featured' => $data['product']['featured'] ?? 0,
            'best_seller' => $data['product']['best_seller'] ?? 0,
            'new_arrival' => $data['product']['new_arrival'] ?? 0,
            'status' => $data['product']['status'] ?? 'active',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Try to update category_type if the column exists
        try {
            $columnCheck = $db->selectOne("SHOW COLUMNS FROM products LIKE 'category_type'");
            if ($columnCheck) {
                $updateData['category_type'] = $data['product']['category_type'] ?? 'general';
            }
        } catch (Exception $e) {
            // Column doesn't exist, skip it
        }

        try {
            // Update product directly
            $db->update('products', $updateData, 'id = :id', ['id' => $id]);

            // Handle additional images
            if (isset($_FILES['additional_images']) && is_array($_FILES['additional_images']['name'])) {
                $uploadDir = PUBLIC_PATH . '/assets/images/products/';

                // Get current max sort_order for this product
                $maxSort = $db->selectOne(
                    "SELECT MAX(sort_order) as max_sort FROM product_images WHERE product_id = :product_id",
                    ['product_id' => $id]
                );
                $sortOrder = ($maxSort['max_sort'] ?? 0) + 1;

                for ($i = 0; $i < count($_FILES['additional_images']['name']); $i++) {
                    if (
                        isset($_FILES['additional_images']['error'][$i]) &&
                        $_FILES['additional_images']['error'][$i] === UPLOAD_ERR_OK &&
                        !empty($_FILES['additional_images']['name'][$i])
                    ) {

                        $originalName = preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['additional_images']['name'][$i]));
                        $fileName = time() . '_add' . $i . '_' . $originalName;
                        $targetPath = $uploadDir . $fileName;

                        if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$i], $targetPath)) {
                            // Insert into product_images table
                            $db->insert('product_images', [
                                'product_id' => $id,
                                'image_path' => 'public/assets/images/products/' . $fileName,
                                'alt_text' => $data['product']['name'] . ' - Image ' . ($sortOrder),
                                'is_primary' => 0,
                                'sort_order' => $sortOrder
                            ]);
                            $sortOrder++;
                        }
                    }
                }
            }

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
                exit;
            }
            $_SESSION['product_success'] = 'Product updated successfully';
            header('Location: ' . View::url('/admin/products'));
        } catch (Exception $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Error updating product: ' . $e->getMessage()]);
                exit;
            }
            $_SESSION['product_error'] = 'Error updating product: ' . $e->getMessage();
            header('Location: ' . View::url('/admin/products/' . $id . '/edit'));
        }
        exit;
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token']);
            return;
        }

        $db = Database::getInstance();
        $product = $db->selectOne("SELECT * FROM products WHERE id = :id", ['id' => $id]);

        if (!$product) {
            $this->jsonResponse(['success' => false, 'error' => 'Product not found']);
            return;
        }

        // Check if product has orders (table might not exist yet)
        try {
            $orderCount = $db->selectOne(
                "SELECT COUNT(*) as count FROM order_items WHERE product_id = :id",
                ['id' => $id]
            );
            $hasOrders = $orderCount && $orderCount['count'] > 0;
        } catch (Exception $e) {
            // order_items table doesn't exist yet
            $hasOrders = false;
        }

        if ($hasOrders) {
            // Soft delete - just change status
            $updated = $db->query(
                "UPDATE products SET status = 'inactive' WHERE id = :id",
                ['id' => $id]
            );
            if ($updated) {
                $this->jsonResponse(['success' => true, 'message' => 'Product deactivated (has orders)']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to deactivate product']);
            }
        } else {
            // Hard delete - also delete product image if exists
            if ($product['image'] && file_exists(ROOT_PATH . '/' . $product['image'])) {
                unlink(ROOT_PATH . '/' . $product['image']);
            }

            $deleted = $db->query("DELETE FROM products WHERE id = :id", ['id' => $id]);
            if ($deleted) {
                $this->jsonResponse(['success' => true, 'message' => 'Product deleted successfully']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to delete product']);
            }
        }
    }

    /**
     * Validate product data
     */
    private function validateProductData($isUpdate = false, $existingProduct = null)
    {
        $errors = [];

        // Get input
        $name = $this->security->cleanInput(Request::post('name'));
        $sku = $this->security->cleanInput(Request::post('sku'));
        $categoryType = Request::post('category_type', 'general'); // Use category_type instead of category_id
        $categoryId = Request::post('category_id');
        $brandId = Request::post('brand_id');
        $price = Request::post('price');
        $comparePrice = Request::post('compare_price');
        $cost = Request::post('cost');
        $quantity = Request::post('quantity');
        $minQuantity = Request::post('min_quantity', 1);
        $weight = Request::post('weight');
        $description = $this->security->cleanInput(Request::post('description'));
        $specifications = $this->security->cleanInput(Request::post('specifications'));
        $status = Request::post('status', 'active');
        $featured = Request::post('featured') ? 1 : 0;
        $bestSeller = Request::post('best_seller') ? 1 : 0;
        $newArrival = Request::post('new_arrival') ? 1 : 0;

        // Validate required fields
        if (empty($name)) {
            $errors['name'] = 'Product name is required';
        }

        if (empty($sku)) {
            $errors['sku'] = 'SKU is required';
        } else {
            // Check SKU uniqueness
            $db = Database::getInstance();
            $where = $isUpdate ? "sku = :sku AND id != :id" : "sku = :sku";
            $params = ['sku' => $sku];
            if ($isUpdate) {
                $params['id'] = $existingProduct['id'] ?? Request::post('id');
            }

            $existing = $db->selectOne(
                "SELECT id FROM products WHERE {$where}",
                $params
            );

            if ($existing) {
                $errors['sku'] = 'SKU already exists';
            }
        }

        // Validate category_type (must be 'general' or 'our-products')
        if (empty($categoryType) || !in_array($categoryType, ['general', 'our-products'])) {
            $errors['category_type'] = 'Valid category type is required';
        }

        // Validate category_id (Skip for brand subsection products which have category_id = NULL)
        $isBrandProduct = ($existingProduct && !empty($existingProduct['brand_subcategory_id'])) ||
            ($isUpdate && Request::post('brand_subcategory_id'));

        if (empty($categoryId)) {
            if (!$isBrandProduct) {
                $errors['category_id'] = 'Category is required';
            }
        } else {
            try {
                $db = Database::getInstance();
                $categoryRow = $db->selectOne(
                    "SELECT id, type FROM categories WHERE id = :id AND status = 'active'",
                    ['id' => $categoryId]
                );

                if (!$categoryRow) {
                    $errors['category_id'] = 'Selected category is invalid or inactive';
                } else {
                    // If category has a type, update the product's category_type to match
                    // This ensures consistency when categories are selected from fallback
                    if (!empty($categoryRow['type'])) {
                        $categoryType = $categoryRow['type'];
                    }
                }
            } catch (Exception $e) {
                $errors['category_id'] = 'Failed to validate category';
            }
        }

        if (empty($price) || $price <= 0) {
            $errors['price'] = 'Valid price is required';
        }

        if ($quantity === null || $quantity < 0) {
            $errors['quantity'] = 'Valid quantity is required';
        }

        return [
            'errors' => $errors,
            'product' => [
                'name' => $name,
                'sku' => $sku,
                'category_type' => $categoryType,  // Use category_type instead of category_id
                'category_id' => $categoryId ? (int) $categoryId : null,
                'brand_id' => $brandId ?: null,
                'price' => $price,
                'compare_price' => $comparePrice ?: null,
                'cost' => $cost ?: null,
                'quantity' => $quantity,
                'min_quantity' => $minQuantity,
                'weight' => $weight ?: null,
                'description' => $description,
                'specifications' => $specifications,
                'status' => $status,
                'featured' => $featured,
                'best_seller' => $bestSeller,
                'new_arrival' => $newArrival
            ]
        ];
    }

    /**
     * Generate unique slug
     */
    private function generateSlug($name, $excludeId = null)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $originalSlug = $slug;
        $counter = 1;

        $db = Database::getInstance();

        while (true) {
            $where = $excludeId ? "slug = :slug AND id != :id" : "slug = :slug";
            $params = ['slug' => $slug];
            if ($excludeId) {
                $params['id'] = $excludeId;
            }

            $existing = $db->selectOne(
                "SELECT id FROM products WHERE {$where}",
                $params
            );

            if (!$existing) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token. Please refresh the page and try again.']);
            return;
        }

        $db = Database::getInstance();
        $product = $db->selectOne("SELECT * FROM products WHERE id = :id", ['id' => $id]);

        if (!$product) {
            $this->jsonResponse(['success' => false, 'error' => 'Product not found']);
            return;
        }

        // Toggle featured status
        $newStatus = !$product['featured'];
        $updated = $db->query(
            "UPDATE products SET featured = :featured WHERE id = :id",
            ['featured' => $newStatus ? 1 : 0, 'id' => $id]
        );

        if ($updated) {
            $this->jsonResponse([
                'success' => true,
                'featured' => $newStatus,
                'message' => $newStatus ? 'Added to Featured Products' : 'Removed from Featured Products'
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to update status']);
        }
    }

    /**
     * Toggle best seller status
     */
    public function toggleBestSeller($id)
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token. Please refresh the page and try again.']);
            return;
        }

        $db = Database::getInstance();
        $product = $db->selectOne("SELECT * FROM products WHERE id = :id", ['id' => $id]);

        if (!$product) {
            $this->jsonResponse(['success' => false, 'error' => 'Product not found']);
            return;
        }

        // Toggle best_seller status
        $newStatus = !$product['best_seller'];
        $updated = $db->query(
            "UPDATE products SET best_seller = :best_seller WHERE id = :id",
            ['best_seller' => $newStatus ? 1 : 0, 'id' => $id]
        );

        if ($updated) {
            $this->jsonResponse([
                'success' => true,
                'best_seller' => $newStatus,
                'message' => $newStatus ? 'Added to Best Sellers' : 'Removed from Best Sellers'
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to update status']);
        }
    }

    /**
     * Delete individual product image
     */
    public function deleteImage()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid security token']);
            return;
        }

        $imageId = Request::post('image_id');

        if (!$imageId) {
            $this->jsonResponse(['success' => false, 'error' => 'Image ID is required']);
            return;
        }

        $db = Database::getInstance();
        $image = $db->selectOne(
            "SELECT * FROM product_images WHERE id = :id",
            ['id' => $imageId]
        );

        if (!$image) {
            $this->jsonResponse(['success' => false, 'error' => 'Image not found']);
            return;
        }

        // Delete physical file
        if ($image['image_path'] && file_exists(ROOT_PATH . '/' . $image['image_path'])) {
            unlink(ROOT_PATH . '/' . $image['image_path']);
        }

        // Delete from database
        $deleted = $db->query(
            "DELETE FROM product_images WHERE id = :id",
            ['id' => $imageId]
        );

        if ($deleted) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Failed to delete image'
            ]);
        }
    }

    /**
     * Send JSON response
     */
    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

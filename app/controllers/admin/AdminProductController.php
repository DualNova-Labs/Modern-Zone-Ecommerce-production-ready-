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
        $where = "1=1";
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
        
        $data = [
            'title' => 'Manage Products - Admin',
            'products' => $products,
            'categories' => $categories,
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
     * Show create product form
     */
    public function create()
    {
        $categories = Category::getActive();
        $brands = Brand::getActive();
        
        $data = [
            'title' => 'Add New Product - Admin',
            'categories' => $categories,
            'brands' => $brands,
            'csrf_token' => $this->security->getCsrfToken(),
            'admin_menu' => AdminMiddleware::getAdminMenu(),
            'active_menu' => 'products',
            'errors' => $_SESSION['product_errors'] ?? [],
            'old' => $_SESSION['product_old'] ?? []
        ];
        
        unset($_SESSION['product_errors']);
        unset($_SESSION['product_old']);
        
        View::render('admin/products/create', $data);
    }
    
    /**
     * Store new product
     */
    public function store()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['product_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/admin/products/create'));
            exit;
        }
        
        // Get and validate input
        $data = $this->validateProductData();
        
        if (!empty($data['errors'])) {
            $_SESSION['product_errors'] = $data['errors'];
            $_SESSION['product_old'] = Request::all();
            header('Location: ' . View::url('/admin/products/create'));
            exit;
        }
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLIC_PATH . '/assets/images/products/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $data['product']['image'] = 'public/assets/images/products/' . $fileName;
            }
        }
        
        // Create product
        $product = new Product();
        $product->fill($data['product']);
        
        // Generate slug
        $product->slug = $this->generateSlug($product->name);
        
        if ($product->save()) {
            $_SESSION['product_success'] = 'Product created successfully';
            header('Location: ' . View::url('/admin/products'));
        } else {
            $_SESSION['product_error'] = 'Failed to create product';
            $_SESSION['product_old'] = Request::all();
            header('Location: ' . View::url('/admin/products/create'));
        }
        exit;
    }
    
    /**
     * Get product data for editing (AJAX endpoint)
     */
    public function edit($id)
    {
        header('Content-Type: application/json');
        
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
        
        echo json_encode([
            'success' => true,
            'product' => $product
        ]);
        exit;
    }
    
    /**
     * Update product
     */
    public function update($id)
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['product_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/admin/products/' . $id . '/edit'));
            exit;
        }
        
        $product = Product::find($id);
        
        if (!$product) {
            $_SESSION['product_error'] = 'Product not found';
            header('Location: ' . View::url('/admin/products'));
            exit;
        }
        
        // Get and validate input
        $data = $this->validateProductData(true);
        
        if (!empty($data['errors'])) {
            $_SESSION['product_errors'] = $data['errors'];
            header('Location: ' . View::url('/admin/products/' . $id . '/edit'));
            exit;
        }
        
        // Handle image upload if new image provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLIC_PATH . '/assets/images/products/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                // Delete old image if exists
                if ($product->image && file_exists(ROOT_PATH . '/' . $product->image)) {
                    unlink(ROOT_PATH . '/' . $product->image);
                }
                
                $data['product']['image'] = 'public/assets/images/products/' . $fileName;
            }
        } else {
            // Keep existing image
            $data['product']['image'] = $product->image;
        }
        
        // Update product
        $product->fill($data['product']);
        
        // Update slug if name changed
        if ($product->isDirty('name')) {
            $product->slug = $this->generateSlug($product->name, $product->id);
        }
        
        if ($product->save()) {
            $_SESSION['product_success'] = 'Product updated successfully';
            header('Location: ' . View::url('/admin/products'));
        } else {
            $_SESSION['product_error'] = 'Failed to update product';
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
    private function validateProductData($isUpdate = false)
    {
        $errors = [];
        
        // Get input
        $name = $this->security->cleanInput(Request::post('name'));
        $sku = $this->security->cleanInput(Request::post('sku'));
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
                $params['id'] = Request::post('id');
            }
            
            $existing = $db->selectOne(
                "SELECT id FROM products WHERE {$where}",
                $params
            );
            
            if ($existing) {
                $errors['sku'] = 'SKU already exists';
            }
        }
        
        if (empty($categoryId)) {
            $errors['category_id'] = 'Category is required';
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
                'category_id' => $categoryId,
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
     * Send JSON response
     */
    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

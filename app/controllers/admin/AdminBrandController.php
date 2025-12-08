<?php
/**
 * Admin Brand Controller
 * Handles brand CRUD operations
 */
require_once APP_PATH . '/middleware/AdminMiddleware.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/View.php';
require_once APP_PATH . '/core/Request.php';
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/models/Brand.php';

class AdminBrandController
{
    private $security;
    
    public function __construct()
    {
        AdminMiddleware::check();
        $this->security = Security::getInstance();
    }
    
    /**
     * List all brands
     */
    public function index()
    {
        $db = Database::getInstance();
        $brands = $db->select(
            "SELECT b.*, 
                    (SELECT COUNT(*) FROM products WHERE brand_id = b.id) as product_count
             FROM brands b
             ORDER BY b.name"
        );
        
        // Fetch subcategories for each brand
        $subcategoriesByBrand = [];
        $subcategories = $db->select(
            "SELECT bs.*, 
                    (SELECT COUNT(*) FROM products WHERE brand_subcategory_id = bs.id) as product_count
             FROM brand_subcategories bs
             ORDER BY bs.brand_id, bs.sort_order, bs.name"
        );
        
        foreach ($subcategories as $subcat) {
            $brandId = $subcat['brand_id'];
            if (!isset($subcategoriesByBrand[$brandId])) {
                $subcategoriesByBrand[$brandId] = [];
            }
            $subcategoriesByBrand[$brandId][] = $subcat;
        }
        
        // Get all products for assignment dropdown
        $allProducts = $db->select(
            "SELECT id, name, sku, brand_id, brand_subcategory_id FROM products ORDER BY name"
        );
        
        $data = [
            'title' => 'Brands Management',
            'brands' => $brands,
            'subcategoriesByBrand' => $subcategoriesByBrand,
            'allProducts' => $allProducts,
            'success' => $_SESSION['brand_success'] ?? null,
            'error' => $_SESSION['brand_error'] ?? null,
            'csrf_token' => $this->security->getCsrfToken(),
        ];
        
        // Clear flash messages
        unset($_SESSION['brand_success']);
        unset($_SESSION['brand_error']);
        
        View::render('admin/brands/index', $data);
    }
    
    /**
     * Show create brand form - redirects to index with modal
     */
    public function create()
    {
        // Redirect to index page - brand creation is handled via modal
        header('Location: ' . View::url('/admin/brands'));
        exit;
    }
    
    /**
     * Store new brand
     */
    public function store()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['brand_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
        
        // Get and sanitize input
        $data = [
            'name' => $this->security->cleanInput(Request::post('name')),
            'slug' => $this->security->cleanInput(Request::post('slug')),
            'description' => $this->security->cleanInput(Request::post('description')),
            'website' => $this->security->cleanInput(Request::post('website')),
            'country' => $this->security->cleanInput(Request::post('country')),
            'founded_year' => $this->security->cleanInput(Request::post('founded_year')),
            'about' => $this->security->cleanInput(Request::post('about')),
            'specialties' => $this->security->cleanInput(Request::post('specialties')),
            'sort_order' => (int)Request::post('sort_order', 0),
            'status' => Request::post('status', 'active'),
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Brand name is required';
        }
        
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        // Check if slug exists
        if (Brand::findBySlug($data['slug'])) {
            $errors['slug'] = 'Slug already exists';
        }
        
        if (!empty($errors)) {
            $_SESSION['brand_errors'] = $errors;
            $_SESSION['brand_old'] = $data;
            $_SESSION['brand_error'] = implode('<br>', $errors);
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
        
        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLIC_PATH . '/assets/images/brands/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $fileName = time() . '_' . uniqid() . '.' . $fileExt;
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
                $data['logo'] = 'public/assets/images/brands/' . $fileName;
            }
        }
        
        // Create brand
        try {
            $db = Database::getInstance();
            $db->insert('brands', $data);
            
            $_SESSION['brand_success'] = 'Brand created successfully!';
            header('Location: ' . View::url('/admin/brands'));
            exit;
        } catch (Exception $e) {
            $_SESSION['brand_error'] = 'Failed to create brand: ' . $e->getMessage();
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
    }
    
    /**
     * Show edit brand form
     */
    public function edit($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            $_SESSION['brand_error'] = 'Brand not found';
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
        
        $data = [
            'title' => 'Edit Brand',
            'brand' => $brand,
            'csrf_token' => $this->security->getCsrfToken(),
            'errors' => $_SESSION['brand_errors'] ?? [],
            'old' => $_SESSION['brand_old'] ?? [],
        ];
        
        // Clear flash data
        unset($_SESSION['brand_errors']);
        unset($_SESSION['brand_old']);
        
        View::render('admin/brands/edit', $data);
    }
    
    /**
     * Update brand
     */
    public function update($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            $_SESSION['brand_error'] = 'Brand not found';
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
        
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['brand_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
        
        // Get and sanitize input
        $data = [
            'name' => $this->security->cleanInput(Request::post('name')),
            'slug' => $this->security->cleanInput(Request::post('slug')),
            'description' => $this->security->cleanInput(Request::post('description')),
            'website' => $this->security->cleanInput(Request::post('website')),
            'country' => $this->security->cleanInput(Request::post('country')),
            'founded_year' => $this->security->cleanInput(Request::post('founded_year')),
            'about' => $this->security->cleanInput(Request::post('about')),
            'specialties' => $this->security->cleanInput(Request::post('specialties')),
            'sort_order' => (int)Request::post('sort_order', 0),
            'status' => Request::post('status', 'active'),
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Brand name is required';
        }
        
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        // Check if slug exists (excluding current brand)
        $existingBrand = Brand::findBySlug($data['slug']);
        if ($existingBrand && $existingBrand->id != $id) {
            $errors['slug'] = 'Slug already exists';
        }
        
        if (!empty($errors)) {
            $_SESSION['brand_errors'] = $errors;
            $_SESSION['brand_old'] = $data;
            header('Location: ' . View::url("/admin/brands/{$id}/edit"));
            exit;
        }
        
        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLIC_PATH . '/assets/images/brands/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $fileName = time() . '_' . uniqid() . '.' . $fileExt;
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
                // Delete old logo if exists
                if (!empty($brand->logo) && file_exists(ROOT_PATH . '/' . $brand->logo)) {
                    unlink(ROOT_PATH . '/' . $brand->logo);
                }
                $data['logo'] = 'public/assets/images/brands/' . $fileName;
            }
        } else {
            // Keep existing logo
            $data['logo'] = $brand->logo;
        }
        
        // Update brand
        try {
            $db = Database::getInstance();
            $db->update('brands', $data, 'id = :id', ['id' => $id]);
            
            $_SESSION['brand_success'] = 'Brand updated successfully!';
            header('Location: ' . View::url('/admin/brands'));
            exit;
        } catch (Exception $e) {
            $_SESSION['brand_error'] = 'Failed to update brand: ' . $e->getMessage();
            header('Location: ' . View::url("/admin/brands/{$id}/edit"));
            exit;
        }
    }
    
    /**
     * Delete brand
     */
    public function destroy($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            $_SESSION['brand_error'] = 'Brand not found';
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
        
        // Check if brand has products
        $productCount = $brand->getProductCount();
        if ($productCount > 0) {
            $_SESSION['brand_error'] = "Cannot delete brand. It has {$productCount} products.";
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
        
        try {
            $db = Database::getInstance();
            $db->delete('brands', 'id = :id', ['id' => $id]);
            
            $_SESSION['brand_success'] = 'Brand deleted successfully!';
        } catch (Exception $e) {
            $_SESSION['brand_error'] = 'Failed to delete brand: ' . $e->getMessage();
        }
        
        header('Location: ' . View::url('/admin/brands'));
        exit;
    }
    
    /**
     * Toggle brand status
     */
    public function toggleStatus($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            $_SESSION['brand_error'] = 'Brand not found';
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
        
        $newStatus = $brand->status === 'active' ? 'inactive' : 'active';
        
        try {
            $db = Database::getInstance();
            $db->update('brands', ['status' => $newStatus], 'id = :id', ['id' => $id]);
            
            $_SESSION['brand_success'] = 'Brand status updated successfully!';
        } catch (Exception $e) {
            $_SESSION['brand_error'] = 'Failed to update brand status: ' . $e->getMessage();
        }
        
        header('Location: ' . View::url('/admin/brands'));
        exit;
    }
    
    /**
     * Generate slug from name
     */
    private function generateSlug($name)
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
    
    /**
     * Get brand subcategories (API endpoint)
     */
    public function getSubcategories($brandId)
    {
        header('Content-Type: application/json');
        
        try {
            $db = Database::getInstance();
            $subcategories = $db->select(
                "SELECT id, name, slug, description, status 
                 FROM brand_subcategories 
                 WHERE brand_id = :brand_id AND status = 'active'
                 ORDER BY sort_order, name",
                ['brand_id' => $brandId]
            );
            
            echo json_encode([
                'success' => true,
                'subcategories' => $subcategories
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch subcategories: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    /**
     * Store brand subcategory
     */
    public function storeSubcategory($brandId)
    {
        header('Content-Type: application/json');
        
        // Validate CSRF token - check both token names for compatibility
        $submittedToken = $_POST['csrf_token'] ?? $_POST['_csrf_token'] ?? null;
        $sessionToken = $_SESSION['_csrf_token'] ?? null;
        
        if (!$submittedToken || !$sessionToken || !hash_equals($sessionToken, $submittedToken)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid security token'
            ]);
            exit;
        }
        
        $name = $this->security->cleanInput(Request::post('name'));
        
        if (empty($name)) {
            echo json_encode([
                'success' => false,
                'message' => 'Subcategory name is required'
            ]);
            exit;
        }
        
        $slug = $this->generateSlug($name);
        
        try {
            $db = Database::getInstance();
            
            // Check if slug exists for this brand
            $existing = $db->selectOne(
                "SELECT id FROM brand_subcategories WHERE brand_id = :brand_id AND slug = :slug",
                ['brand_id' => $brandId, 'slug' => $slug]
            );
            
            if ($existing) {
                echo json_encode([
                    'success' => true,
                    'subcategory' => $existing,
                    'message' => 'Subcategory already exists'
                ]);
                exit;
            }
            
            $subcategoryId = $db->insert('brand_subcategories', [
                'brand_id' => $brandId,
                'name' => $name,
                'slug' => $slug,
                'description' => $this->security->cleanInput(Request::post('description', '')),
                'status' => 'active',
                'sort_order' => 0
            ]);
            
            echo json_encode([
                'success' => true,
                'subcategory' => [
                    'id' => $subcategoryId,
                    'name' => $name,
                    'slug' => $slug
                ],
                'message' => 'Subcategory created successfully'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create subcategory: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    /**
     * Delete brand subcategory
     */
    public function deleteSubcategory($brandId, $subcategoryId)
    {
        header('Content-Type: application/json');
        
        // Validate CSRF token - check both token names for compatibility
        $submittedToken = $_POST['csrf_token'] ?? $_POST['_csrf_token'] ?? null;
        $sessionToken = $_SESSION['_csrf_token'] ?? null;
        
        if (!$submittedToken || !$sessionToken || !hash_equals($sessionToken, $submittedToken)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid security token'
            ]);
            exit;
        }
        
        try {
            $db = Database::getInstance();
            
            // Check if subcategory has products
            $productCount = $db->selectOne(
                "SELECT COUNT(*) as count FROM products WHERE brand_subcategory_id = :id",
                ['id' => $subcategoryId]
            );
            
            if ($productCount && $productCount['count'] > 0) {
                echo json_encode([
                    'success' => false,
                    'message' => "Cannot delete subcategory. It has {$productCount['count']} products."
                ]);
                exit;
            }
            
            $db->delete('brand_subcategories', 'id = :id AND brand_id = :brand_id', [
                'id' => $subcategoryId,
                'brand_id' => $brandId
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Subcategory deleted successfully'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete subcategory: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    /**
     * Update brand subcategory
     */
    public function updateSubcategory($brandId, $subcategoryId)
    {
        header('Content-Type: application/json');
        
        // Validate CSRF token - check both token names for compatibility
        $submittedToken = $_POST['csrf_token'] ?? $_POST['_csrf_token'] ?? null;
        $sessionToken = $_SESSION['_csrf_token'] ?? null;
        
        if (!$submittedToken || !$sessionToken || !hash_equals($sessionToken, $submittedToken)) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token']);
            exit;
        }
        
        $name = $this->security->cleanInput(Request::post('name'));
        
        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Subcategory name is required']);
            exit;
        }
        
        $slug = $this->generateSlug($name);
        
        try {
            $db = Database::getInstance();
            
            // Check if slug exists for another subcategory in this brand
            $existing = $db->selectOne(
                "SELECT id FROM brand_subcategories WHERE brand_id = :brand_id AND slug = :slug AND id != :id",
                ['brand_id' => $brandId, 'slug' => $slug, 'id' => $subcategoryId]
            );
            
            if ($existing) {
                echo json_encode(['success' => false, 'message' => 'A subcategory with this name already exists']);
                exit;
            }
            
            $db->update('brand_subcategories', [
                'name' => $name,
                'slug' => $slug,
                'description' => $this->security->cleanInput(Request::post('description', ''))
            ], 'id = :id AND brand_id = :brand_id', [
                'id' => $subcategoryId,
                'brand_id' => $brandId
            ]);
            
            echo json_encode([
                'success' => true,
                'subcategory' => ['id' => $subcategoryId, 'name' => $name, 'slug' => $slug],
                'message' => 'Subcategory updated successfully'
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to update subcategory: ' . $e->getMessage()]);
        }
        exit;
    }
    
    /**
     * Show brand details - redirects to index (subsections managed inline)
     */
    public function show($id)
    {
        // Subsection management is now inline on the brands index page
        header('Location: ' . View::url('/admin/brands'));
        exit;
    }
    
    /**
     * Assign product to brand with subcategory
     */
    public function assignProduct($brandId)
    {
        header('Content-Type: application/json');
        
        // Validate CSRF token - check both token names for compatibility
        $submittedToken = $_POST['csrf_token'] ?? $_POST['_csrf_token'] ?? null;
        $sessionToken = $_SESSION['_csrf_token'] ?? null;
        
        if (!$submittedToken || !$sessionToken || !hash_equals($sessionToken, $submittedToken)) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token']);
            exit;
        }
        
        $productId = Request::post('product_id');
        $subcategoryId = Request::post('subcategory_id');
        
        if (empty($productId)) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required']);
            exit;
        }
        
        try {
            $db = Database::getInstance();
            $db->update('products', [
                'brand_id' => $brandId,
                'brand_subcategory_id' => $subcategoryId ?: null
            ], 'id = :id', ['id' => $productId]);
            
            echo json_encode(['success' => true, 'message' => 'Product assigned to brand successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to assign product: ' . $e->getMessage()]);
        }
        exit;
    }
    
    /**
     * Remove product from brand
     */
    public function removeProduct($brandId, $productId)
    {
        header('Content-Type: application/json');
        
        // Validate CSRF token - check both token names for compatibility
        $submittedToken = $_POST['csrf_token'] ?? $_POST['_csrf_token'] ?? null;
        $sessionToken = $_SESSION['_csrf_token'] ?? null;
        
        if (!$submittedToken || !$sessionToken || !hash_equals($sessionToken, $submittedToken)) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token']);
            exit;
        }
        
        try {
            $db = Database::getInstance();
            $db->update('products', [
                'brand_id' => null,
                'brand_subcategory_id' => null
            ], 'id = :id AND brand_id = :brand_id', [
                'id' => $productId,
                'brand_id' => $brandId
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Product removed from brand']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to remove product: ' . $e->getMessage()]);
        }
        exit;
    }
    
    /**
     * Create new product directly from brand/subcategory page
     */
    public function createProduct()
    {
        // Validate CSRF token - check both token names for compatibility
        $submittedToken = $_POST['csrf_token'] ?? $_POST['_csrf_token'] ?? null;
        $sessionToken = $_SESSION['_csrf_token'] ?? null;
        
        if (!$submittedToken || !$sessionToken || !hash_equals($sessionToken, $submittedToken)) {
            $_SESSION['brand_error'] = 'Invalid security token. Please refresh the page and try again.';
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
        
        $db = Database::getInstance();
        
        // Get and sanitize input
        $data = [
            'name' => $this->security->cleanInput(Request::post('name')),
            'sku' => $this->security->cleanInput(Request::post('sku')),
            'description' => $this->security->cleanInput(Request::post('description')),
            'price' => floatval(Request::post('price', 0)),
            'compare_price' => floatval(Request::post('compare_price', 0)) ?: null,
            'quantity' => intval(Request::post('quantity', 0)),
            'min_quantity' => intval(Request::post('min_quantity', 1)),
            'category_id' => null, // Not used for brand subcategory products
            'brand_id' => Request::post('brand_id'),
            'brand_subcategory_id' => Request::post('brand_subcategory_id'),
            'status' => Request::post('status', 'active'),
            'featured' => Request::post('featured') ? 1 : 0,
            'best_seller' => Request::post('best_seller') ? 1 : 0,
            'new_arrival' => Request::post('new_arrival') ? 1 : 0,
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Product name is required';
        }
        
        if (empty($data['sku'])) {
            $errors[] = 'SKU is required';
        } else {
            // Check if SKU already exists
            $existing = $db->selectOne("SELECT id FROM products WHERE sku = :sku", ['sku' => $data['sku']]);
            if ($existing) {
                $errors[] = 'SKU already exists';
            }
        }
        
        if ($data['price'] <= 0) {
            $errors[] = 'Price must be greater than 0';
        }
        
        if (empty($data['brand_id'])) {
            $errors[] = 'Brand ID is required';
        }
        
        if (!empty($errors)) {
            $_SESSION['brand_error'] = implode('<br>', $errors);
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
        
        // Generate slug
        $data['slug'] = $this->generateSlug($data['name']);
        
        // Handle main image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLIC_PATH . '/assets/images/products/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $fileName = time() . '_' . uniqid() . '.' . $fileExt;
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $data['image'] = 'public/assets/images/products/' . $fileName;
            }
        }
        
        // Create product
        try {
            $productId = $db->insert('products', $data);
            
            // Handle additional images
            if (isset($_FILES['additional_images']) && !empty($_FILES['additional_images']['name'][0])) {
                $uploadDir = PUBLIC_PATH . '/assets/images/products/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $sortOrder = 1;
                foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmpName) {
                    if ($_FILES['additional_images']['error'][$key] === UPLOAD_ERR_OK) {
                        $fileExt = strtolower(pathinfo($_FILES['additional_images']['name'][$key], PATHINFO_EXTENSION));
                        $fileName = time() . '_' . uniqid() . '_' . $sortOrder . '.' . $fileExt;
                        $targetPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($tmpName, $targetPath)) {
                            $db->insert('product_images', [
                                'product_id' => $productId,
                                'image_path' => 'public/assets/images/products/' . $fileName,
                                'sort_order' => $sortOrder,
                                'alt_text' => $data['name'] . ' - Image ' . $sortOrder
                            ]);
                            $sortOrder++;
                        }
                    }
                }
            }
            
            $_SESSION['brand_success'] = 'Product "' . htmlspecialchars($data['name']) . '" created successfully!';
            header('Location: ' . View::url('/admin/brands'));
            exit;
        } catch (Exception $e) {
            $_SESSION['brand_error'] = 'Failed to create product: ' . $e->getMessage();
            header('Location: ' . View::url('/admin/brands'));
            exit;
        }
    }
}
?>

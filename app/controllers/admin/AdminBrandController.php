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
        
        $data = [
            'title' => 'Brands Management',
            'brands' => $brands,
            'success' => $_SESSION['brand_success'] ?? null,
            'error' => $_SESSION['brand_error'] ?? null,
        ];
        
        // Clear flash messages
        unset($_SESSION['brand_success']);
        unset($_SESSION['brand_error']);
        
        View::render('admin/brands/index', $data);
    }
    
    /**
     * Show create brand form
     */
    public function create()
    {
        $data = [
            'title' => 'Create Brand',
            'csrf_token' => $this->security->getCsrfToken(),
            'errors' => $_SESSION['brand_errors'] ?? [],
            'old' => $_SESSION['brand_old'] ?? [],
        ];
        
        // Clear flash data
        unset($_SESSION['brand_errors']);
        unset($_SESSION['brand_old']);
        
        View::render('admin/brands/create', $data);
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
            header('Location: ' . View::url('/admin/brands/create'));
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
            header('Location: ' . View::url('/admin/brands/create'));
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
                if (!empty($brand['logo']) && file_exists(ROOT_PATH . '/' . $brand['logo'])) {
                    unlink(ROOT_PATH . '/' . $brand['logo']);
                }
                $data['logo'] = 'public/assets/images/brands/' . $fileName;
            }
        } else {
            // Keep existing logo
            $data['logo'] = $brand['logo'];
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
}
?>

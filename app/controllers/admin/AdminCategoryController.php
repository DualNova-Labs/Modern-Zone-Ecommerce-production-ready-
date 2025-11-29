<?php
/**
 * Admin Category Controller
 * Handles category CRUD operations
 */
require_once APP_PATH . '/middleware/AdminMiddleware.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/View.php';
require_once APP_PATH . '/core/Request.php';
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/models/Category.php';

class AdminCategoryController
{
    private $security;
    
    public function __construct()
    {
        AdminMiddleware::check();
        $this->security = Security::getInstance();
    }
    
    /**
     * List all categories
     */
    public function index()
    {
        $db = Database::getInstance();
        $categories = $db->select(
            "SELECT c.*, 
                    p.name as parent_name,
                    (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count
             FROM categories c
             LEFT JOIN categories p ON c.parent_id = p.id
             ORDER BY c.sort_order, c.name"
        );
        
        $data = [
            'title' => 'Categories Management',
            'categories' => $categories,
            'success' => $_SESSION['category_success'] ?? null,
            'error' => $_SESSION['category_error'] ?? null,
        ];
        
        // Clear flash messages
        unset($_SESSION['category_success']);
        unset($_SESSION['category_error']);
        
        View::render('admin/categories/index', $data);
    }
    
    /**
     * Show create category form
     */
    public function create()
    {
        $db = Database::getInstance();
        $parentCategories = $db->select(
            "SELECT * FROM categories WHERE parent_id IS NULL AND status = 'active' ORDER BY name"
        );
        
        $data = [
            'title' => 'Create Category',
            'parentCategories' => $parentCategories,
            'csrf_token' => $this->security->getCsrfToken(),
            'errors' => $_SESSION['category_errors'] ?? [],
            'old' => $_SESSION['category_old'] ?? [],
        ];
        
        // Clear flash data
        unset($_SESSION['category_errors']);
        unset($_SESSION['category_old']);
        
        View::render('admin/categories/create', $data);
    }
    
    /**
     * Store new category
     */
    public function store()
    {
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['category_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }
        
        // Get and sanitize input
        $data = [
            'parent_id' => Request::post('parent_id') ?: null,
            'type' => Request::post('type', 'general'),
            'name' => $this->security->cleanInput(Request::post('name')),
            'slug' => $this->security->cleanInput(Request::post('slug')),
            'description' => $this->security->cleanInput(Request::post('description')),
            'icon' => $this->security->cleanInput(Request::post('icon')),
            'sort_order' => (int)Request::post('sort_order', 0),
            'status' => Request::post('status', 'active'),
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Category name is required';
        }
        
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        // Check if slug exists
        if (Category::findBySlug($data['slug'])) {
            $errors['slug'] = 'Slug already exists';
        }
        
        if (!empty($errors)) {
            $_SESSION['category_errors'] = $errors;
            $_SESSION['category_old'] = $data;
            header('Location: ' . View::url('/admin/categories/create'));
            exit;
        }
        
        // Create category
        try {
            $db = Database::getInstance();
            $db->insert('categories', $data);
            
            $_SESSION['category_success'] = 'Category created successfully!';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        } catch (Exception $e) {
            $_SESSION['category_error'] = 'Failed to create category: ' . $e->getMessage();
            header('Location: ' . View::url('/admin/categories/create'));
            exit;
        }
    }
    
    /**
     * Show edit category form
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if (!$category) {
            $_SESSION['category_error'] = 'Category not found';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }
        
        $db = Database::getInstance();
        $parentCategories = $db->select(
            "SELECT * FROM categories WHERE parent_id IS NULL AND id != :id AND status = 'active' ORDER BY name",
            ['id' => $id]
        );
        
        $data = [
            'title' => 'Edit Category',
            'category' => $category,
            'parentCategories' => $parentCategories,
            'csrf_token' => $this->security->getCsrfToken(),
            'errors' => $_SESSION['category_errors'] ?? [],
            'old' => $_SESSION['category_old'] ?? [],
        ];
        
        // Clear flash data
        unset($_SESSION['category_errors']);
        unset($_SESSION['category_old']);
        
        View::render('admin/categories/edit', $data);
    }
    
    /**
     * Update category
     */
    public function update($id)
    {
        $category = Category::find($id);
        if (!$category) {
            $_SESSION['category_error'] = 'Category not found';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }
        
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['category_error'] = 'Invalid security token. Please try again.';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }
        
        // Get and sanitize input
        $data = [
            'parent_id' => Request::post('parent_id') ?: null,
            'type' => Request::post('type', 'general'),
            'name' => $this->security->cleanInput(Request::post('name')),
            'slug' => $this->security->cleanInput(Request::post('slug')),
            'description' => $this->security->cleanInput(Request::post('description')),
            'icon' => $this->security->cleanInput(Request::post('icon')),
            'sort_order' => (int)Request::post('sort_order', 0),
            'status' => Request::post('status', 'active'),
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Category name is required';
        }
        
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        // Check if slug exists (excluding current category)
        $existingCategory = Category::findBySlug($data['slug']);
        if ($existingCategory && $existingCategory->id != $id) {
            $errors['slug'] = 'Slug already exists';
        }
        
        if (!empty($errors)) {
            $_SESSION['category_errors'] = $errors;
            $_SESSION['category_old'] = $data;
            header('Location: ' . View::url("/admin/categories/{$id}/edit"));
            exit;
        }
        
        // Update category
        try {
            $db = Database::getInstance();
            $db->update('categories', $data, 'id = :id', ['id' => $id]);
            
            $_SESSION['category_success'] = 'Category updated successfully!';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        } catch (Exception $e) {
            $_SESSION['category_error'] = 'Failed to update category: ' . $e->getMessage();
            header('Location: ' . View::url("/admin/categories/{$id}/edit"));
            exit;
        }
    }
    
    /**
     * Delete category
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            $_SESSION['category_error'] = 'Category not found';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }
        
        // Check if category has products
        $productCount = $category->getProductCount();
        if ($productCount > 0) {
            $_SESSION['category_error'] = "Cannot delete category. It has {$productCount} products.";
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }
        
        // Check if category has children
        $children = $category->children();
        if (!empty($children)) {
            $_SESSION['category_error'] = 'Cannot delete category. It has subcategories.';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }
        
        try {
            $db = Database::getInstance();
            $db->delete('categories', 'id = :id', ['id' => $id]);
            
            $_SESSION['category_success'] = 'Category deleted successfully!';
        } catch (Exception $e) {
            $_SESSION['category_error'] = 'Failed to delete category: ' . $e->getMessage();
        }
        
        header('Location: ' . View::url('/admin/categories'));
        exit;
    }
    
    /**
     * Toggle category status
     */
    public function toggleStatus($id)
    {
        $category = Category::find($id);
        if (!$category) {
            $_SESSION['category_error'] = 'Category not found';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }
        
        $newStatus = $category->status === 'active' ? 'inactive' : 'active';
        
        try {
            $db = Database::getInstance();
            $db->update('categories', ['status' => $newStatus], 'id = :id', ['id' => $id]);
            
            $_SESSION['category_success'] = 'Category status updated successfully!';
        } catch (Exception $e) {
            $_SESSION['category_error'] = 'Failed to update category status: ' . $e->getMessage();
        }
        
        header('Location: ' . View::url('/admin/categories'));
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

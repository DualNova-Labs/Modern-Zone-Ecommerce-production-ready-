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
     * Show create category form - redirects to index with modal
     */
    public function create()
    {
        // Redirect to index page - category creation is handled via modal
        header('Location: ' . View::url('/admin/categories'));
        exit;
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
            'sort_order' => (int) Request::post('sort_order', 0),
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
            $_SESSION['category_error'] = implode('<br>', $errors);
            header('Location: ' . View::url('/admin/categories'));
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
            header('Location: ' . View::url('/admin/categories'));
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
            'sort_order' => (int) Request::post('sort_order', 0),
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
        // Debug: Log to file
        error_log("DELETE CATEGORY: ID=$id, POST=" . json_encode($_POST) . ", HEADERS=" . json_encode(getallheaders()));

        $category = Category::find($id);
        if (!$category) {
            $_SESSION['category_error'] = 'Category not found';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }

        // Check if force delete is requested (via header or POST parameter)
        $forceDelete = (isset($_SERVER['HTTP_X_FORCE_DELETE']) && $_SERVER['HTTP_X_FORCE_DELETE'] === 'true')
            || (isset($_POST['force_delete']) && $_POST['force_delete'] === 'true');

        error_log("FORCE DELETE: " . ($forceDelete ? 'YES' : 'NO'));

        // Check if category has products
        $productCount = $category->getProductCount();
        if ($productCount > 0 && !$forceDelete) {
            $_SESSION['category_error'] = "Cannot delete category. It has {$productCount} products.";
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }

        // Check if category has children
        $children = $category->children();
        if (!empty($children) && !$forceDelete) {
            $_SESSION['category_error'] = 'Cannot delete category. It has subcategories.';
            header('Location: ' . View::url('/admin/categories'));
            exit;
        }

        try {
            $db = Database::getInstance();

            // If force delete, also delete associated products and update children
            if ($forceDelete) {
                // First get all product IDs in this category (ALL products, not just active)
                $products = $db->select(
                    "SELECT id FROM products WHERE category_id = :category_id",
                    ['category_id' => $id]
                );

                error_log("Found " . count($products) . " products to delete");

                // Delete product images first (foreign key constraint)
                foreach ($products as $product) {
                    error_log("Deleting images for product ID: " . $product['id']);
                    $db->delete('product_images', 'product_id = :product_id', ['product_id' => $product['id']]);
                }

                // Delete ALL products in this category (not just based on productCount)
                if (count($products) > 0) {
                    error_log("Deleting all products in category");
                    $db->delete('products', 'category_id = :category_id', ['category_id' => $id]);
                }

                // Update child categories to have no parent
                if (!empty($children)) {
                    $db->update('categories', ['parent_id' => null], 'parent_id = :parent_id', ['parent_id' => $id]);
                }
            }

            // Delete the category
            error_log("Attempting to delete category ID: $id");
            $result = $db->delete('categories', 'id = :id', ['id' => $id]);
            error_log("Delete result: " . json_encode($result));

            if ($forceDelete) {
                $_SESSION['category_success'] = 'Category and all associated data deleted successfully!';
            } else {
                $_SESSION['category_success'] = 'Category deleted successfully!';
            }
        } catch (Exception $e) {
            error_log("Delete exception: " . $e->getMessage());
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

    /**
     * API: Get subcategories by parent type (general or our-products)
     */
    public function getSubcategoriesByType()
    {
        header('Content-Type: application/json');

        $type = Request::get('type', 'general');

        try {
            $db = Database::getInstance();

            // First try to get categories by type
            $categories = $db->select(
                "SELECT id, name, slug, type, description 
                 FROM categories 
                 WHERE type = :type AND status = 'active'
                 ORDER BY sort_order, name",
                ['type' => $type]
            );

            // If no categories found for this type, get all active categories as fallback
            if (empty($categories)) {
                $categories = $db->select(
                    "SELECT id, name, slug, type, description 
                     FROM categories 
                     WHERE status = 'active'
                     ORDER BY type, sort_order, name"
                );
            }

            echo json_encode([
                'success' => true,
                'categories' => $categories,
                'type_queried' => $type
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch categories: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * API: Create subcategory inline (for product form)
     */
    public function createSubcategoryInline()
    {
        header('Content-Type: application/json');

        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid security token. Please refresh the page.'
            ]);
            exit;
        }

        $name = $this->security->cleanInput(Request::post('name'));
        $type = Request::post('type', 'general');

        if (empty($name)) {
            echo json_encode([
                'success' => false,
                'message' => 'Category name is required'
            ]);
            exit;
        }

        // Generate slug
        $slug = $this->generateSlug($name);

        // Check if slug exists
        $existingCategory = Category::findBySlug($slug);
        if ($existingCategory) {
            // Return the existing category instead of error
            echo json_encode([
                'success' => true,
                'category' => [
                    'id' => $existingCategory->id,
                    'name' => $existingCategory->name,
                    'slug' => $existingCategory->slug,
                    'type' => $existingCategory->type
                ],
                'message' => 'Category already exists, using existing one'
            ]);
            exit;
        }

        try {
            $db = Database::getInstance();
            $categoryId = $db->insert('categories', [
                'name' => $name,
                'slug' => $slug,
                'type' => $type,
                'parent_id' => null,
                'status' => 'active',
                'sort_order' => 0
            ]);

            echo json_encode([
                'success' => true,
                'category' => [
                    'id' => $categoryId,
                    'name' => $name,
                    'slug' => $slug,
                    'type' => $type
                ],
                'message' => 'Category created successfully'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create category: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}
?>
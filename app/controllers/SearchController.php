<?php
/**
 * Search Controller
 * Handles product search and filtering
 */
require_once APP_PATH . '/models/Product.php';
require_once APP_PATH . '/models/Category.php';
require_once APP_PATH . '/models/Brand.php';

class SearchController
{
    /**
     * Search page with filters
     */
    public function index()
    {
        // Get search parameters
        $query = Request::get('q', '');
        $category = Request::get('category');
        $brand = Request::get('brand');
        $minPrice = Request::get('min_price');
        $maxPrice = Request::get('max_price');
        $inStock = Request::get('in_stock');
        $featured = Request::get('featured');
        $sort = Request::get('sort', 'relevance');
        $page = Request::get('page', 1);
        
        // Build filters array
        $filters = [
            'category' => $category,
            'brand' => $brand,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'in_stock' => $inStock,
            'featured' => $featured,
            'sort' => $sort,
            'page' => $page,
            'per_page' => 12
        ];
        
        // Search products
        $results = Product::search($query, $filters);
        
        // Get categories and brands for filters
        $categories = $this->getCategories();
        $brands = $this->getBrands();
        
        // Get price range
        $priceRange = $this->getPriceRange();
        
        $data = [
            'title' => $query ? "Search Results for '{$query}'" : 'All Products',
            'description' => 'Browse our complete range of industrial tools and equipment',
            'query' => $query,
            'filters' => $filters,
            'results' => $results,
            'categories' => $categories,
            'brands' => $brands,
            'price_range' => $priceRange,
            'sort_options' => $this->getSortOptions()
        ];
        
        View::render('pages/search/index', $data);
    }
    
    /**
     * AJAX search suggestions
     */
    public function suggestions()
    {
        $query = Request::get('q', '');
        
        if (strlen($query) < 2) {
            $this->jsonResponse([]);
            return;
        }
        
        $db = Database::getInstance();
        
        // Get product suggestions
        $products = $db->select(
            "SELECT id, name, slug, price,
                    (SELECT image_path FROM product_images WHERE product_id = products.id AND is_primary = 1 LIMIT 1) as image
             FROM products
             WHERE status = 'active' 
             AND (name LIKE :query OR sku LIKE :sku)
             ORDER BY views DESC
             LIMIT 5",
            ['query' => "%{$query}%", 'sku' => "{$query}%"]
        );
        
        // Get category suggestions
        $categories = $db->select(
            "SELECT id, name, slug
             FROM categories
             WHERE status = 'active' 
             AND name LIKE :query
             LIMIT 3",
            ['query' => "%{$query}%"]
        );
        
        $suggestions = [
            'products' => $products,
            'categories' => $categories
        ];
        
        $this->jsonResponse($suggestions);
    }
    
    /**
     * Advanced search page
     */
    public function advanced()
    {
        $categories = $this->getCategories();
        $brands = $this->getBrands();
        $priceRange = $this->getPriceRange();
        
        $data = [
            'title' => 'Advanced Search - Modern Zone Trading',
            'description' => 'Find exactly what you need with our advanced search',
            'categories' => $categories,
            'brands' => $brands,
            'price_range' => $priceRange
        ];
        
        View::render('pages/search/advanced', $data);
    }
    
    /**
     * Get categories for filter
     */
    private function getCategories()
    {
        $db = Database::getInstance();
        return $db->select(
            "SELECT c.*, COUNT(p.id) as product_count
             FROM categories c
             LEFT JOIN products p ON c.id = p.category_id AND p.status = 'active'
             WHERE c.status = 'active'
             GROUP BY c.id
             ORDER BY c.sort_order, c.name"
        );
    }
    
    /**
     * Get brands for filter
     */
    private function getBrands()
    {
        $db = Database::getInstance();
        return $db->select(
            "SELECT b.*, COUNT(p.id) as product_count
             FROM brands b
             LEFT JOIN products p ON b.id = p.brand_id AND p.status = 'active'
             WHERE b.status = 'active'
             GROUP BY b.id
             HAVING product_count > 0
             ORDER BY b.name"
        );
    }
    
    /**
     * Get price range
     */
    private function getPriceRange()
    {
        $db = Database::getInstance();
        $range = $db->selectOne(
            "SELECT MIN(price) as min_price, MAX(price) as max_price
             FROM products
             WHERE status = 'active'"
        );
        
        return [
            'min' => floor($range['min_price'] ?? 0),
            'max' => ceil($range['max_price'] ?? 1000)
        ];
    }
    
    /**
     * Get sort options
     */
    private function getSortOptions()
    {
        return [
            'relevance' => 'Most Relevant',
            'name_asc' => 'Name (A-Z)',
            'name_desc' => 'Name (Z-A)',
            'price_asc' => 'Price (Low to High)',
            'price_desc' => 'Price (High to Low)',
            'newest' => 'Newest First',
            'rating' => 'Highest Rated'
        ];
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

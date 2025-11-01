<?php
/**
 * Product Model
 */
require_once APP_PATH . '/core/Model.php';

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'category_id', 'brand_id', 'sku', 'name', 'slug', 'description',
        'specifications', 'price', 'compare_price', 'cost', 'quantity',
        'min_quantity', 'weight', 'image', 'featured', 'best_seller', 'new_arrival',
        'status', 'views', 'meta_title', 'meta_description', 'meta_keywords'
    ];
    
    /**
     * Get product by slug
     */
    public static function findBySlug($slug)
    {
        $db = Database::getInstance();
        $product = $db->selectOne(
            "SELECT p.*, c.name as category_name, c.slug as category_slug,
                    b.name as brand_name, b.slug as brand_slug
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN brands b ON p.brand_id = b.id
             WHERE p.slug = :slug AND p.status = 'active'",
            ['slug' => $slug]
        );
        
        if ($product) {
            $instance = new self();
            $instance->attributes = $product;
            $instance->exists = true;
            return $instance;
        }
        
        return null;
    }
    
    /**
     * Get product images
     */
    public function getImages()
    {
        return $this->db->select(
            "SELECT * FROM product_images 
             WHERE product_id = :product_id 
             ORDER BY is_primary DESC, sort_order ASC",
            ['product_id' => $this->id]
        );
    }
    
    /**
     * Get primary image
     */
    public function getPrimaryImage()
    {
        $image = $this->db->selectOne(
            "SELECT * FROM product_images 
             WHERE product_id = :product_id AND is_primary = 1 
             LIMIT 1",
            ['product_id' => $this->id]
        );
        
        return $image ? $image['image_path'] : '/assets/images/no-image.png';
    }
    
    /**
     * Get category
     */
    public function category()
    {
        return $this->belongsTo('Category', 'category_id');
    }
    
    /**
     * Get brand
     */
    public function brand()
    {
        return $this->belongsTo('Brand', 'brand_id');
    }
    
    /**
     * Get reviews
     */
    public function reviews()
    {
        return $this->hasMany('ProductReview', 'product_id');
    }
    
    /**
     * Get average rating
     */
    public function getAverageRating()
    {
        $result = $this->db->selectOne(
            "SELECT AVG(rating) as avg_rating, COUNT(*) as review_count 
             FROM product_reviews 
             WHERE product_id = :product_id AND status = 'approved'",
            ['product_id' => $this->id]
        );
        
        return [
            'rating' => round($result['avg_rating'] ?? 0, 1),
            'count' => $result['review_count'] ?? 0
        ];
    }
    
    /**
     * Check if product is in stock
     */
    public function inStock()
    {
        return $this->quantity > 0 && $this->status === 'active';
    }
    
    /**
     * Get discount percentage
     */
    public function getDiscountPercentage()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }
    
    /**
     * Increment views
     */
    public function incrementViews()
    {
        $this->db->query(
            "UPDATE {$this->table} SET views = views + 1 WHERE id = :id",
            ['id' => $this->id]
        );
    }
    
    /**
     * Update stock quantity
     */
    public function updateStock($quantity, $operation = 'decrease')
    {
        if ($operation === 'decrease') {
            if ($this->quantity < $quantity) {
                return false;
            }
            $newQuantity = $this->quantity - $quantity;
        } else {
            $newQuantity = $this->quantity + $quantity;
        }
        
        $this->quantity = $newQuantity;
        $this->status = $newQuantity > 0 ? 'active' : 'out_of_stock';
        
        return $this->save();
    }
    
    /**
     * Get related products
     */
    public function getRelatedProducts($limit = 4)
    {
        return $this->db->select(
            "SELECT p.*, 
                    p.image as image
             FROM products p
             WHERE p.category_id = :category_id 
             AND p.id != :product_id 
             AND p.status = 'active'
             ORDER BY p.views DESC, p.created_at DESC
             LIMIT :limit",
            [
                'category_id' => $this->category_id,
                'product_id' => $this->id,
                'limit' => $limit
            ]
        );
    }
    
    /**
     * Get featured products
     */
    public static function getFeaturedProducts($limit = 8)
    {
        $db = Database::getInstance();
        
        return $db->select(
            "SELECT p.*, 
                    c.name as category_name, 
                    b.name as brand_name,
                    p.image as image
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN brands b ON p.brand_id = b.id
             WHERE p.featured = 1 
             AND p.status = 'active'
             ORDER BY p.created_at DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
    }
    
    /**
     * Get best selling products
     */
    public static function getBestSellingProducts($limit = 8)
    {
        $db = Database::getInstance();
        
        return $db->select(
            "SELECT p.*, 
                    c.name as category_name, 
                    b.name as brand_name,
                    p.image as image
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN brands b ON p.brand_id = b.id
             WHERE p.best_seller = 1 
             AND p.status = 'active'
             ORDER BY p.views DESC, p.created_at DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
    }
    
    /**
     * Search products
     */
    public static function search($query, $filters = [])
    {
        $db = Database::getInstance();
        
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name,
                       p.image as image,
                       (SELECT AVG(rating) FROM product_reviews WHERE product_id = p.id AND status = 'approved') as avg_rating
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.status = 'active'";
        
        $params = [];
        
        // Search query
        if (!empty($query)) {
            $sql .= " AND (p.name LIKE :query OR p.description LIKE :query OR p.sku LIKE :sku)";
            $params['query'] = "%{$query}%";
            $params['sku'] = $query;
        }
        
        // Category filter
        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $filters['category'];
        }
        
        // Brand filter
        if (!empty($filters['brand'])) {
            $sql .= " AND p.brand_id = :brand_id";
            $params['brand_id'] = $filters['brand'];
        }
        
        // Price range filter
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }
        
        // In stock filter
        if (!empty($filters['in_stock'])) {
            $sql .= " AND p.quantity > 0";
        }
        
        // Featured filter
        if (!empty($filters['featured'])) {
            $sql .= " AND p.featured = 1";
        }
        
        // Sorting
        $sortOptions = [
            'relevance' => 'p.views DESC',
            'name_asc' => 'p.name ASC',
            'name_desc' => 'p.name DESC',
            'price_asc' => 'p.price ASC',
            'price_desc' => 'p.price DESC',
            'newest' => 'p.created_at DESC',
            'rating' => 'avg_rating DESC'
        ];
        
        $sort = $filters['sort'] ?? 'relevance';
        $orderBy = $sortOptions[$sort] ?? $sortOptions['relevance'];
        $sql .= " ORDER BY {$orderBy}";
        
        // Pagination
        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? 12;
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        $countSql = str_replace(
            'SELECT p.*, c.name as category_name, b.name as brand_name,
                       (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image,
                       (SELECT AVG(rating) FROM product_reviews WHERE product_id = p.id AND status = \'approved\') as avg_rating',
            'SELECT COUNT(*) as total',
            $sql
        );
        $countSql = preg_replace('/ORDER BY.*$/', '', $countSql);
        
        $countResult = $db->selectOne($countSql, $params);
        $total = $countResult['total'] ?? 0;
        
        // Add pagination to main query
        $sql .= " LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;
        
        // Get products
        $products = $db->select($sql, $params);
        
        return [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }
}

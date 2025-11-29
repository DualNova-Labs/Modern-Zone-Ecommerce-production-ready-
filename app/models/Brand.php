<?php
/**
 * Brand Model
 */
require_once APP_PATH . '/core/Model.php';

class Brand extends Model
{
    protected $table = 'brands';
    protected $fillable = [
        'name', 'slug', 'logo', 'description', 'website', 'status'
    ];
    
    /**
     * Get brand by slug
     */
    public static function findBySlug($slug)
    {
        return self::findBy('slug', $slug);
    }
    
    /**
     * Get active brands
     */
    public static function getActive()
    {
        $db = Database::getInstance();
        return $db->select(
            "SELECT * FROM brands 
             WHERE status = 'active' 
             ORDER BY name"
        );
    }
    
    /**
     * Get products for brand
     */
    public function products($limit = null)
    {
        $sql = "SELECT p.*, 
                       (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                FROM products p
                WHERE p.brand_id = :brand_id AND p.status = 'active'
                ORDER BY p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $params = ['brand_id' => $this->id];
        if ($limit) {
            $params['limit'] = $limit;
        }
        
        return $this->db->select($sql, $params);
    }
    
    /**
     * Get product count
     */
    public function getProductCount()
    {
        $result = $this->db->selectOne(
            "SELECT COUNT(*) as count 
             FROM products 
             WHERE brand_id = :brand_id AND status = 'active'",
            ['brand_id' => $this->id]
        );
        
        return $result['count'] ?? 0;
    }
    
    /**
     * Get brands with products
     */
    public static function getWithProducts()
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
     * Get categories for this brand from the pivot table
     */
    public function getCategories()
    {
        // First try to get from pivot table
        $pivotCategories = $this->db->select(
            "SELECT DISTINCT c.* 
             FROM categories c 
             JOIN brand_subcategory bs ON bs.subcategory_id = c.id 
             WHERE bs.brand_id = :brand_id 
             AND c.status = 'active'
             ORDER BY c.name",
            ['brand_id' => $this->id]
        );
        
        // If no pivot data, fall back to categories with products
        if (empty($pivotCategories)) {
            return $this->db->select(
                "SELECT DISTINCT c.* 
                 FROM categories c 
                 JOIN products p ON p.category_id = c.id 
                 WHERE p.brand_id = :brand_id 
                 AND p.status = 'active' 
                 AND c.status = 'active'
                 ORDER BY c.name",
                ['brand_id' => $this->id]
            );
        }
        
        return $pivotCategories;
    }
    
    /**
     * Get subcategories assigned to this brand
     */
    public function getSubcategories()
    {
        return $this->db->select(
            "SELECT c.* 
             FROM categories c 
             JOIN brand_subcategory bs ON bs.subcategory_id = c.id 
             WHERE bs.brand_id = :brand_id 
             AND c.status = 'active' 
             AND c.parent_id IS NOT NULL
             ORDER BY c.name",
            ['brand_id' => $this->id]
        );
    }
    
    /**
     * Get subcategory IDs for this brand
     */
    public function getSubcategoryIds()
    {
        $subcategories = $this->db->select(
            "SELECT subcategory_id FROM brand_subcategory WHERE brand_id = :brand_id",
            ['brand_id' => $this->id]
        );
        
        return array_column($subcategories, 'subcategory_id');
    }
    
    /**
     * Sync subcategories for this brand
     * @param array $subcategoryIds Array of category IDs to link to this brand
     */
    public function syncSubcategories($subcategoryIds)
    {
        // Delete existing relationships
        $this->db->query(
            'DELETE FROM brand_subcategory WHERE brand_id = :brand_id',
            ['brand_id' => $this->id]
        );
        
        // Insert new relationships
        if (!empty($subcategoryIds)) {
            foreach ($subcategoryIds as $subcategoryId) {
                $this->db->insert('brand_subcategory', [
                    'brand_id' => $this->id,
                    'subcategory_id' => $subcategoryId
                ]);
            }
        }
        
        return true;
    }
}

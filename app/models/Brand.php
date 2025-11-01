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
}

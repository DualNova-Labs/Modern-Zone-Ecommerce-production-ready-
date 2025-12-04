<?php
/**
 * Category Model
 */
require_once APP_PATH . '/core/Model.php';

class Category extends Model
{
    public $id;
    public $name;
    protected $table = 'categories';
    protected $fillable = [
        'parent_id', 'type', 'name', 'slug', 'description', 
        'image', 'icon', 'sort_order', 'status'
    ];
    
    /**
     * Get category by slug
     */
    public static function findBySlug($slug)
    {
        return self::findBy('slug', $slug);
    }
    
    /**
     * Get active categories
     */
    public static function getActive()
    {
        $db = Database::getInstance();
        return $db->select(
            "SELECT * FROM categories 
             WHERE status = 'active' 
             ORDER BY sort_order, name"
        );
    }
    
    /**
     * Get active categories by type
     */
    public static function getByType($type = 'general')
    {
        $db = Database::getInstance();
        return $db->select(
            "SELECT * FROM categories 
             WHERE status = 'active' AND type = :type
             ORDER BY sort_order, name",
            ['type' => $type]
        );
    }
    
    /**
     * Get parent category
     */
    public function parent()
    {
        if ($this->parent_id) {
            return self::find($this->parent_id);
        }
        return null;
    }
    
    /**
     * Get child categories
     */
    public function children()
    {
        return $this->db->select(
            "SELECT * FROM {$this->table} 
             WHERE parent_id = :parent_id AND status = 'active'
             ORDER BY sort_order, name",
            ['parent_id' => $this->id]
        );
    }
    
    /**
     * Get products in category
     */
    public function products($limit = null)
    {
        $sql = "SELECT p.*, 
                       (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                FROM products p
                WHERE p.category_id = :category_id AND p.status = 'active'
                ORDER BY p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $params = ['category_id' => $this->id];
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
             WHERE category_id = :category_id AND status = 'active'",
            ['category_id' => $this->id]
        );
        
        return $result['count'] ?? 0;
    }
    
    /**
     * Get category tree
     */
    public static function getTree()
    {
        $db = Database::getInstance();
        $categories = $db->select(
            "SELECT * FROM categories 
             WHERE status = 'active' 
             ORDER BY sort_order, name"
        );
        
        return self::buildTree($categories);
    }
    
    /**
     * Build category tree from flat array
     */
    private static function buildTree($categories, $parentId = null)
    {
        $tree = [];
        
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $children = self::buildTree($categories, $category['id']);
                if ($children) {
                    $category['children'] = $children;
                }
                $tree[] = $category;
            }
        }
        
        return $tree;
    }
}

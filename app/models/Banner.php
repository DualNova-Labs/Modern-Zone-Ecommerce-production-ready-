<?php
/**
 * Banner Model
 * Manages hero slider banners for the homepage
 */
require_once APP_PATH . '/core/Database.php';

class Banner
{
    private $db;
    private $table = 'banners';
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get all active banners ordered by sort_order
     */
    public function getActiveBanners()
    {
        $query = "SELECT * FROM {$this->table} 
                  WHERE status = 'active' 
                  ORDER BY sort_order ASC, created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all banners (for admin)
     */
    public function getAllBanners($limit = null, $offset = 0)
    {
        $query = "SELECT * FROM {$this->table} 
                  ORDER BY sort_order ASC, created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($query);
        
        if ($limit) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get banner by ID
     */
    public function getBannerById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new banner
     */
    public function createBanner($data)
    {
        $query = "INSERT INTO {$this->table} 
                  (title, subtitle, badge, image, link_url, link_text, sort_order, status) 
                  VALUES 
                  (:title, :subtitle, :badge, :image, :link_url, :link_text, :sort_order, :status)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':subtitle', $data['subtitle']);
        $stmt->bindParam(':badge', $data['badge']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':link_url', $data['link_url']);
        $stmt->bindParam(':link_text', $data['link_text']);
        $stmt->bindParam(':sort_order', $data['sort_order'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update banner
     */
    public function updateBanner($id, $data)
    {
        $query = "UPDATE {$this->table} 
                  SET title = :title,
                      subtitle = :subtitle,
                      badge = :badge,
                      image = :image,
                      link_url = :link_url,
                      link_text = :link_text,
                      sort_order = :sort_order,
                      status = :status
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':subtitle', $data['subtitle']);
        $stmt->bindParam(':badge', $data['badge']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':link_url', $data['link_url']);
        $stmt->bindParam(':link_text', $data['link_text']);
        $stmt->bindParam(':sort_order', $data['sort_order'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status']);
        
        return $stmt->execute();
    }
    
    /**
     * Delete banner
     */
    public function deleteBanner($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Update banner status
     */
    public function updateStatus($id, $status)
    {
        $query = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status);
        
        return $stmt->execute();
    }
    
    /**
     * Count all banners
     */
    public function countBanners()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}

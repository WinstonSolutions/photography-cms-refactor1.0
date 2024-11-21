<?php
class Gallery {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 获取最新图片
    public function getLatestImages($limit = 12) {
        $limit = (int)$limit;
        $sql = "SELECT * FROM images ORDER BY created_at DESC LIMIT $limit";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 获取单个图片详情
    public function getImage($id) {
        $sql = "SELECT * FROM images WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // 添加新图片
    public function addImage($title, $description, $image_path, $thumbnail_path, $user_id) {
        $sql = "INSERT INTO images (title, description, image_path, thumbnail_path, user_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$title, $description, $image_path, $thumbnail_path, $user_id]);
    }
    
    // 获取所有图片
    public function getAllImages() {
        $sql = "SELECT * FROM images ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 删除图片
    public function deleteImage($image_id) {
        // 先获取图片信息以删除文件
        $sql = "SELECT image_path, thumbnail_path FROM images WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$image_id]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 删除物理文件
        if ($image) {
            @unlink($image['image_path']);
            @unlink($image['thumbnail_path']);
        }
        
        // 从数据库中删除记录
        $sql = "DELETE FROM images WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$image_id]);
    }
    
    // 获取图片总数
    public function getTotalImages() {
        $sql = "SELECT COUNT(*) FROM images";
        return $this->db->query($sql)->fetchColumn();
    }
    
    // 获取分页图片
    public function getPaginatedImages($page, $per_page) {
        $offset = ($page - 1) * $per_page;
        $sql = "SELECT i.*, u.username 
                FROM images i 
                JOIN users u ON i.user_id = u.id 
                ORDER BY i.created_at DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$per_page, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 
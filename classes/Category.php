<?php
require_once __DIR__ . '/Database.php';

class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO albums (name, description) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['name'], $data['description']]);
    }
    
    public function update($id, $data) {
        $sql = "UPDATE albums SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }
    
    public function getAllCategories() {
        try {
            $sql = "SELECT a.*, COUNT(i.id) as posts_count 
                    FROM albums a 
                    LEFT JOIN images i ON a.id = i.category 
                    GROUP BY a.id 
                    ORDER BY a.name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Category::getAllCategories Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getCategory($id) {
        $sql = "SELECT * FROM albums WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function exists($id) {
        $query = "SELECT COUNT(*) FROM albums WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0; // 返回是否存在
    }
} 
<?php
require_once __DIR__ . '/Database.php';

class Album {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $name = $data['name'];
        $description = $data['description'];
        $userId = $data['user_id'];

        $sql = "INSERT INTO albums (name, description, user_id) VALUES ('$name', '$description', '$userId')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
    
    public function update($id, $data) {
        $sql = "UPDATE albums SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }
    
    public function getAllAlbums() {
        try {
            $sql = "SELECT a.*, COUNT(ai.image_id) as posts_count, u.username, a.created_at 
                    FROM albums a 
                    LEFT JOIN album_images ai ON a.id = ai.album_id 
                    LEFT JOIN users u ON a.user_id = u.id 
                    GROUP BY a.id 
                    ORDER BY a.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Album::getAllAlbums Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getAlbum($id) {
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
        return $stmt->fetchColumn() > 0;
    }
    
    public function getCategory($id) {
        $sql = "SELECT * FROM albums WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAlbumsCount() {
        $query = "SELECT COUNT(*) as count FROM albums";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];    
    }
} 
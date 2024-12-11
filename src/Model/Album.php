<?php
namespace Src\Model;

use Src\Core\Database;
use PDO;
use PDOException;

require_once __DIR__ . '/Image.php';

class Album {
    private $db;
    private $config;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->config = require __DIR__ . '/../../config/config.php';
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

    public function deleteAlbum($id) {
        $sql = "DELETE FROM albums WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAlbumById($id) {
        $sql = "SELECT * FROM albums WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function deleteAlbumWithImages($albumId) {
        // 获取专辑中的所有图片
        $images = $this->getImagesByAlbumId($albumId);

        // 删除所有图片
        foreach ($images as $image) {
            if ($this->deleteImage($image['image_id'])) {
                error_log("Successfully deleted image ID: " . $image['id']);
            } else {
                error_log("Failed to delete image ID: " . $image['id']);
            }
        }

        // 删除专辑
        return $this->deleteAlbum($albumId);
    }

    public function getImagesByAlbumId($albumId) {
        $sql = "SELECT * FROM album_images WHERE album_id = :album_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':album_id', $albumId);
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 调试信息
        error_log("Images for album ID $albumId: " . print_r($images, true));
        
        return $images;
    }

    public function deleteImage($imageId) {
        $image = new Image(); // 创建 Image 类的实例
        return $image->deleteImage($imageId); // 调用 Image 类中的 deleteImage 方法
    }
} 
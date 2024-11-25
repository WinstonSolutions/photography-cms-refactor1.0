<?php
require_once __DIR__ . '/Database.php';

class Image {
    private $db;
    private $config;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->config = require __DIR__ . '/../config/config.php';
    }
    
    // 处理图片上传
    public function upload($file, $album_id, $user_id) {
        // 检查文件类型
        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_type, $this->config['allowed_image_types'])) {
            throw new Exception('Unsupported file type');
        }
        
        // 检查文件大小
        if ($file['size'] > $this->config['max_file_size']) {
            throw new Exception('File too large');
        }
        
        // 生成唯一文件名
        $filename = uniqid() . '.' . $file_type;
        $upload_path = $this->config['upload_path'] . $filename;
        
        // 移动上传的文件
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception('File upload failed');
        }
        
        // 保存到数据库
        $query = "INSERT INTO images (file_path, album_id, user_id, created_at) VALUES (:file_path, :album_id, :user_id, NOW())";
        // print_r($query);
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':file_path', $upload_path);
        $stmt->bindParam(':album_id', $album_id);
        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception('Failed to save image information to database');
        }
        
        return true; // 上传成功
    }
} 
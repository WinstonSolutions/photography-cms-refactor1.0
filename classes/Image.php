<?php
class Image {
    private $db;
    private $config;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->config = require_once 'config/config.php';
    }
    
    // 处理图片上传
    public function upload($file, $title, $description, $user_id) {
        // 检查文件类型
        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_type, $this->config['allowed_image_types'])) {
            throw new Exception('不支持的文件类型');
        }
        
        // 检查文件大小
        if ($file['size'] > $this->config['max_file_size']) {
            throw new Exception('文件太大');
        }
        
        // 生成唯一文件名
        $filename = uniqid() . '.' . $file_type;
        $upload_path = $this->config['upload_path'] . $filename;
        $thumbnail_path = $this->config['upload_path'] . 'thumb_' . $filename;
        
        // 移动上传的文件
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception('文件上传失败');
        }
        
        // 创建缩略图
        create_thumbnail(
            $upload_path,
            $thumbnail_path,
            $this->config['thumbnail_width'],
            $this->config['thumbnail_height']
        );
        
        // 保存到数据库
        $gallery = new Gallery();
        return $gallery->addImage($title, $description, $upload_path, $thumbnail_path, $user_id);
    }
} 
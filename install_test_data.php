<?php
require_once 'classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // 插入测试图片数据
    $test_images = [
        [
            'title' => '日落风景',
            'description' => '美丽的日落景色',
            'image_path' => 'public/uploads/sunset.jpg',
            'thumbnail_path' => 'public/uploads/thumb_sunset.jpg',
            'user_id' => 1,
            'category' => 'landscape'
        ],
        [
            'title' => '城市夜景',
            'description' => '繁华的城市夜景',
            'image_path' => 'public/uploads/city.jpg',
            'thumbnail_path' => 'public/uploads/thumb_city.jpg',
            'user_id' => 1,
            'category' => 'landscape'
        ],
        [
            'title' => '人像写真',
            'description' => '优雅的人像摄影',
            'image_path' => 'public/uploads/portrait.jpg',
            'thumbnail_path' => 'public/uploads/thumb_portrait.jpg',
            'user_id' => 1,
            'category' => 'portrait'
        ]
    ];
    
    // 准备SQL语句
    $sql = "INSERT INTO images (title, description, image_path, thumbnail_path, user_id, category) 
            VALUES (:title, :description, :image_path, :thumbnail_path, :user_id, :category)";
    $stmt = $db->prepare($sql);
    
    // 插入测试数据
    foreach ($test_images as $image) {
        $stmt->execute($image);
    }
    
    // 创建示例图片文件
    $sample_image = imagecreatetruecolor(800, 600);
    $sample_thumb = imagecreatetruecolor(300, 200);
    
    // 确保上传目录存在
    if (!file_exists('public/uploads')) {
        mkdir('public/uploads', 0777, true);
    }
    
    // 为每个示例图片创建一些简单的图形
    foreach (['sunset', 'city', 'portrait'] as $name) {
        // 创建原图
        $sample_image = imagecreatetruecolor(800, 600);
        // 生成随机颜色
        $bg_color = imagecolorallocate($sample_image, rand(0, 255), rand(0, 255), rand(0, 255));
        imagefill($sample_image, 0, 0, $bg_color);
        // 添加一些图形
        $shape_color = imagecolorallocate($sample_image, rand(0, 255), rand(0, 255), rand(0, 255));
        imagefilledellipse($sample_image, 400, 300, 200, 200, $shape_color);
        
        // 创建缩略图
        $sample_thumb = imagecreatetruecolor(300, 200);
        imagecopyresampled($sample_thumb, $sample_image, 0, 0, 0, 0, 300, 200, 800, 600);
        
        // 保存图片
        imagejpeg($sample_image, "public/uploads/{$name}.jpg", 90);
        imagejpeg($sample_thumb, "public/uploads/thumb_{$name}.jpg", 80);
        
        // 释放内存
        imagedestroy($sample_image);
        imagedestroy($sample_thumb);
    }
    
    echo "测试数据添加成功！示例图片已创建在 public/uploads 目录下。";
    
} catch(PDOException $e) {
    die("数据库错误: " . $e->getMessage());
} catch(Exception $e) {
    die("发生错误: " . $e->getMessage());
} 
<?php


$config = [
    
    'upload_path' => __DIR__ . '/../uploads/', // 文件上传路径
    'allowed_image_types' => ['jpg', 'jpeg', 'png'], // 允许的文件类型
    'max_file_size' => 10 * 1024 * 1024, // 最大文件大小（5MB） 
    'thumbnail_width' => 150,          // 缩略图宽度
    'thumbnail_height' => 150          // 缩略图高度
];

define('BASE_URL', 'http://localhost/WebDevelopment2/photography-cms-refactor1.0/'); // 例如: http://localhost/photography-cms

return $config;


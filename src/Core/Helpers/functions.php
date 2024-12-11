<?php
namespace Core\Helpers;

// 清理输入数据
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// 生成缩略图
function create_thumbnail($source_path, $target_path, $width, $height) {
    $source_image = imagecreatefromjpeg($source_path);
    $source_width = imagesx($source_image);
    $source_height = imagesy($source_image);
    
    $target_image = imagecreatetruecolor($width, $height);
    imagecopyresampled($target_image, $source_image, 0, 0, 0, 0, 
                      $width, $height, $source_width, $source_height);
    
    imagejpeg($target_image, $target_path, 80);
    imagedestroy($source_image);
    imagedestroy($target_image);
}

// 检查用户是否登录
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// 检查是否是管理员
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
} 
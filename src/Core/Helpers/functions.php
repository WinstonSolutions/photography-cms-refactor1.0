<?php
// 移除命名空间，让这些函数在全局范围内可用
// namespace App\Core\Helpers;

// 防止函数重复声明
if (!function_exists('clean_input')) {
    /**
     * 清理输入数据
     * @param string $data
     * @return string
     */
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

if (!function_exists('is_logged_in')) {
    /**
     * 检查用户是否登录
     * @return bool
     */
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('is_admin')) {
    /**
     * 检查是否是管理员
     * @return bool
     */
    function is_admin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}

if (!function_exists('create_thumbnail')) {
    /**
     * 生成缩略图
     */
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
}



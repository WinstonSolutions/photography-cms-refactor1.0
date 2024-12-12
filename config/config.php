<?php

// 只在常量未定义时定义它
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/WebDevelopment2/photography-cms-refactor1.0/');
}

return [
    // 数据库配置
    'db_host' => 'localhost',
    'db_name' => 'photography_cms',
    'db_user' => 'root',     // 你的数据库用户名
    'db_pass' => '',         // 你的数据库密码

    // 其他配置
    'upload_path' => __DIR__ . '/../storage/uploads/',
    'allowed_image_types' => ['jpg', 'jpeg', 'png', 'gif'],
    'max_file_size' => 5242880, // 5MB in bytes
    'host' => 'localhost',
    'base_url' => BASE_URL,  // 将常量也作为配置项提供
];


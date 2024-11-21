<?php
require_once 'classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // 创建用户表
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // 创建图片表
    $db->exec("CREATE TABLE IF NOT EXISTS images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        image_path VARCHAR(255) NOT NULL,
        thumbnail_path VARCHAR(255) NOT NULL,
        user_id INT NOT NULL,
        category VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    
    // 创建管理员账户
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $db->exec("INSERT INTO users (username, email, password, role) 
               VALUES ('admin', 'admin@example.com', '$admin_password', 'admin')");
    
    echo "数据库表创建成功！<br>";
    echo "管理员账户：<br>";
    echo "邮箱: admin@example.com<br>";
    echo "密码: admin123";
    
} catch(PDOException $e) {
    die("数据库错误: " . $e->getMessage());
} 
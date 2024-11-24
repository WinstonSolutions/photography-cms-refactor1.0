<?php

require_once __DIR__ . '/../config/config.php'; // 确保配置文件中有数据库连接信息
require_once __DIR__ . '/Database.php';

class User {
    private $db;
    
    public function __construct() { //use the singleton pattern to create a database instance
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 用户注册
    public function register($username, $email, $password_hash) {
        $query = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
        
        // 使用 prepare 方法准备 SQL 语句
        $stmt = $this->db->prepare($query);
        
        // 绑定参数
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $password_hash);
        
        // 执行查询
        return $stmt->execute();
    }
    
    // 用户登录
    public function login($email, $password_hash) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password_hash, $user['password_hash'])) {
            return $user;
        }
        return false;
    }
    
    // 获取所有用户
    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 删除用户
    public function deleteUser($user_id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user_id]);
    }
} 
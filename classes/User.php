<?php

require_once __DIR__ . '/../config/config.php'; // 确保配置文件中有数据库连接信息
require_once __DIR__ . '/../classes/Database.php'; // 确保路径正确

class User {
    private $db;
    
    public function __construct() {
        $this->db = new Database(); // 实例化 Database 类
    }
    
    // 用户注册
    public function register($username, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $this->db->query($query);
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $hashed_password);
        
        return $this->db->execute();
    }
    
    // 用户登录
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
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
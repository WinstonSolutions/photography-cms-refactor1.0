<?php
namespace App\Model;

use App\Core\Database;
use PDO;

class User {
    private $db;
    private $config;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->config = include dirname(__DIR__, 2) . '/config/config.php';
    }
    
    // 用户注册
    public function register($username, $email, $password) {
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        
        // 使用 prepare 方法准备 SQL 语句
        $stmt = $this->db->prepare($query);
        
        // 绑定参数
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        
        // 执行查询
        return $stmt->execute();
    }
    
    // 用户登录
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 调试信息：检查���询结果
        if (!$user) {
            echo "No user found with this email.";
            return false;
        }
        
        // 直接比较明文密码
        if ($password !== $user['password']) {
            echo "Password does not match.";
            return false;
        }
        
        return $user;
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
    
    public function getUsernameById($id) {
        $sql = "SELECT username FROM users WHERE id = :id"; // 假设用户表名为 users
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn(); // 返回用户名
    }
    public function getUsersCount() {
        $query = "SELECT COUNT(*) as count FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];    
    }

} 
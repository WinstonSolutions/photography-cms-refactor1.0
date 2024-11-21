<?php
class Database {
    private $connection;
    private static $instance = null;
    
    // 私有构造函数，防止直接创建对象
    private function __construct() {
        $config = require_once(__DIR__ . '/../config/database.php');
        try {
            $this->connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
                $config['username'],
                $config['password']
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("连接失败: " . $e->getMessage());
        }
    }
    
    // 获取数据库实例（单例模式）
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // 获取数据库连接
    public function getConnection() {
        return $this->connection;
    }
} 
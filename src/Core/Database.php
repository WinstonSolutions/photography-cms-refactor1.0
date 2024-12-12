<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            // 使用 dirname() 函数更安全地获取配置文件
            $config = include dirname(__DIR__, 2) . '/config/database.php';
            
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            
            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            // 记录错误日志而不是直接输出
            error_log("Database Connection Error: " . $e->getMessage());
            throw new PDOException("数据库连接失败");
        }
    }
    
    // 防止克隆
    private function __clone() {}
    
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
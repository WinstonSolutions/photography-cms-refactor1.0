<?php
namespace Src\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;
    private static $config = null;
    
    private function __construct() {
        // 只在配置未加载时加载配置
        if (self::$config === null) {
            self::$config = require __DIR__ . '/../../config/config.php';
        }
        
        // 检查配置是否正确加载
        if (!is_array(self::$config)) {
            throw new PDOException('Database configuration not found or invalid');
        }
        
        // 检查必要的配置项是否存在
        if (!isset(self::$config['db_host']) || !isset(self::$config['db_name']) || 
            !isset(self::$config['db_user']) || !isset(self::$config['db_pass'])) {
            throw new PDOException('Missing required database configuration');
        }
        
        try {
            $dsn = "mysql:host=" . self::$config['db_host'] . 
                   ";dbname=" . self::$config['db_name'] . 
                   ";charset=utf8";
            
            $this->connection = new PDO(
                $dsn,
                self::$config['db_user'],
                self::$config['db_pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
} 
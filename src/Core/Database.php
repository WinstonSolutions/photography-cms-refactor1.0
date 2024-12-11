<?php
class Database {
    private $connection;
    private static $instance = null;
    
    // Private constructor to prevent direct creation
    private function __construct() {
        $config = require __DIR__ . '/../../config/database.php';
        
        if (!is_array($config)) {
            throw new Exception('Database configuration file must return an array');
        }

        try {
            $this->connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
                $config['username'],
                $config['password']
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    // Get database instance (Singleton pattern)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Get database connection
    public function getConnection() {
        return $this->connection;
    }
} 
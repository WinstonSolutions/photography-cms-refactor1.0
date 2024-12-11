<?php
require_once __DIR__ . '/../src/Core/Database.php';

try {
    $db = \Src\Core\Database::getInstance();
    $connection = $db->getConnection();
    echo "Database connection successful!";
    
    // 测试查询
    $stmt = $connection->query("SELECT NOW()");
    $result = $stmt->fetch();
    echo "<br>Current time from database: " . $result[0];
    
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
} 
<?php
session_start();
require_once '../includes/functions.php';

// 检查是否登录且是管理员
if (!is_logged_in() || !is_admin()) {
    header('Location: /login.php');
    exit();
}

include '../includes/header.php';
?>

<div class="admin-dashboard">
    <h1>管理后台</h1>
    
    <div class="admin-menu">
        <div class="menu-item">
            <h3>用户管理</h3>
            <p>管理系统用户</p>
            <a href="user-management.php" class="btn">进入</a>
        </div>
        
        <div class="menu-item">
            <h3>图库管理</h3>
            <p>管理图片和相册</p>
            <a href="gallery-management.php" class="btn">进入</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?> 
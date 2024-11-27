<?php
session_start();
// print_r($_SESSION);
require_once '../config/config.php';
require_once '../classes/Album.php';

// 实例化Album类获取所有分类
$album = new Album();
$albums = $album->getAllAlbums();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photography CMS - Home</title>
    <link rel="stylesheet" href="../public/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    
</head>
<body>
    <!-- 头部区域 -->
    <div class="header">
        <img src="../img/homeheader.jpg" alt="Header Image" class="header-image">
        <div class="header-text">
            <h1>Welcome to Wentao Zhao's Photography CMS</h1>
            <p>Capture the moment, preserve the memory</p>
        </div>
    </div>

    <!-- 导航栏 -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- 相册下拉菜单 -->
            <div class="dropdown">
                <a href="home.php"  class="dropbtn">Albums</a>
                <div class="dropdown-content">
                    <?php foreach($albums as $album): ?>
                        <a href="home.php?album_id=<?php echo $album['id']; ?>" class="nav-link">
                            <?php echo htmlspecialchars($album['name']); ?> 
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

           

            <!-- 用户信息下拉菜单 -->
            <div class="dropdown">
                <button class="dropbtn">Identity</button>
                <div class="dropdown-content">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <p>Hello <?php echo $_SESSION['username']; ?></p>
                        <a href="../admin/index.php">Backend Management</a>
                        <a href="home.php?action=logout">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                        <a href="register.php">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

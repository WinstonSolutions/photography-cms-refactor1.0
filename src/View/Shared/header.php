<?php
// 移除直接的文件引入，改用命名空间
use App\Core\Helpers\Session;
use App\Core\Helpers\{clean_input, is_logged_in, is_admin};
use App\Model\Album;  // 添加模型的命名空间引用
use App\Model\User;   // 添加模型的命名空间引用

Session::start();

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
    <!-- CSS 引用 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/header.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/home.css">
    
    <!-- JavaScript 引用 -->
    <script src="<?php echo BASE_URL; ?>/public/js/main.js" defer></script>
    
    <!-- 添加 Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- 添加 footer 相关的样式 -->
    <style>
        /* 确保 footer 始终在底部 */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
        }
        
        .footer {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <!-- 头部区域 -->
    <header class="main-header">
        <div class="header-image-container">
            <img src="<?php echo BASE_URL; ?>/public/images/homeheader.jpg" alt="Header Image" class="header-image">
            <div class="header-text">
                <h1>Welcome to Winston's Photography CMS</h1>
                <p>Capture the moment, preserve the memory</p>
            </div>
        </div>

        <!-- 导航栏 -->
        <nav class="navbar">
            <div class="nav-container">
                <!-- 相册下拉菜单 -->
                <div class="dropdown">
                    <a href="<?php echo BASE_URL; ?>/src/View/Home/home.php" class="dropbtn">Albums</a>
                    <div class="dropdown-content">
                        <?php foreach($albums as $album): ?>
                            <a href="<?php echo BASE_URL; ?>/src/View/Home/home.php?album_id=<?php echo $album['id']; ?>" class="nav-link">
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
                            <a href="<?php echo BASE_URL; ?>/admin/index.php">Backend Management</a>
                            <a href="<?php echo BASE_URL; ?>/src/View/Home/home.php?action=logout">Logout</a>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/src/View/Home/login.php">Login</a>
                            <a href="<?php echo BASE_URL; ?>/src/View/Home/register.php">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
 
    <!-- 在其他 JavaScript 引用后添加 -->
    <script src="<?php echo BASE_URL; ?>/public/js/home.js" defer></script>
 
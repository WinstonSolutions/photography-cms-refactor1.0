<?php
session_start();
require_once '../config/config.php';
require_once '../classes/Category.php';

// 实例化Category类获取所有分类
$category = new Category();
$categories = $category->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photography CMS - Home</title>
    <style>
        /* 基础样式 */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* 头部样式 */
        .header {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
        }

        .header-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .header-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        /* 导航栏样式 */
        .navbar {
            background-color: #333;
            padding: 15px 0;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-around;
        }

        /* 下拉菜单样式 */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            color: white;
            padding: 10px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 16px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <!-- 头部区域 -->
    <div class="header">
        <img src="../img/homeheader.jpg" alt="Header Image" class="header-image">
        <div class="header-text">
            <h1>Welcome to Photography CMS</h1>
            <p>Capture the moment, preserve the memory</p>
        </div>
    </div>

    <!-- 导航栏 -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- 相册下拉菜单 -->
            <div class="dropdown">
                <button class="dropbtn">Albums</button>
                <div class="dropdown-content">
                    <?php foreach($categories as $category): ?>
                        <a href="gallery.php?category=<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?> 
                            (<?php echo $category['posts_count']; ?>)
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 用户信息下拉菜单 -->
            <div class="dropdown">
                <button class="dropbtn">Identity</button>
                <div class="dropdown-content">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="profile.php">Personal Information</a>
                        <a href="../admin/index.php">Backend Management</a>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                        <a href="register.php">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- 主要内容区域 -->
    <div class="main-content">
        <!-- 这里可以添加其他内容 -->
    </div>

    <script>
        // 如果需要添加任何JavaScript功能
    </script>
</body>
</html> 
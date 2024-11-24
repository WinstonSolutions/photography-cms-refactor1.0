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
    <link rel="stylesheet" href="../public/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

    <!-- Footer 区域 -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>A professional photography content management system designed to showcase and manage your precious moments.</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <div class="social-icons">  <!-- 新增social-icons容器 -->
                    <a href="https://twitter.com/your-handle" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="https://tiktok.com/@your-handle" target="_blank"><i class="fab fa-tiktok"></i></a>
                    <a href="https://facebook.com/your-page" target="_blank"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p>
                    <i class="fa fa-phone"></i> +1 431 990 1234<br>
                    <i class="fa fa-envelope"></i> wzhao8@academic.rrc.ca<br>
                    <i class="fa fa-map-marker"></i> Winnipeg, Canada
                </p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Photography CMS. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // 如果需要添加任何JavaScript功能
    </script>
</body>
</html> 
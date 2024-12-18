<?php
// 使用 Composer 的自动加载器
require_once __DIR__ . '/../../../vendor/autoload.php';

// 正确的命名空间路径
use App\Core\Helpers\Session;
use App\Controller\Admin\AdminController;

Session::start();

// $controller = new AdminController();

// // 获取当前页面参数
// $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// // 根据页面参数获取数据
// switch($page) {
//     case 'dashboard':
//         $viewData = $controller->dashboard();
//         break;
//     // ... 其他页面处理
// }

// 确保视图文件是通过控制器加载的
if (!isset($viewData)) {
    die('Direct access to this file is not allowed');
}

// 解构数据
extract($viewData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f0f2f5;
        }

        .admin-header {
            background: #333;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .admin-header a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-title {
            font-size: 1.2em;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- 管理后台顶部导航栏 -->
    <div class="admin-header">
        <div class="header-left">
            <a href="../views/home.php"><i class="fas fa-arrow-left"></i> Visit</a>
        </div>
        <div class="header-right">
            <a href="#"><i class="fas fa-user"></i> <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; ?></a>
            <!-- <a href="../views/home.php"><i class="fas fa-sign-out-alt"></i> Logout</a> -->
        </div>
    </div>

    <!-- 在 body 标签内，admin-header 之后添加 -->
    <div class="admin-container">
        <!-- 左侧导航栏 -->
        <div class="admin-sidebar">
            <a href="index.php" class="sidebar-item <?php echo !isset($_GET['page']) ? 'active' : ''; ?>">
                <i class="fas fa-desktop"></i> Dashboard
            </a>
            <div class="sidebar-group">
                <a href="index.php?page=photos" class="sidebar-item <?php echo (htmlspecialchars($_GET['page'] ?? '') === 'photos') ? 'active' : ''; ?>">
                    <i class="fas fa-folder"></i> Photos
                </a>
            </div>
            <div class="sidebar-group">
                <a href="index.php?page=albums" class="sidebar-item <?php echo (htmlspecialchars($_GET['page'] ?? '') === 'albums') ? 'active' : ''; ?>">
                    <i class="fas fa-folder"></i> Albums
                </a>
            </div>
            <div class="sidebar-group">
                <a href="index.php?page=users" class="sidebar-item <?php echo (htmlspecialchars($_GET['page'] ?? '') === 'users') ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Users
                </a>
            </div>
        </div>

        <!-- 主要内容区域 -->
        <div class="admin-content">
            <?php
            // 根据页面参数加载不同的内容
            switch($page) {
                case 'users':
                    include __DIR__ . '/../src/View/Admin/users.php';
                    break;
                case 'photos':
                    include __DIR__ . '/../src/View/Admin/photos.php';
                    break;
                case 'albums':
                    include __DIR__ . '/../src/View/Admin/albums.php';
                    break;
                default:
                    // 显示默认的 dashboard 内容
                    include __DIR__ . '/../src/View/Admin/index.php';
            }
            ?>
        </div>
    </div>
</body>
</html>

<style>
.admin-dashboard {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.admin-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.stat-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 2em;
    margin-bottom: 10px;
    color: #007bff;
}

.stat-number {
    font-size: 1.5em;
    font-weight: bold;
    margin: 10px 0;
}

.stat-label {
    color: #666;
    font-size: 0.9em;
}

h1 {
    color: #333;
    margin-bottom: 20px;
}

h2 {
    color: #666;
    font-size: 1.2em;
    margin-bottom: 15px;
}

.admin-container {
    display: flex;
    min-height: calc(100vh - 60px); /* 减去header高度 */
}

.admin-sidebar {
    width: 250px;
    background: #333;
    color: #fff;
    padding: 20px 0;
}

.sidebar-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #fff;
    text-decoration: none;
    transition: background 0.3s;
    cursor: pointer;
}

.sidebar-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar-item.active {
    background: rgba(255, 255, 255, 0.2);
}

.sidebar-item i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.sidebar-item .fa-chevron-down {
    margin-left: auto;
    font-size: 0.8em;
}

.sidebar-submenu {
    background: rgba(0, 0, 0, 0.2);
    padding: 5px 0;
}

.sidebar-subitem {
    display: flex;
    align-items: center;
    padding: 8px 20px 8px 40px;
    color: #fff;
    text-decoration: none;
    transition: background 0.3s;
    font-size: 0.9em;
}

.sidebar-subitem:hover {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar-subitem i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.admin-content {
    flex: 1;
    padding: 20px;
    background: #f0f2f5;
}

.sidebar-group {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

/* 调整原有的样式 */
.admin-dashboard {
    max-width: none;
    padding: 0;
}
</style>


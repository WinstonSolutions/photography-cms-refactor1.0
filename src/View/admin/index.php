<?php
// 使用 Composer 的自动加载器
require_once __DIR__ . '/../../../vendor/autoload.php';

// 正确的命名空间路径
use App\Core\Helpers\Session;
use App\Controller\Admin\AdminController;

Session::start();

// $controller = new AdminController();

// // // 获取当前页面参数
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// // // 根据页面参数获取数据
// switch($page) {
//     case 'dashboard':
//         $viewData = $controller->dashboard();
//         break;
//     // ... 其他页面处理
// }

// 确保视图文件是通过控制器加载的
if (!isset($viewData)) {
    die('Direct access to index.php is not allowed');
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
    <!-- 引入外部 CSS 文件 -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/admin.css">
  
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
                    include __DIR__ . '/user-management.php';
                    break;
                case 'photos':
                    include __DIR__ . '/post-management.php';
                    break;
                case 'albums':
                    include __DIR__ . '/album-management.php';
                    break;
                default:
                    // 显示默认的 dashboard 内容
                    include __DIR__ . '/dashboard-content.php';
            }
            ?>
        </div>
    </div>
</body>
</html>


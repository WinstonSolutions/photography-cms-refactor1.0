<?php
// 定义项目根目录常量
define('ROOT_PATH', dirname(__DIR__));

// 引入必要的类和配置
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/config/config.php';

// 引入必要的命名空间
use App\Controller\Home\HomeController;
use App\Core\Helpers\Session;

Session::start();

// 检查是否请求访问 CMS
if (isset($_GET['access_cms']) || isset($_GET['action'])) {
    // 创建控制器实例
    $controller = new HomeController();
    
    // 根据请求路径决定调用哪个方法
    $action = $_GET['action'] ?? 'index';
    
    switch ($action) {
        case 'logout':
            $controller->logout();
            break;
        default:
            // 获取视图数据
            $viewData = $controller->index();
            // 加载视图
            require_once ROOT_PATH . '/src/View/Home/home.php';
            break;
    }
    exit(); // 确保不会继续显示欢迎页面
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winston's PHOTOGRAPHY-CMS</title>
    <!-- 这里引用了 style.css -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <!-- 主标题 -->
    <div id="mainTitle" class="main-title">WINSTON'S PHOTOGRAPHY-CMS</div>
    
    <!-- 背景图片 -->
    <img id="bgImage" class="bg-image" src="./images/background.jpeg" alt="Background">
    
    <!-- CMS入口链接 -->
    <a href="?access_cms=1" id="cmsEnter" class="cms-enter">ACCESS THE CMS</a>

    <script src="./js/main.js"></script>
</body>
</html>
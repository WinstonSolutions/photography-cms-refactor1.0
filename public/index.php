<?php
// Start the session
session_start();

// 定义项目根目录常量
define('ROOT_PATH', dirname(__DIR__));

// 引入必要的类和配置
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/config/config.php';

// 引入必要的命名空间
use App\Controller\Home\HomeController;
use App\Core\Helpers\Session;
use App\Controller\Admin\AdminController;
Session::start();



// 检查是否请求访问 CMS
if (isset($_GET['access_cms']) || isset($_GET['action']) || isset($_GET['search'])) {//查是否有名为access_cms或action的GET参数。如果这些参数中的任何一个存在，则进入下面的代码块
    // 创建控制器实例
    $homeController = new HomeController();
    $adminController = new AdminController();
    
    // 根据请求路径决定调用哪个方法
    $action = $_GET['action'] ?? 'index';//如果$_GET['action']存在且非空，则$action变量被赋予这个值；否则，$action被设为'index'。这意味着如果没有指定动作，默认使用index动作。
    
    switch ($action) {
        case 'showlogin':
            $homeController->showlogin();
            break;
        case 'login':
            $homeController->login();
            break;
        case 'logout':
            $homeController->logout();
            break;
        case 'backend':
            $adminController->dashboard();
            break;
        case 'photos':
            $adminController->photos();
            break;
        case 'albums':
            $adminController->albums();
            break;
        case 'users':
            $adminController->users();
            break;
        case 'delete':
            $adminController->deleteImage();
            break;
        case 'deleteAlbum':
            $adminController->deleteAlbum();
            break;
        case 'deleteUser':
            $adminController->deleteUser();
            break;
        case 'showRegister':
            $homeController->showRegister();
            break;
        case 'sort':
            $homeController->sort();
            break;
        default:
            $homeController->index();
            break;
    }
    exit(); // 确保不会继续显示欢迎页面
}?><!DOCTYPE html>
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

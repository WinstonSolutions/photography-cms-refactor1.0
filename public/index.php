<?php
// 定义项目根目录常量
define('ROOT_PATH', dirname(__DIR__));

// 引入必要的类和配置
require_once ROOT_PATH . '/src/Controller/Home/HomeController.php';
require_once ROOT_PATH . '/config/config.php';

// 如果有请求访问CMS
if (isset($_GET['access_cms'])) {
    // 实例化控制器
    $controller = new \Controller\Home\HomeController();
    // 调用index方法
    $controller->index();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winston's PHOTOGRAPHY-CMS</title>
    <!-- 修改CSS文件路径，使用相对于项目根目录的路径 -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <!-- 主标题 -->
    <div id="mainTitle" class="main-title">WINSTON'S PHOTOGRAPHY-CMS</div>
    
    <!-- 修改背景图片路径，使用相对于当前目录的路径 -->
    <img id="bgImage" class="bg-image" src="./images/background.jpeg" alt="Background">
    
    <!-- 修改链接，添加access_cms参数 -->
    <a href="?access_cms=1" id="cmsEnter" class="cms-enter">ACCESS THE CMS</a>

    <!-- 修改JavaScript文件路径，使用相对于当前目录的路径 -->
    <script src="./js/main.js"></script>
</body>
</html>
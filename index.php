<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wentao Zhao's PHOTOGRAPHY-CMS</title>
    <style>
        /* 基础样式设置 */
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: black;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        /* 主标题样式 */
        .main-title {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 2.5em;
            opacity: 0;
            text-align: center;
            transition: opacity 2s;
        }

        /* 背景图片容器 */
        .bg-image {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 2s;
            object-fit: cover;
        }

        /* CMS入口链接样式 */
        .cms-enter {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-decoration: none;
            font-size: 1.5em;
            opacity: 0;
            transition: all 0.3s;
            padding: 10px 20px;
            border: 2px solid white;
        }

        .cms-enter:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <!-- 主标题 -->
    <div id="mainTitle" class="main-title">Wentao Zhao's PHOTOGRAPHY-CMS</div>
    
    <!-- 背景图片 -->
    <img id="bgImage" class="bg-image" src="./img/background.jpeg" alt="Background">
    
    <!-- CMS入口链接 -->
    <a href="admin/login.php" id="cmsEnter" class="cms-enter">ACCESS THE CMS</a>

    <script>
        // 动画序列控制
        document.addEventListener('DOMContentLoaded', function() {
            const mainTitle = document.getElementById('mainTitle');
            const bgImage = document.getElementById('bgImage');
            const cmsEnter = document.getElementById('cmsEnter');

            // 显示主标题
            setTimeout(() => {
                mainTitle.style.opacity = '1';
            }, 500);

            // 淡出主标题
            setTimeout(() => {
                mainTitle.style.opacity = '0';
            }, 3000);

            // 显示背景图片
            setTimeout(() => {
                bgImage.style.opacity = '1';
            }, 4000);

            // 显示CMS入口链接
            setTimeout(() => {
                cmsEnter.style.opacity = '1';
            }, 5000);
        });
    </script>
</body>
</html>
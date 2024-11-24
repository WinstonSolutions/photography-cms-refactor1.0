<?php
include __DIR__ . '/../includes/header.php';

// 检查是否有注销请求
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // 清除所有会话变量
    $_SESSION = [];
    
    // 销毁会话
    session_destroy();
    
    // 重定向到 home.php
    header('Location: home.php');
    exit();
}
?>

<!-- 主要内容区域 -->
<div class="main-content">
    <!-- 这里可以添加其他内容 -->
</div>

<?php
include __DIR__ . '/../includes/footer.php';
?>
</body>
</html>
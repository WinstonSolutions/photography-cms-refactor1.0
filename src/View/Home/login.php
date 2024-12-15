<?php
// 确保 session 已启动
session_start();
// 确保 autoload 已加载
require_once __DIR__ . '/../../../vendor/autoload.php';

// 引入辅助函数
require_once __DIR__ . '/../../Core/Helpers/functions.php';

// 使用命名空间引入 User 类
use App\Model\User;



// // 调试信息：检查文件是否存在
// if (class_exists('App\Model\User')) {
//     echo "User class found.";
//     exit();
// } else {
//     echo "User class not found.";
//     exit();
// }

// 如果已经登录，重定向到首页
if(is_logged_in()) {
    header('Location: home.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    
    $user = new User();
    $logged_user = $user->login($email, $password);
    
    if ($logged_user) {
        $_SESSION['user_id'] = $logged_user['id'];
        $_SESSION['username'] = $logged_user['username'];
        $_SESSION['user_role'] = $logged_user['role'];
        
        // 创建 HomeController 实例并调用 index 方法
        $controller = new \App\Controller\Home\HomeController();
        $controller->index();
        exit();
    } else {
        $error = '邮箱或密码错误';
    }
}

// 包含头部文件
require_once __DIR__ . '/../Shared/header.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h1>Login</h1>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email" value="">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password" value="">
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="auth-links">
            <p>Don't have an account? <a href="register.php" class="register-link">Register Now</a></p>
            <p><a href="/forgot-password" class="forgot-password-link">Forgot Password?</a></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../Shared/footer.php'; ?> 
<?php
session_start();
require_once 'includes/functions.php';
require_once 'classes/User.php';

// 如果已经登录，重定向到首页
if(is_logged_in()) {
    header('Location: /');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $error = '两次输入的密码不一致';
    } else {
        $user = new User();
        if ($user->register($username, $email, $password)) {
            // 注册成功后自动登录
            $logged_user = $user->login($email, $password);
            $_SESSION['user_id'] = $logged_user['id'];
            $_SESSION['username'] = $logged_user['username'];
            $_SESSION['user_role'] = $logged_user['role'];
            header('Location: /');
            exit();
        } else {
            $error = '注册失败，邮箱可能已被使用';
        }
    }
}

include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h1>注册新账号</h1>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">用户名</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">邮箱</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">确认密码</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">注册</button>
        </form>
        
        <div class="auth-links">
            <p>已有账号？ <a href="/login">立即登录</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 
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
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    
    $user = new User();
    $logged_user = $user->login($email, $password);
    
    if ($logged_user) {
        $_SESSION['user_id'] = $logged_user['id'];
        $_SESSION['username'] = $logged_user['username'];
        $_SESSION['user_role'] = $logged_user['role'];
        header('Location: /');
        exit();
    } else {
        $error = '邮箱或密码错误';
    }
}

include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h1>登录</h1>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">邮箱</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">登录</button>
        </form>
        
        <div class="auth-links">
            <p>还没有账号？ <a href="/register">立即注册</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 
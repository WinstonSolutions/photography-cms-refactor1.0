<?php

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../classes/User.php';

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

include __DIR__ . '/../includes/header.php';
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
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
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

<?php include __DIR__ . '/../includes/footer.php'; ?> 
<?php
// 确保视图文件是通过控制器加载的
if (!isset($viewData)) {
    die('Direct access to this file is not allowed');
}

// 包含头部文件
require_once __DIR__ . '/../Shared/header.php';

// 从视图数据中提取错误信息
$error = $viewData['error'] ?? '';
?>

<div class="auth-container">
    <div class="auth-form">
        <h1>Login</h1>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo BASE_URL; ?>public/index.php?action=login" class="login-form">
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
            <p>Don't have an account? <a href="<?php echo BASE_URL; ?>public/index.php?action=showRegister" class="register-link">Register Now</a></p>
            <p><a href="/forgot-password" class="forgot-password-link">Forgot Password?</a></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../Shared/footer.php'; ?> 
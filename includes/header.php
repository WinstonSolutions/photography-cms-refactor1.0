<?php
$config = require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['site_name']; ?></title>
    <link rel="stylesheet" href="/photography-cms/public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="/photography-cms/"><?php echo $config['site_name']; ?></a>
            </div>
            <ul class="nav-links">
                <li><a href="/photography-cms/">Home</a></li>
                <li><a href="/photography-cms/gallery">Gallery</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="/photography-cms/admin">Admin</a></li>
                    <li><a href="/photography-cms/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/photography-cms/login">Login</a></li>
                    <li><a href="/photography-cms/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
</html> 
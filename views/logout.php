<?php
session_start();

// 清除所有会话变量
$_SESSION = [];

// 销毁会话
session_destroy();

// 重定向到登录页面
header('Location: login.php');
exit();
?> 
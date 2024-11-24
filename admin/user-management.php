<?php

require_once  __DIR__ . '/../includes/functions.php';
require_once  __DIR__ . '/../classes/User.php';



$user = new User();
$users = $user->getAllUsers(); // 需要在User类中添加此方法

// 处理用户删除
if (isset($_POST['delete_user'])) {
    $user_id = clean_input($_POST['user_id']);
    $user->deleteUser($user_id);
    header('Location: user-management.php');
    exit();
}

?>

<div class="admin-content">
    <h1>用户管理</h1>
    
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>用户名</th>
                <th>邮箱</th>
                <th>注册时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['created_at']; ?></td>
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete_user" 
                                onclick="return confirm('确定要删除此用户吗？')" 
                                class="btn-delete">删除</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


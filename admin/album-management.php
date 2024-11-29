<?php
// print_r($_SESSION);
require_once '../includes/functions.php';
require_once '../classes/Album.php';

$error = ''; // Initialize error message variable
$success = ''; // Initialize success message variable
$Album = new Album(); // 创建 Album 类的实例
// print_r($_SERVER);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $name = clean_input($_POST['name']);
                $description = clean_input($_POST['description']);
                $userId = $_SESSION['user_id']; // 从会话中获取用户 ID
                if ($Album->create(['name' => $name, 'description' => $description, 'user_id' => $userId])) {
                    $success = 'Album created successfully!'; // Success message
                } else {
                    $error = 'Failed to create album.'; // Error message
                }
                break;
            
        }
    }
}



$Albums = new Album(); 
$Albums = $Albums->getAllAlbums();


// 处理删除请求
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']); // 获取要删除的专辑 ID
    // 获取专辑信息
    $albumToDelete = $Album->getAlbumById($deleteId); // 假设 Album 类中有此方法

    // 检查用户权限
    if ($albumToDelete['user_id'] === $_SESSION['user_id'] || $_SESSION['user_role'] === 'admin') {
        if ($Album->deleteAlbumWithImages($deleteId)) { // 删除专辑及其图片
            header('Location: index.php?page=albums'); // 重定向到 albums 页面
            exit();
        } else {
            $error = "Failed to delete the album and its images."; // 添加错误信息
        }
    } else {
        $error = "You do not have permission to delete this album.";
    }
}

?>

<div class="admin-content">
    <h1>Album Management</h1>
    
    <?php if ($error): ?> <!-- Display error message -->
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?> <!-- Display success message -->
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <!-- Create/Edit Form -->
    <div class="Album-form">
        <h2>Create New Album</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label for="name">Album Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea>
            </div>
            
            <button type="submit" class="btn">Create Album</button>
        </form>
    </div>
    
    <!-- Albums List -->
    <div class="Albums-list">
        <h2>All Albums</h2>
        <table class="Albums-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Posts Count</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($Albums as $cat): ?>
                    <tr>
                        <td><?php echo $cat['name']; ?></td>
                        <td><?php echo $cat['description']; ?></td>
                        <td><?php echo $cat['posts_count']; ?></td>
                        <td><?php echo $cat['username']; ?></td>
                        <td><?php echo $cat['created_at']; ?></td>
                        <td>
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <a href="album-management.php?delete_id=<?php echo $cat['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete All Posts in this Album?');"
                                    class="delete-btn delete-active">Delete</a>
                            <?php else: ?>
                                <a href="#" onclick="alert('You do not have permission to delete this Album'); return false;"
                                    class="delete-btn delete-inactive">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<style>
.delete-btn {
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 3px;
        transition: background-color 0.3s ease;
    }

    .delete-active {
        background-color: red;
        color: white;
        cursor: pointer;
    }

    .delete-active:hover {
        background-color: darkred;
    }

    .delete-inactive {
        background-color: #cccccc;
        color: #666666;
        cursor: not-allowed;
    }

</style>


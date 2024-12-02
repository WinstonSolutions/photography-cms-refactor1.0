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
    /* Add styles for the album management page */
    .admin-content {
        padding: 20px; /* Add padding to the content */
        background-color: #f9f9f9; /* Light background color */
        border-radius: 5px; /* Rounded corners */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }

    h1, h2 {
        color: #333; /* Darker text color for headings */
    }

    .error {
        color: red; /* Error message color */
        font-weight: bold; /* Bold error messages */
    }

    .success {
        color: green; /* Success message color */
        font-weight: bold; /* Bold success messages */
    }

    .Album-form {
        margin-bottom: 20px; /* Space below the form */
        padding: 15px; /* Padding inside the form */
        background-color: #fff; /* White background for the form */
        border-radius: 5px; /* Rounded corners */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }

    .form-group {
        margin-bottom: 15px; /* Space between form groups */
    }

    .btn {
        background-color: #007bff; /* Primary button color */
        color: white; /* Button text color */
        padding: 10px 15px; /* Button padding */
        border: none; /* Remove border */
        border-radius: 5px; /* Rounded corners */
        cursor: pointer; /* Pointer cursor on hover */
        transition: background-color 0.3s; /* Smooth background color transition */
    }

    .btn:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    .Albums-table {
        width: 100%; /* Full width for the table */
        border-collapse: collapse; /* Collapse borders */
        margin-top: 20px; /* Space above the table */
    }

    th, td {
        padding: 10px; /* Padding for table cells */
        text-align: left; /* Left align text */
        border: 1px solid #ccc; /* Light border for cells */
    }

    th {
        background-color: #f2f2f2; /* Light gray background for headers */
        color: #333; /* Darker text color for headers */
    }

    .delete-btn {
        padding: 5px 10px; /* Padding for delete button */
        text-decoration: none; /* Remove underline */
        border-radius: 3px; /* Rounded corners */
        transition: background-color 0.3s ease; /* Smooth background color transition */
    }

    .delete-active {
        background-color: red; /* Red background for active delete button */
        color: white; /* White text for delete button */
    }

    .delete-active:hover {
        background-color: darkred; /* Darker red on hover */
    }

    .delete-inactive {
        background-color: #cccccc; /* Gray background for inactive delete button */
        color: #666666; /* Darker gray text for inactive button */
        cursor: not-allowed; /* Not-allowed cursor for inactive button */
    }
</style>


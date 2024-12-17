<?php
error_reporting(E_ALL); // 显示所有错误
ini_set('display_errors', 1); // 在页面上显示错误

require_once __DIR__ . '/../includes/functions.php'; // 确保在这里调用 session_start()
require_once __DIR__ . '/../classes/Album.php';
require_once __DIR__ . '/../classes/Image.php';
require_once __DIR__ . '/../classes/User.php'; // 确保引入 User 类

require_once __DIR__ . '/../lib/php-image-resize/lib/ImageResize.php';
require_once __DIR__ . '/../lib/php-image-resize/lib/ImageResizeException.php';

use \Gumlet\ImageResize;

$albumModel = new Album();
$albums = $albumModel->getAllAlbums(); // 获取所有相册

$error = '';
$success = '';

// 获取配置
$config = require __DIR__ . '/../config/config.php'; // 确保配置文件路径正确

$host = $_SERVER['HTTP_HOST'];
// print_r($host);
if ($host === 'localhost') {
    $host = 'localhost/WebDevelopment2/photography-cms';
} else {
    $host = 'web2.byethost18.com';
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $album_id = clean_input($_POST['album_id']);
    $file = $_FILES['image']; // Get the uploaded file

    // Check album_id is valid
    if (!$albumModel->exists($album_id)) {
        $error = 'Selected album does not exist.';
    } else {
        // Check file format
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $error = 'Only JPG, JPEG, and PNG file formats are allowed.';
        } else {
            // Handle file upload
            $image = new Image();
            $upload_success = $image->upload($file, $album_id, $_SESSION['user_id']); // 传递 user_id

            if ($upload_success) {
                // Create thumbnail version of the uploaded image
                try {
                    // 确保缩略图目录存在
                    $thumbnail_dir = $config['upload_path']; // 使用 $config 中的路径
                    if (!is_dir($thumbnail_dir)) {
                        mkdir($thumbnail_dir, 0755, true); // 创建目录
                    }

                    $thumbnail_path = $thumbnail_dir . uniqid() . '_' . basename($file['name']); // 定义缩略图路径
                    $imageResize = new ImageResize($upload_success); // Load the uploaded image
                    $imageResize->resizeToWidth(150); // Resize to width of 150 pixels
                    $imageResize->save($thumbnail_path); // Save the thumbnail

                    // Save both original and thumbnail paths to the database
                    $filename = basename($file['name']); // 获取文件名
                    $imageId = $image->saveImagePaths($upload_success, $thumbnail_path, $_SESSION['user_id'], $filename); // Save both paths and filename

                    // 关联图片与相册
                    if ($imageId) {
                        $image->associateImageWithAlbum($imageId, $album_id); // 关联图片与相册
                    }

                    $success = 'File uploaded successfully and thumbnail created!';
                } catch (Exception $e) {
                    $error = 'Thumbnail creation failed: ' . $e->getMessage() . ' at ' . $thumbnail_path;
                }
            } else {
                $error = 'File upload failed, please try again.';
            }
        }
    }
}

$imageModel = new Image();
$userModel = new User(); // 实例化 User 类

// 获取所有图片
$images = $imageModel->getAllImages(); // 获取所有图片的信息，包括相册 ID

// 检查是否有注销请求
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // 清除所有会话变量
    $_SESSION = [];

    // 销毁会话
    session_destroy();

    // 重定向到登录页面
    header('Location: login.php');
    exit();
}

// 处理删除请求
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']); // 获取要删除的图片 ID
    $imageToDelete = $imageModel->getImageById($deleteId); // 获取图片信息

    // 检查用户权限
    if ($imageToDelete['user_id'] === $_SESSION['user_id'] || $_SESSION['user_role'] === 'admin') {
        if ($imageModel->deleteImage($deleteId)) { // 删除图片
            header('Location: index.php?page=photos'); // 重定向到 photos 页面
            exit();
        } else {
            $error = "Failed to delete the image."; // 添加错误信息
        }
    } else {
        $error = "You do not have permission to delete this image.";
    }
}
?>

<div class="admin-dashboard">
    <h1>Upload Photo</h1>

    <div class="admin-section">
        <h2>Select an Album</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="album_id">Select an Album</label>
                <select id="album_id" name="album_id" required>
                    <option value="">Please select an album</option>
                    <?php foreach ($albums as $album): ?>
                        <option value="<?php echo $album['id']; ?>"><?php echo htmlspecialchars($album['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Select File</label>
                <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png" required>
                <p>Allowed file types: .jpg, .jpeg, .png</p>
            </div>

            <button type="submit" class="btn btn-primary">Upload Image</button>
        </form>
    </div>

    <div class="image-list">
        <table>
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Filename</th>
                    <th>Created At</th>
                    <th>Album</th>
                    <th>Uploaded By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($images as $img): ?>
                    <tr>
                        <td>
                            <img src="<?php echo 'http://' . $host . '/' . htmlspecialchars($img['thumbnail_path']); ?>"
                                alt="Image" style="width: 100px; height: auto;" />
                        </td>
                        <td><?php echo htmlspecialchars($img['filename']); ?></td>
                        <td><?php echo htmlspecialchars($img['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($img['album_name']); ?></td> <!-- 显示相册名称 -->
                        <td><?php echo htmlspecialchars($userModel->getUsernameById($img['user_id'])); ?></td>
                        <td>
                            <?php if ($img['user_id'] === $_SESSION['user_id'] || $_SESSION['user_role'] === 'admin'): ?>
                                <a href="post-management.php?delete_id=<?php echo $img['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this image?');"
                                    class="delete-btn delete-active">Delete</a>
                            <?php else: ?>
                                <a href="#" onclick="alert('You do not have permission to delete this item'); return false;"
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
    /* Add styles */
    .form-group {
        margin-bottom: 15px;
    }

    .error {
        color: red;
    }

    .success {
        color: green;
    }

    .image-list {
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ccc;
    }

    th {
        background-color: #f2f2f2;
    }

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
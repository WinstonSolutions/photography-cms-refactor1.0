<?php

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../classes/Image.php';

require_once __DIR__ . '/../lib/php-image-resize/lib/ImageResize.php';
require_once __DIR__ . '/../lib/php-image-resize/lib/ImageResizeException.php';

use \Gumlet\ImageResize;

$category = new Category();
$categories = $category->getAllCategories(); // Get all albums

$error = '';
$success = '';

// 获取配置
$config = require __DIR__ . '/../config/config.php'; // 确保配置文件路径正确

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $album_id = clean_input($_POST['album_id']);
    $file = $_FILES['image']; // Get the uploaded file

    // Check album_id is valid
    if (!$category->exists($album_id)) {
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
                    $image->saveImagePaths($upload_success, $thumbnail_path, $album_id, $_SESSION['user_id'], $filename); // Save both paths and filename

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
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
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
</style>


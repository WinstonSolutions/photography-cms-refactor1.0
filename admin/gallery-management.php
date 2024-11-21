<?php
session_start();
require_once '../includes/functions.php';
require_once '../classes/Gallery.php';
require_once '../classes/Image.php';

// 检查是否登录且是管理员
if (!is_logged_in() || !is_admin()) {
    header('Location: /login.php');
    exit();
}

$gallery = new Gallery();
$images = $gallery->getAllImages(); // 需要在Gallery类中添加此方法

// 处理图片上传
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    try {
        $image = new Image();
        $image->upload(
            $_FILES['image'],
            clean_input($_POST['title']),
            clean_input($_POST['description']),
            $_SESSION['user_id']
        );
        header('Location: gallery-management.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// 处理图片删除
if (isset($_POST['delete_image'])) {
    $image_id = clean_input($_POST['image_id']);
    $gallery->deleteImage($image_id); // 需要在Gallery类中添加此方法
    header('Location: gallery-management.php');
    exit();
}

include '../includes/header.php';
?>

<div class="admin-content">
    <h1>图库管理</h1>
    
    <!-- 上传新图片表单 -->
    <div class="upload-form">
        <h2>上传新图片</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">标题</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="description">描述</label>
                <textarea id="description" name="description"></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">选择图片</label>
                <input type="file" id="image" name="image" accept="image/*" required 
                       onchange="previewImage(this)">
                <img id="image-preview" src="#" alt="" style="display: none; max-width: 200px;">
            </div>
            
            <button type="submit" class="btn">上传图片</button>
        </form>
    </div>
    
    <!-- 图片列表 -->
    <div class="gallery-list">
        <h2>现有图片</h2>
        <div class="gallery-grid">
            <?php foreach($images as $image): ?>
            <div class="gallery-item">
                <img src="<?php echo $image['thumbnail_path']; ?>" 
                     alt="<?php echo $image['title']; ?>">
                <h3><?php echo $image['title']; ?></h3>
                <p><?php echo $image['description']; ?></p>
                <form method="POST">
                    <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                    <button type="submit" name="delete_image" 
                            onclick="return confirm('确定要删除此图片吗？')" 
                            class="btn-delete">删除</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?> 
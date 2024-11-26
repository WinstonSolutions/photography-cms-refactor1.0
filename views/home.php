<?php
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Image.php';
require_once __DIR__ . '/../classes/Category.php';

$image = new Image();
$category = new Category();

// 获取所有类别
$categories = $category->getAllCategories();

// 获取所有图片
$images = $image->getAllImages(); // 假设你在 Image 类中有这个方法

// 检查是否有注销请求
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // 清除所有会话变量
    $_SESSION = [];
    
    // 销毁会话
    session_destroy();
    
    // 重定向到 home.php
    header('Location: home.php');
    exit();
}
?>

<!-- 主要内容区域 -->
<div class="main-content">
    <h1>Gallery</h1>
    
    <?php foreach ($categories as $cat): ?>
        <h2><?php echo htmlspecialchars($cat['name']); ?></h2>
        <div class="image-gallery">
            <?php foreach ($images as $img): ?>
                <?php if ($img['album_id'] == $cat['id']): // 根据类别过滤图片 ?>
                    <div class="image-item">
                        <?php 
                            // 生成正确的缩略图路径
                            $thumbnail_path = htmlspecialchars($img['thumbnail_path']); // 使用相对路径
                            // echo '<p>Thumbnail Path: ' . $thumbnail_path . '</p>'; // 输出路径用于调试
                        ?>
                        <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/WebDevelopment2/photography-cms/' . $thumbnail_path; ?>" alt="Image" />
                        <!-- <p>Image URL: <?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/WebDevelopment2/photography-cms/' . $thumbnail_path; ?></p> -->
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<style>
.image-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.image-item {
    width: calc(33.33% - 10px); /* 三列布局 */
}

.image-item img {
    width: 100%;
    border-radius: 8px;
}
</style>

<?php
include __DIR__ . '/../includes/footer.php';
?>
</body>
</html>
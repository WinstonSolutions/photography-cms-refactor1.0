<?php
session_start();
require_once 'includes/functions.php';
require_once 'classes/Database.php';
require_once 'classes/Gallery.php';

// 获取最新图片
$gallery = new Gallery();
$latest_images = $gallery->getLatestImages(12);

// 包含页头
include 'includes/header.php';
?>

<div class="container">
    <h1>欢迎来到摄影作品展示系统</h1>
    
    <!-- 最新作品展示 -->
    <div class="latest-works">
        <h2>最新作品</h2>
        <div class="gallery-grid">
            <?php foreach($latest_images as $image): ?>
                <div class="gallery-item">
                    <img src="<?php echo $image['thumbnail_path']; ?>" 
                         alt="<?php echo $image['title']; ?>">
                    <h3><?php echo $image['title']; ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 
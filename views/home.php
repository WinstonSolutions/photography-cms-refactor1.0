<?php
session_start();
require_once 'includes/functions.php';
require_once 'classes/Gallery.php';

$gallery = new Gallery();
$latest_images = $gallery->getLatestImages(6);

include 'includes/header.php';
?>

<div class="home-container">
    <!-- 首页横幅 -->
    <div class="banner">
        <h1>欢迎来到摄影展示平台</h1>
        <p>分享你的精彩瞬间</p>
        <?php if(!is_logged_in()): ?>
            <div class="cta-buttons">
                <a href="/register" class="btn">立即注册</a>
                <a href="/login" class="btn btn-outline">登录</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- 最新作品展示 -->
    <section class="latest-works">
        <h2>最新作品</h2>
        <div class="gallery-grid">
            <?php foreach($latest_images as $image): ?>
                <div class="gallery-item">
                    <a href="/gallery.php?id=<?php echo $image['id']; ?>">
                        <img src="<?php echo $image['thumbnail_path']; ?>" 
                             alt="<?php echo $image['title']; ?>">
                        <div class="image-info">
                            <h3><?php echo $image['title']; ?></h3>
                            <p class="author">by <?php echo $image['username']; ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="view-more">
            <a href="/gallery" class="btn">查看更多作品</a>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?> 
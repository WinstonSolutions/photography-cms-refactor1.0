<?php
session_start();
require_once 'includes/functions.php';
require_once 'classes/Gallery.php';

$gallery = new Gallery();

// 处理分页
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12;
$total_images = $gallery->getTotalImages();
$total_pages = ceil($total_images / $per_page);
$images = $gallery->getPaginatedImages($page, $per_page);

include 'includes/header.php';
?>

<div class="gallery-container">
    <h1>摄影作品展示</h1>
    
    <!-- 筛选器 -->
    <div class="gallery-filters">
        <form method="GET" class="filter-form">
            <select name="category" onchange="this.form.submit()">
                <option value="">所有分类</option>
                <option value="landscape">风景</option>
                <option value="portrait">人像</option>
                <option value="street">街拍</option>
            </select>
        </form>
    </div>

    <!-- 图片网格 -->
    <div class="gallery-grid">
        <?php foreach($images as $image): ?>
            <div class="gallery-item">
                <a href="/image.php?id=<?php echo $image['id']; ?>">
                    <img src="<?php echo $image['thumbnail_path']; ?>" 
                         alt="<?php echo $image['title']; ?>">
                    <div class="image-info">
                        <h3><?php echo $image['title']; ?></h3>
                        <p class="description"><?php echo substr($image['description'], 0, 100); ?>...</p>
                        <p class="author">by <?php echo $image['username']; ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 分页 -->
    <div class="pagination">
        <?php if($page > 1): ?>
            <a href="?page=<?php echo $page-1; ?>" class="btn">上一页</a>
        <?php endif; ?>
        
        <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" 
               class="btn <?php echo $i === $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if($page < $total_pages): ?>
            <a href="?page=<?php echo $page+1; ?>" class="btn">下一页</a>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 
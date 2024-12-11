<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Core/Helpers/functions.php';
require_once __DIR__ . '/../../../src/Core/Helpers/Session.php';
require_once __DIR__ . '/../Shared/header.php';
require_once __DIR__ . '/../../Controller/Home/HomeController.php';





use Core\Helpers\Session;

Session::start();

// 实例化控制器
$controller = new \Controller\Home\HomeController();

// 处理登出请求
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $controller->logout();
}

// 获取数据
$viewData = $controller->index();

// 解构数据
extract($viewData);

// 视图代码开始
?>

<!-- 主要内容区域 -->
<div class="main-content" style="background-color: black; color: white; padding: 20px; flex: 1;">
    <h1>Albums</h1>
    
    <!-- 添加搜索框 -->
    <div>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by filename" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <select name="album_search">
                <option value="">Select Album</option>
                <?php foreach ($albums as $album): ?>
                    <option value="<?php echo $album['id']; ?>" <?php echo $selectedAlbumSearch === $album['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($album['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Search">
        </form>
    </div>

    <?php if ($selectedAlbumId !== null): ?>
        <?php 
        // 获取所选相册的信息
        $selectedAlbum = $albumModel->getCategory($selectedAlbumId); // 获取相册信息
        ?>
        <h2><?php echo htmlspecialchars($selectedAlbum['name']); ?></h2> <!-- 只显示所选相册的名称 -->
        
        <!-- 排序按钮 -->
        <div>
            <form method="GET" action="">
                <input type="hidden" name="album_id" value="<?php echo $selectedAlbumId; ?>">
                <label for="sort_by">Sort by:</label>
                <select name="sort_by" id="sort_by" onchange="this.form.submit()">
                    <option value="filename_asc" <?php echo $sortBy === 'filename_asc' ? 'selected' : ''; ?>>Filename A-Z</option>
                    <option value="filename_desc" <?php echo $sortBy === 'filename_desc' ? 'selected' : ''; ?>>Filename Z-A</option>
                    <option value="created_at_new_old" <?php echo $sortBy === 'created_at_new_old' ? 'selected' : ''; ?>>Created At New-Old</option>
                    <option value="created_at_old_new" <?php echo $sortBy === 'created_at_old_new' ? 'selected' : ''; ?>>Created At Old-New</option>
                </select>
            </form>
        </div>

        <div class="image-gallery">
            <?php 
            // 根据选择的排序方式对图片进行排序
            usort($images, function($a, $b) use ($sortBy) {
                switch ($sortBy) {
                    case 'filename_asc':
                        return strcmp($a['filename'], $b['filename']);
                    case 'filename_desc':
                        return strcmp($b['filename'], $a['filename']);
                    case 'created_at_new_old':
                        return strtotime($b['created_at']) - strtotime($a['created_at']);
                    case 'created_at_old_new':
                        return strtotime($a['created_at']) - strtotime($b['created_at']);
                }
            });

            foreach ($images as $img): ?>
                <?php 
                // 检查图片是否与所选相册相关联
                $isAssociated = $image->isImageInAlbum($img['id'], $selectedAlbumId); // 使用所选相册 ID
                // 检查搜索关键字是否匹配
                $matchesSearch = empty($searchQuery) || stripos($img['filename'], $searchQuery) !== false;
                // 检查选择的相册是否匹配
                $matchesAlbum = empty($selectedAlbumSearch) || $selectedAlbumSearch === $selectedAlbumId;
                if ($isAssociated && $matchesSearch && $matchesAlbum): ?>
                    <div class="image-item" onclick="openModal('<?php echo 'http://' . $host . '/' . htmlspecialchars($img['file_path']); ?>')">
                        <img 
                            src="<?php echo 'http://' . $host . '/' . htmlspecialchars($img['file_path']); ?>" 
                            alt="<?php echo htmlspecialchars($img['filename']); ?>"
                            loading="lazy"
                        />
                        <p class="image-title"><?php echo htmlspecialchars($img['filename']); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- 显示所有相册及其图片 -->
        <?php foreach ($albums as $album): ?>
            <?php 
            // 检查相册中是否有图片
            $hasImages = false;
            foreach ($images as $img) {
                if ($image->isImageInAlbum($img['id'], $album['id'])) {
                    $hasImages = true;
                    break; // 找到至少一张图片后退出循环
                }
            }
            ?>
            <?php if ($hasImages): // 只有在相册中有图片时才显示相册名称 ?>
                <h2><?php echo htmlspecialchars($album['name']); ?></h2>
            <?php endif; ?>
            <div class="image-gallery">
                <?php foreach ($images as $img): ?>
                    <?php 
                    // 检查图片是否与当前相册相关联
                    $isAssociated = $image->isImageInAlbum($img['id'], $album['id']); // 使用 img['id'] 来检查
                    // 检查搜索关键字是否匹配
                    $matchesSearch = empty($searchQuery) || stripos($img['filename'], $searchQuery) !== false;
                    // 检查选择的相册是否匹配
                    $matchesAlbum = empty($selectedAlbumSearch) || $selectedAlbumSearch === $album['id'];
                    if ($isAssociated && $matchesSearch && $matchesAlbum): ?>
                        <div class="image-item" onclick="openModal('<?php echo 'http://' . $host . '/' . htmlspecialchars($img['file_path']); ?>')">
                            <img 
                                src="<?php echo 'http://' . $host . '/' . htmlspecialchars($img['file_path']); ?>" 
                                alt="<?php echo htmlspecialchars($img['filename']); ?>"
                                loading="lazy"
                            />
                            <p class="image-title"><?php echo htmlspecialchars($img['filename']); ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- 模态框 -->
<div id="modal" class="modal" onclick="closeModal()">
    <span class="close">&times;</span>
    <img class="modal-content" id="modal-img">
    <div id="caption"></div>
</div>

<style>
/* 图片画廊容器 */
.image-gallery {
    column-count: 3;
    column-gap: 15px; /* 小列间距 */
    padding: 15px;
    width: 100%;
    max-width: 100%; /* 移除最大宽度限制 */
    margin: 0 auto;
}

/* 每个图片项���容器 */
.image-item {
    break-inside: avoid;
    margin-bottom: 15px;
    position: relative;
    width: 100%;
    display: block;
    cursor: pointer;
}

/* 图片样式 */
.image-item img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 8px;
    transition: all 0.3s ease;
    object-fit: cover;
}

.image-title {
    opacity: 0; /* 初始隐藏标题 */
    transition: opacity 0.3s ease;
    color: white;
    padding: 8px;
    margin: 0;
}

.image-title.loaded {
    opacity: 1; /* 图片加载完成后显示标题 */
}

/* 主要内容区域样式 */
.main-content {
    width: 100%;
    max-width: 100%;
    padding: 20px;
    box-sizing: border-box;
    margin: 0 auto;
}

/* 响应式布局优化 */
@media (min-width: 1200px) {
    .image-gallery {
        column-count: 3;
    }
}

@media (max-width: 1199px) and (min-width: 768px) {
    .image-gallery {
        column-count: 2;
    }
}

@media (max-width: 767px) {
    .image-gallery {
        column-count: 1;
    }
}

/* 模态框样式优化 */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    padding: 40px;
    box-sizing: border-box;
}

.modal-content {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 8px;
}

.close {
    position: absolute;
    top: 20px;
    right: 30px;
    color: white;
    font-size: 35px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close:hover {
    color: #999;
}



</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.image-item img');
    
    images.forEach(img => {
        // 设置懒加载
        img.loading = 'lazy';
        
        // 添加加载事件监听器
        img.addEventListener('load', function() {
            // 图片加载完成后的处理
            img.style.display = 'block';
            const title = img.nextElementSibling;
            if (title && title.classList.contains('image-title')) {
                title.style.display = 'block';
            }
        });
    });
});

function openModal(src) {
    document.getElementById("modal").style.display = "block";
    document.getElementById("modal-img").src = src;
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}
</script>

<?php require_once __DIR__ . '/../Shared/footer.php'; ?>
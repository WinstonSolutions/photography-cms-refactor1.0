<?php
require_once '../config/config.php';;

include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Image.php';
require_once __DIR__ . '/../classes/Album.php';

$host = $_SERVER['HTTP_HOST'];
if (strpos($host, ':8000') !== false) {
    $host = 'localhost';
}


$image = new Image();
$albumModel = new Album();

// 获取所有相册
$albums = $albumModel->getAllAlbums();

// 获取所有图片
$images = $image->getAllImages(); // 假设你在 Image 类中有这个方法

// 获取相册 ID
$selectedAlbumId = isset($_GET['album_id']) ? intval($_GET['album_id']) : null;

// 获取排序方式
$sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'filename_asc'; // 默认按 filename 升序排序

// 获取搜索关键字
$searchQuery = isset($_GET['search']) ? $_GET['search'] : ''; // 获取搜索框的输入

// 获取选择的相册
$selectedAlbumSearch = isset($_GET['album_search']) ? intval($_GET['album_search']) : null; // 获取选择的相册 ID

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
<div class="main-content" style="background-color: black; color: white; padding: 20px;">
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
                    <div class="image-item" onclick="openModal('<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/WebDevelopment2/photography-cms/' . htmlspecialchars($img['file_path']); ?>')">
                        <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/WebDevelopment2/photography-cms/' . htmlspecialchars($img['file_path']); ?>" alt="Image" />
                        <p><?php echo htmlspecialchars($img['filename']); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- 显示所有相册及其图片 -->
        <?php foreach ($albums as $album): ?>
            <h2><?php echo htmlspecialchars($album['name']); ?></h2>
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
                        <div class="image-item" onclick="openModal('<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/WebDevelopment2/photography-cms/' . htmlspecialchars($img['file_path']); ?>')">
                            <img src="<?php echo 'http://' . $host . '/WebDevelopment2/photography-cms/' . htmlspecialchars($img['file_path']); ?>" alt="Image" />
                            <p><?php echo htmlspecialchars($img['filename']); ?></p>
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
.image-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.image-item {
    width: calc(33.33% - 10px); /* 三列布局 */
    cursor: pointer; /* 鼠标悬停时显示为手型 */
}

.image-item img {
    width: 100%;
    max-width: 150px; /* 设置最大宽度 */
    height: auto; /* 保持纵横比 */
    border-radius: 8px;
    transition: transform 0.2s; /* 添加过渡效果 */
}

.image-item img:hover {
    transform: scale(1.05); /* 鼠标悬停时放大 */
}

/* 模态框样式 */
.modal {
    display: none; /* 默认隐藏 */
    position: fixed; /* 固定位置 */
    z-index: 1000; /* 在最上层 */
    left: 0;
    top: 0;
    width: 100%; /* 全屏 */
    height: 100%; /* 全屏 */
    overflow: auto; /* 如果需要，添加滚动条 */
    background-color: rgba(0, 0, 0, 0.8); /* 半透明背景 */
}

.modal-content {
    margin: auto;
    display: block;
    width: 80%; /* 设置宽度 */
    max-width: 700px; /* 最大宽度 */
}

.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}
</style>

<script>
function openModal(src) {
    document.getElementById("modal").style.display = "block";
    document.getElementById("modal-img").src = src;
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}
</script>

<?php
include __DIR__ . '/../includes/footer.php';
?>
</body>
</html>
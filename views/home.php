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
<div class="main-content" style="background-color: black; color: white; padding: 20px;">
    <h1>Gallery</h1>
    
    <?php foreach ($categories as $cat): ?>
        <h2><?php echo htmlspecialchars($cat['name']); ?></h2>
        <div class="image-gallery">
            <?php foreach ($images as $img): ?>
                <?php if ($img['album_id'] == $cat['id']): // 根据类别过滤图片 ?>
                    <div class="image-item" onclick="openModal('<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/WebDevelopment2/photography-cms/' . htmlspecialchars($img['thumbnail_path']); ?>')">
                        <?php 
                            // 生成正确的缩略图路径
                            $thumbnail_path = htmlspecialchars($img['thumbnail_path']); // 使用相对路径
                        ?>
                        <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/WebDevelopment2/photography-cms/' . $thumbnail_path; ?>" alt="Image" />
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
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
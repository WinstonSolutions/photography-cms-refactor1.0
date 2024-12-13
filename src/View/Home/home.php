<?php
// 视图文件不需要重复实例化控制器，只需要使用传入的数据
if (!isset($viewData)) {
    die('Direct access to this file is not allowed');
}

require_once __DIR__ . '/../Shared/header.php';

// 解构数据
extract($viewData);

// 视图代码开始
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
                    <div class="image-item" onclick="openModal('<?php echo isset($host) ? 'http://' . $host : ''; ?>/<?php echo htmlspecialchars($img['file_path']); ?>')">
                        <img 
                            src="<?php echo isset($host) ? 'http://' . $host : ''; ?>/<?php echo htmlspecialchars($img['file_path']); ?>" 
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
                        <div class="image-item" onclick="openModal('<?php echo isset($host) ? 'http://' . $host : ''; ?>/<?php echo htmlspecialchars($img['file_path']); ?>')">
                            <img 
                                src="<?php echo isset($host) ? 'http://' . $host : ''; ?>/<?php echo htmlspecialchars($img['file_path']); ?>" 
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

<?php 
require_once __DIR__ . '/../Shared/footer.php'; 
?>
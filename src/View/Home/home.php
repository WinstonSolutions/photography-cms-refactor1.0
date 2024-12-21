<?php
// Ensure the view file is loaded through the controller
if (!isset($viewData)) {
    die('Direct access to this file is not allowed');
}

require_once __DIR__ . '/../Shared/header.php';

// Extract data
extract($viewData);

// View code starts
$host = $_SERVER['HTTP_HOST'];
if ($host === 'localhost') {
    $host = 'localhost/WebDevelopment2/photography-cms-refactor1.0';
} else {
    $host = 'web2.byethost18.com';
}
?>

<!-- Main content area -->
<div class="main-content" style="background-color: black; color: white; padding: 20px;">
    <h1>Albums</h1>
    
    <!-- Search form -->
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
        // Get selected album information
        $selectedAlbum = $albumModel->getCategory($selectedAlbumId);
        ?>
        <h2><?php echo htmlspecialchars($selectedAlbum['name']); ?></h2>
        
        <!-- Sorting options -->
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
            // Sort images based on selected criteria
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
                // Check if image is associated with the selected album
                $isAssociated = $image->isImageInAlbum($img['id'], $selectedAlbumId);
                // Check if search keyword matches
                $matchesSearch = empty($searchQuery) || stripos($img['filename'], $searchQuery) !== false;
                // Check if selected album matches
                $matchesAlbum = empty($selectedAlbumSearch) || $selectedAlbumSearch === $selectedAlbumId;
                if ($isAssociated && $matchesSearch && $matchesAlbum): ?>
                    <div class="image-item" onclick="openModal('<?php echo isset($host) ? 'http://' . $host : ''; ?>/storage/<?php echo htmlspecialchars($img['file_path']); ?>')">
                        <img 
                            src="<?php echo isset($host) ? 'http://' . $host : ''; ?>/storage/<?php echo htmlspecialchars($img['file_path']); ?>" 
                            alt="<?php echo htmlspecialchars($img['filename']); ?>"
                            loading="lazy"
                        />
                        <p class="image-title"><?php echo htmlspecialchars($img['filename']); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Display all albums and their images -->
        <?php foreach ($albums as $album): ?>
            <?php 
            // Check if the album has images
            $hasImages = false;
            foreach ($images as $img) {
                if ($image->isImageInAlbum($img['id'], $album['id'])) {
                    $hasImages = true;
                    break; // Exit loop after finding at least one image
                }
            }
            ?>
            <?php if ($hasImages): // Only display album name if it has images ?>
                <h2><?php echo htmlspecialchars($album['name']); ?></h2>
                <div class="image-gallery">
                    <?php foreach ($images as $img): ?>
                        <?php 
                        // Check if image is associated with the current album
                        $isAssociated = $image->isImageInAlbum($img['id'], $album['id']);
                        // Check if search keyword matches
                        $matchesSearch = empty($searchQuery) || stripos($img['filename'], $searchQuery) !== false;
                        // Check if selected album matches
                        $matchesAlbum = empty($selectedAlbumSearch) || $selectedAlbumSearch === $album['id'];
                        if ($isAssociated && $matchesSearch && $matchesAlbum): ?>
                            <div class="image-item" onclick="openModal('<?php echo isset($host) ? 'http://' . $host : ''; ?>/storage/<?php echo htmlspecialchars($img['file_path']); ?>')">
                                <img 
                                    src="<?php echo isset($host) ? 'http://' . $host : ''; ?>/storage/<?php echo htmlspecialchars($img['file_path']); ?>" 
                                    alt="<?php echo htmlspecialchars($img['filename']); ?>"
                                    loading="lazy"
                                />
                                <p class="image-title"><?php echo htmlspecialchars($img['filename']); ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="modal" class="modal" onclick="closeModal()">
    <span class="close">&times;</span>
    <img class="modal-content" id="modal-img">
    <div id="caption"></div>
</div>

<?php 
require_once __DIR__ . '/../Shared/footer.php'; 
?>
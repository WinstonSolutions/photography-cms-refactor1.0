<?php
// Ensure the view file is loaded through the controller
if (!isset($viewData)) {
    die('Direct access to this file is not allowed');
}

require_once __DIR__ . '/../Shared/header.php';

// Extract data
extract($viewData);

// Check if an album ID is provided in the query string
$selectedAlbumId = $_GET['album_id'] ?? null;

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

    <!-- Display albums and their images -->
    <?php foreach ($albums as $album): ?>
        <?php 
        // Check if the album has images and matches the selected album ID
        $hasImages = false;
        foreach ($images as $img) {
            if ($img['album_id'] === $album['id'] && (!$selectedAlbumId || $selectedAlbumId == $album['id'])) {
                $hasImages = true;
                break; // Exit loop after finding at least one image
            }
        }
        ?>
        <?php if ($hasImages): // Only display album name if it has images ?>
            <h2><?php echo htmlspecialchars($album['name']); ?></h2>
            <div>
            <?php if (!empty($selectedAlbumId)): ?>
                <!-- Sort By dropdown -->
                <form method="GET" action="" id="sortForm">
                    <!-- Preserve existing search parameters -->
                    <input type="hidden" name="access_cms" value="1">
                    <input type="hidden" name="album_id" value="<?php echo htmlspecialchars($selectedAlbumId); ?>">
                    <select name="sort_by" onchange="this.form.submit()">
                        <option value="filename_asc" <?php echo $sortBy === 'filename_asc' ? 'selected' : ''; ?>>Filename Ascending</option>
                        <option value="filename_desc" <?php echo $sortBy === 'filename_desc' ? 'selected' : ''; ?>>Filename Descending</option>
                        <option value="created_at_new_old" <?php echo $sortBy === 'created_at_new_old' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="created_at_old_new" <?php echo $sortBy === 'created_at_old_new' ? 'selected' : ''; ?>>Oldest First</option>
                    </select>
                </form>
            <?php endif; ?>
            </div>
            <div class="image-gallery">
                <?php foreach ($images as $img): ?>
                    <?php if ($img['album_id'] === $album['id'] && (!$selectedAlbumId || $selectedAlbumId == $album['id'])): ?>
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
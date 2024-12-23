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

    <!-- Display albums and their images -->
    <?php foreach ($albums as $album): ?>
        <?php 
        // Check if the album has images
        $hasImages = false;
        foreach ($images as $img) {
            if ($img['album_id'] === $album['id']) {
                $hasImages = true;
                break; // Exit loop after finding at least one image
            }
        }
        ?>
        <?php if ($hasImages): // Only display album name if it has images ?>
            <h2><?php echo htmlspecialchars($album['name']); ?></h2>
            <div class="image-gallery">
                <?php foreach ($images as $img): ?>
                    <?php if ($img['album_id'] === $album['id']): ?>
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
<?php

require_once '../includes/functions.php';
require_once '../classes/Album.php';

$error = ''; // Initialize error message variable
$success = ''; // Initialize success message variable
$Album = new Album(); // 创建 Album 类的实例


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $name = clean_input($_POST['name']);
                $description = clean_input($_POST['description']);
                if ($Album->create(['name' => $name, 'description' => $description])) {
                    $success = 'Album created successfully!'; // Success message
                } else {
                    $error = 'Failed to create album.'; // Error message
                }
                break;
                
            case 'update':
                $id = (int)$_POST['Album_id'];
                $name = clean_input($_POST['name']);
                $description = clean_input($_POST['description']);
                if ($Album->update($id, ['name' => $name, 'description' => $description])) {
                    $success = 'Album updated successfully!'; // Success message
                } else {
                    $error = 'Failed to update album.'; // Error message
                }
                break;
        }
    }
}

$Albums = new Album(); 
$Albums = $Albums->getAllAlbums();


?>

<div class="admin-content">
    <h1>Album Management</h1>
    
    <?php if ($error): ?> <!-- Display error message -->
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?> <!-- Display success message -->
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <!-- Create/Edit Form -->
    <div class="Album-form">
        <h2>Create New Album</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label for="name">Album Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea>
            </div>
            
            <button type="submit" class="btn">Create Album</button>
        </form>
    </div>
    
    <!-- Albums List -->
    <div class="Albums-list">
        <h2>All Albums</h2>
        <table class="Albums-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Posts Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($Albums as $cat): ?>
                    <tr>
                        <td><?php echo $cat['name']; ?></td>
                        <td><?php echo $cat['description']; ?></td>
                        <td><?php echo $cat['posts_count']; ?></td>
                        <td>
                            <a href="?edit=<?php echo $cat['id']; ?>" class="btn btn-edit">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


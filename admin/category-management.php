<?php
session_start();
require_once '../includes/functions.php';
require_once '../classes/Category.php';

// Check admin access
if (!is_logged_in() || !is_admin()) {
    header('Location: /photography-cms/login');
    exit();
}

$category = new Category();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $name = clean_input($_POST['name']);
                $description = clean_input($_POST['description']);
                $category->create([
                    'name' => $name,
                    'description' => $description
                ]);
                break;
                
            case 'update':
                $id = (int)$_POST['category_id'];
                $name = clean_input($_POST['name']);
                $description = clean_input($_POST['description']);
                $category->update($id, [
                    'name' => $name,
                    'description' => $description
                ]);
                break;
        }
    }
}

$categories = $category->getAllCategories();

include '../includes/header.php';
?>

<div class="admin-content">
    <h1>Category Management</h1>
    
    <!-- Create/Edit Form -->
    <div class="category-form">
        <h2>Create New Category</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea>
            </div>
            
            <button type="submit" class="btn">Create Category</button>
        </form>
    </div>
    
    <!-- Categories List -->
    <div class="categories-list">
        <h2>All Categories</h2>
        <table class="categories-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Posts Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $cat): ?>
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

<?php include '../includes/footer.php'; ?> 
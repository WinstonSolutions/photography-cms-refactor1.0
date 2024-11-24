<?php
session_start();
require_once '../includes/functions.php';
require_once '../classes/Post.php';

// Check admin access
if (!is_logged_in() || !is_admin()) {
    header('Location: /photography-cms/login');
    exit();
}

$post = new Post();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $title = clean_input($_POST['title']);
                $content = $_POST['content']; // Allow HTML from WYSIWYG
                $category_id = (int)$_POST['category_id'];
                $slug = create_slug($title);
                
                $post->create([
                    'title' => $title,
                    'content' => $content,
                    'category_id' => $category_id,
                    'slug' => $slug,
                    'user_id' => $_SESSION['user_id']
                ]);
                break;
                
            case 'update':
                // Similar to create but with ID
                break;
                
            case 'delete':
                $post->delete((int)$_POST['post_id']);
                break;
        }
    }
}

// Get posts with sorting
$sort = isset($_GET['sort']) ? clean_input($_GET['sort']) : 'created_at';
$direction = isset($_GET['direction']) ? clean_input($_GET['direction']) : 'DESC';
$posts = $post->getAllPosts($sort, $direction);

include '../includes/header.php';
?>

<!-- Add TinyMCE -->
<script src="https://cdn.tiny.cloud/1/your-api-key/tinymce/5/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content'
    });
</script>

<div class="admin-content">
    <h1>Post Management</h1>
    
    <!-- Create/Edit Form -->
    <div class="post-form">
        <h2>Create New Post</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content"></textarea>
            </div>
            
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category_id" required>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn">Create Post</button>
        </form>
    </div>
    
    <!-- Posts List -->
    <div class="posts-list">
        <h2>All Posts</h2>
        
        <!-- Sorting Controls -->
        <div class="sort-controls">
            Sort by:
            <a href="?sort=title" class="<?php echo $sort === 'title' ? 'active' : ''; ?>">Title</a>
            <a href="?sort=created_at" class="<?php echo $sort === 'created_at' ? 'active' : ''; ?>">Date Created</a>
            <a href="?sort=updated_at" class="<?php echo $sort === 'updated_at' ? 'active' : ''; ?>">Date Updated</a>
        </div>
        
        <table class="posts-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($posts as $post): ?>
                    <tr>
                        <td><?php echo $post['title']; ?></td>
                        <td><?php echo $post['category_name']; ?></td>
                        <td><?php echo $post['created_at']; ?></td>
                        <td><?php echo $post['updated_at']; ?></td>
                        <td>
                            <a href="?edit=<?php echo $post['id']; ?>" class="btn btn-edit">Edit</a>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <button type="submit" class="btn btn-delete" 
                                        onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?> 
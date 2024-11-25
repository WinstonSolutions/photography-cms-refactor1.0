<?php

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../classes/Image.php';



$category = new Category();
$categories = $category->getAllCategories(); // Get all albums

$error = '';
$success = '';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $album_id = clean_input($_POST['album_id']);
    print_r($album_id);
    $file = $_FILES['image'];

    // Check album_id is valid
    if (!$category->exists($album_id)) { // Assuming you have a method to check if the album exists
        $error = 'Selected album does not exist.';
    } else {
        // Check file format
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $error = 'Only JPG, JPEG, and PNG file formats are allowed.';
        } else {
            // Handle file upload
            $image = new Image();
            $upload_success = $image->upload($file, $album_id, $_SESSION['user_id']); // 传递 user_id

            if ($upload_success) {
                $success = 'File uploaded successfully!';
            } else {
                $error = 'File upload failed, please try again.';
            }
        }
    }
}
?>

<div class="admin-dashboard">
    <h1>Upload Photo</h1>
    
    <div class="admin-section">
        <h2>Select an Album</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="album_id">Select an Album</label>
                <select id="album_id" name="album_id" required>
                    <option value="">Please select an album</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="image">Select File</label>
                <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png" required>
                <p>Allowed file types: .jpg, .jpeg, .png</p>
            </div>
            
            <button type="submit" class="btn btn-primary">Upload Image</button>
        </form>
    </div>
</div>

<style>
/* Add styles */
.form-group {
    margin-bottom: 15px;
}

.error {
    color: red;
}

.success {
    color: green;
}
</style>


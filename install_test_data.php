<?php
require_once 'classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Insert test image data
    $test_images = [
        [
            'title' => 'Sunset Landscape',
            'description' => 'Beautiful sunset scenery',
            'image_path' => 'public/uploads/sunset.jpg',
            'thumbnail_path' => 'public/uploads/thumb_sunset.jpg',
            'user_id' => 1,
            'category' => 'landscape'
        ],
        [
            'title' => 'City Night',
            'description' => 'Bustling city nightscape',
            'image_path' => 'public/uploads/city.jpg',
            'thumbnail_path' => 'public/uploads/thumb_city.jpg',
            'user_id' => 1,
            'category' => 'landscape'
        ],
        [
            'title' => 'Portrait',
            'description' => 'Elegant portrait photography',
            'image_path' => 'public/uploads/portrait.jpg',
            'thumbnail_path' => 'public/uploads/thumb_portrait.jpg',
            'user_id' => 1,
            'category' => 'portrait'
        ]
    ];
    
    // Prepare SQL statement
    $sql = "INSERT INTO images (title, description, image_path, thumbnail_path, user_id, category) 
            VALUES (:title, :description, :image_path, :thumbnail_path, :user_id, :category)";
    $stmt = $db->prepare($sql);
    
    // Insert test data
    foreach ($test_images as $image) {
        $stmt->execute($image);
    }
    
    // Create sample image files
    $sample_image = imagecreatetruecolor(800, 600);
    $sample_thumb = imagecreatetruecolor(300, 200);
    
    // Ensure uploads directory exists
    if (!file_exists('public/uploads')) {
        mkdir('public/uploads', 0777, true);
    }
    
    // Create simple graphics for each sample image
    foreach (['sunset', 'city', 'portrait'] as $name) {
        // Create original image
        $sample_image = imagecreatetruecolor(800, 600);
        // Generate random color
        $bg_color = imagecolorallocate($sample_image, rand(0, 255), rand(0, 255), rand(0, 255));
        imagefill($sample_image, 0, 0, $bg_color);
        // Add some shapes
        $shape_color = imagecolorallocate($sample_image, rand(0, 255), rand(0, 255), rand(0, 255));
        imagefilledellipse($sample_image, 400, 300, 200, 200, $shape_color);
        
        // Create thumbnail
        $sample_thumb = imagecreatetruecolor(300, 200);
        imagecopyresampled($sample_thumb, $sample_image, 0, 0, 0, 0, 300, 200, 800, 600);
        
        // Save images
        imagejpeg($sample_image, "public/uploads/{$name}.jpg", 90);
        imagejpeg($sample_thumb, "public/uploads/thumb_{$name}.jpg", 80);
        
        // Free memory
        imagedestroy($sample_image);
        imagedestroy($sample_thumb);
    }
    
    echo "Test data added successfully! Sample images have been created in public/uploads directory.";
    
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch(Exception $e) {
    die("Error occurred: " . $e->getMessage());
} 
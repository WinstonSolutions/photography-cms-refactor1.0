<?php
namespace App\Controller\Home;

use App\Model\Image;
use App\Model\Album;
use App\Model\User;
use App\Core\Helpers\Session;

class HomeController {
    private $imageModel;
    private $albumModel;
    private $userModel;
    
    public function __construct() {
        $this->imageModel = new Image();
        $this->albumModel = new Album();
        $this->userModel = new User();
    }
    
    public function index() {
        // Get search and sort parameters
        $searchQuery = $_GET['search'] ?? '';
        $selectedAlbumSearch = $_GET['album_search'] ?? '';
        $sortBy = $_GET['sort_by'] ?? 'filename_asc';

        // Fetch albums and images
        $albums = $this->albumModel->getAllAlbums();
        $images = $this->imageModel->getAllImages();

        // Filter and sort images
        $filteredImages = $this->filterAndSortImages($images, $searchQuery, $selectedAlbumSearch, $sortBy);

        // Prepare data for the view
        $viewData = [
            'albums' => $albums,
            'images' => $filteredImages,
            'searchQuery' => $searchQuery,
            'selectedAlbumSearch' => $selectedAlbumSearch,
            'sortBy' => $sortBy,
        ];

        // Load the view
        $this->loadView('Home/home', $viewData);
    }

    private function filterAndSortImages($images, $searchQuery, $selectedAlbumSearch, $sortBy) {
        // Filter images based on search query and selected album
        $filteredImages = array_filter($images, function($img) use ($searchQuery, $selectedAlbumSearch) {
            $matchesSearch = empty($searchQuery) || stripos($img['filename'], $searchQuery) !== false;
            $matchesAlbum = empty($selectedAlbumSearch) || $selectedAlbumSearch == $img['album_id'];
            return $matchesSearch && $matchesAlbum;
        });

        // Sort images based on selected criteria
        usort($filteredImages, function($a, $b) use ($sortBy) {
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

        return $filteredImages;
    }

    public function logout() {
        // 清除所有会话变量
        $_SESSION = [];
        
        // 销毁会话
        session_destroy();
        
        // 设置 HTTP 头部，防止缓存
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        
        // 修改重定向路径为 home 界面
        header('Location: ' . BASE_URL . 'public/index.php?access_cms=1');
        exit();
    }

    public function showlogin() {
        // 设置视图数据
        $viewData = [
            'error' => $error ?? ''
        ];

        require_once __DIR__ . '/../../View/Home/login.php';
    }

    public function login() {
        // 检查请求方法是否为 POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 获取用户输入的 email 和 password
            $email = clean_input($_POST['email']);
            $password = $_POST['password'];
            
            // 创建 User 实例并尝试登录
            $user = new User();
            $logged_user = $user->login($email, $password);
            
            if ($logged_user) {
                // 登录成功，设置会话变量
                $_SESSION['user_id'] = $logged_user['id'];
                $_SESSION['username'] = $logged_user['username'];
                $_SESSION['user_role'] = $logged_user['role'];
                
                // 重定向到首页
                header('Location: ' . BASE_URL . 'public/index.php?access_cms=1');
                exit();
            } else {
                // 登录失败，设置错误信息
                $error = '邮箱或密码错误';
            }
        }

    }

    public function deleteImage() {
        // Check if delete_id is set in the GET request
        if (isset($_GET['delete_id'])) {
            $deleteId = intval($_GET['delete_id']); // Get the image ID to delete
            $imageModel = new Image(); // Instantiate the Image model
            $imageToDelete = $imageModel->getImageById($deleteId); // Get image details

            // Check user permissions
            if ($imageToDelete['user_id'] === $_SESSION['user_id'] || $_SESSION['user_role'] === 'admin') {
                if ($imageModel->deleteImage($deleteId)) { // Delete the image
                    header('Location: ' . BASE_URL . 'src/View/admin/post-management.php'); // Redirect to post-management page
                    exit();
                } else {
                    $error = "Failed to delete the image."; // Set error message
                }
            } else {
                $error = "You do not have permission to delete this image."; // Set permission error
            }
        }
    }

    public function showRegister() {
        // Set view data if needed
        $viewData = [
            // Add any data you need to pass to the view
        ];

        // Load the registration view
        require_once __DIR__ . '/../../View/Home/register.php';
    }

    private function loadView($viewPath, $viewData = []) {
        // Extract the view data to be used in the view
        extract($viewData);

        // Construct the full path to the view file
        $viewFile = __DIR__ . '/../../View/' . $viewPath . '.php';

        // Check if the view file exists
        if (file_exists($viewFile)) {
            require_once $viewFile; // Include the view file
        } else {
            die("View file not found: " . $viewFile); // Handle error if view file is missing
        }
    }
} 
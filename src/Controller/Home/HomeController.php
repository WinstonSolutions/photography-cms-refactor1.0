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
        $albums = $this->albumModel->getAllAlbums();
        $images = $this->imageModel->getAllImages();
        
        // 从配置文件获取 host
        $config = include dirname(__DIR__, 3) . '/config/config.php';
        
        // 加载视图并传递数据
        $viewData = [
            'title' => 'Photography CMS',
            'albums' => $albums,
            'images' => $images,
            'selectedAlbumId' => isset($_GET['album_id']) ? intval($_GET['album_id']) : null,
            'sortBy' => isset($_GET['sort_by']) ? $_GET['sort_by'] : 'filename_asc',
            'searchQuery' => isset($_GET['search']) ? $_GET['search'] : '',
            'selectedAlbumSearch' => isset($_GET['album_search']) ? intval($_GET['album_search']) : null,
            'image' => $this->imageModel,
            'host' => $config['host']  // 添加 host 配置
        ];
        
        require_once __DIR__ . '/../../View/Home/home.php';
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

    public function login() {
        // 检查请求方法是否为 POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = clean_input($_POST['email']);
            $password = $_POST['password'];
            
            $user = new User();
            $logged_user = $user->login($email, $password);
            
            if ($logged_user) {
                $_SESSION['user_id'] = $logged_user['id'];
                $_SESSION['username'] = $logged_user['username'];
                $_SESSION['user_role'] = $logged_user['role'];
                
                // 登录成功后重定向到首页
                header('Location: ' . BASE_URL . 'public/index.php?access_cms=1');
                exit();
            } else {
                $error = '邮箱或密码错误';
            }
        }

        // 加载视图并传递错误信息
        $viewData = [
            'error' => $error ?? ''
        ];
        
        require_once __DIR__ . '/../../View/Home/login.php';
    }
} 
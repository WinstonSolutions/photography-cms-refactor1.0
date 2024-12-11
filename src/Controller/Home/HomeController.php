<?php
namespace Controller\Home;

// 使用绝对路径引入所有需要的类
require_once ROOT_PATH . '/src/Model/Image.php';
require_once ROOT_PATH . '/src/Model/Album.php';
require_once ROOT_PATH . '/src/Model/User.php';
require_once ROOT_PATH . '/src/Core/Helpers/Session.php';

// 使用声明
use Model\Image;
use Model\Album;
use Model\User;
use Core\Helpers\Session;

class HomeController {
    private $imageModel;
    private $albumModel;
    private $userModel;
    
    /**
     * 构造函数
     * @param Image|null $imageModel 图片模型
     * @param Album|null $albumModel 相册模型
     * @param User|null $userModel 用户模型
     */
    public function __construct(
        Image $imageModel = null,
        Album $albumModel = null,
        User $userModel = null
    ) {
        // 如果没有注入依赖，则创建新实例
        $this->imageModel = $imageModel ?? new Image();
        $this->albumModel = $albumModel ?? new Album();
        $this->userModel = $userModel ?? new User();
    }
    
    // 处理主页显示逻辑
    public function index() {
        $albums = $this->albumModel->getAllAlbums();
        $images = $this->imageModel->getAllImages();
        $selectedAlbumId = isset($_GET['album_id']) ? intval($_GET['album_id']) : null;
        $sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'filename_asc';
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
        $selectedAlbumSearch = isset($_GET['album_search']) ? intval($_GET['album_search']) : null;
        
        // 返回数据到视图
        return [
            'albums' => $albums,
            'images' => $images,
            'selectedAlbumId' => $selectedAlbumId,
            'sortBy' => $sortBy,
            'searchQuery' => $searchQuery,
            'selectedAlbumSearch' => $selectedAlbumSearch
        ];
    }
    
    // 处理登出逻辑
    public function logout() {
        Session::destroy();
        header('Location: /src/View/Home/home.php');
        exit();
    }
} 
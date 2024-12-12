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
        
        return [
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
    }
    
    public function logout() {
        Session::destroy();
        header('Location: /');
        exit();
    }
} 
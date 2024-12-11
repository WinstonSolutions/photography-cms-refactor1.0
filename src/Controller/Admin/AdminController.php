<?php
namespace Controller\Admin;

require_once __DIR__ . '/../../Model/Image.php';
require_once __DIR__ . '/../../Model/Album.php';
require_once __DIR__ . '/../../Model/User.php';

class AdminController {
    private $imageModel;
    private $albumModel;
    private $userModel;
    
    public function __construct() {
        $this->imageModel = new \Model\Image();
        $this->albumModel = new \Model\Album();
        $this->userModel = new \Model\User();
    }
    
    // 处理仪表盘显示
    public function dashboard() {
        $usersCount = $this->userModel->getUsersCount();
        $albumsCount = $this->albumModel->getAlbumsCount();
        $imagesCount = $this->imageModel->getImagesCount();
        
        return [
            'usersCount' => $usersCount,
            'albumsCount' => $albumsCount,
            'imagesCount' => $imagesCount
        ];
    }
    
    // 其他管理功能...
} 
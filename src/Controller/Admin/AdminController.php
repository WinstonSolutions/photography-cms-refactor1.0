<?php
namespace App\Controller\Admin;

use App\Model\Image;
use App\Model\Album;
use App\Model\User;

class AdminController {
    private $imageModel;
    private $albumModel;
    private $userModel;
    
    public function __construct() {
        $this->imageModel = new Image();
        $this->albumModel = new Album();
        $this->userModel = new User();
    }
    
    // 处理仪表盘显示
    public function dashboard() {
        $usersCount = $this->userModel->getUsersCount();
        $albumsCount = $this->albumModel->getAlbumsCount();
        $imagesCount = $this->imageModel->getPhotosCount();
        $firstPhotoTime =0;
        
        // 将数据传递给视图
        $viewData = [
            'usersCount' => $usersCount,
            'albumsCount' => $albumsCount,
            'imagesCount' => $imagesCount,
            'firstPhotoTime' => $firstPhotoTime
        ];
        
                // 加载视图文件
       
        require_once __DIR__ . '/../../View/admin/index.php';

    }
    public function photos() {
        $usersCount =1;
        $viewData = [
            'usersCount' => $usersCount,
           
        ];
        // 加载视图文件
        require_once __DIR__ . '/../../View/admin/index.php';
    }

    public function albums() {
        $albumsCount =1;
        $viewData = [
            'albumsCount' => $albumsCount,
        ];
        require_once __DIR__ . '/../../View/admin/index.php';
    }   
    public function users() {
        $usersCount =1;
        $viewData = [
            'usersCount' => $usersCount,
        ];
        require_once __DIR__ . '/../../View/admin/index.php';
    }
        

    // 添加一个新的方法来加载视图
    // private function loadView($viewPath, $data = []) {
    //     extract($data); // 解构数据以便在视图中使用
    //     require_once __DIR__ . '/../../View/' . $viewPath . '.php'; // 加载视图文件
    // }
}

<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 31/10/2016
 * Time: 10:27
 */
require_once("model/data.php");
require_once("model/image.php");
require_once("model/imageDAO.php");

class photo{

    private $data;
    private $imgDAO;
    private $currentImgId;

    # Chemin URL où se trouvent les images
    const urlPath="http://localhost/image/image/model/IMG/";

    public function __construct()
    {
        $this->imgDAO = new imageDAO();
    }

    protected function getParams(){
        global $imageId,$size,$zoom;

        if(isset($_GET["imageId"])){
            $imageId = $_GET["imageId"];
        }else{
            $imageId = 1;
        }

        if(isset($_GET['size'])){
            $size = $_GET["size"];
        }else{
            $size=480;
        }

        if(isset($_GET['zoom'])){
            $zoom = $_GET["zoom"];
        }else{
            $zoom=1.0;
        }
    }

    private function setMenuView(){
        global $data,$imageId,$size,$zoom;
        //charge la vue
        $data->menu['Home'] = "index.php";
        $data->menu['A propos'] = "index.php?controller=home&action=aPropos";
        $data->menu['First'] = "index.php?controller=photo&action=first&imageId=1&size=".$size."&zoom=".$zoom;
        $data->menu['Random'] = "index.php?controller=photo&action=random&imageId=".$imageId."&size=".$size."&zoom=".$zoom;
        $data->menu['More'] = "index.php?controller=photoMatrix&action=more&imageId=".$imageId."&size=".$size."&zoom=".$zoom."&nb=2";
        $data->menu['Zoom +'] = "index.php?controller=photo&action=zoomPlus&imageId=".$imageId."&size=".$size."&zoom=1.25";
        $data->menu['Zoom -'] = "index.php?controller=photo&action=zoomMoins&imageId=".$imageId."&size=".$size."&zoom=0.8";
        $data->content = "view/photoView.php";
        require_once("view/mainView.php");
    }

    private function setContentView(){
        global $data,$imageId,$size,$zoom,$img;
        $data = new data();
        $newImage = $this->imgDAO->getImage($imageId);
        $data->imageURL = $newImage->getURL();
        $data->size = 480;
        $data->prevURL = "index.php?controller=photo&action=prevPicture&imageId=".$imageId."&size=".$size;
        $data->nextURL = "index.php?controller=photo&action=nextPicture&imageId=".$imageId."&size=".$size;
    }

    public function first()
    {
        global $data,$imageId,$size,$zoom;
       // $data = new data();
        $this->getParams();
        $image = $this->imgDAO->getFirstImage();
        $imageId = $image->getId();
        $this->setContentView();
        $this->setMenuView();
    }

    public function nextPicture(){
        global $data,$imageId,$size,$zoom;
        //$data = new data();
        $this->getParams();
        $currentImage = $this->imgDAO->getImage($imageId);
        $nextImage = $this->imgDAO->getNextImage($currentImage);
        $imageId = $nextImage->getId();
        $max = $this->imgDAO->size();
        if($imageId > $max){
            $imageId = $max;
        }
        $this->setContentView();
        $this->setMenuView();
    }

    public function prevPicture(){
        global $data,$imageId,$size,$zoom;
        //$data = new data();
        $this->getParams();
        $currentImage = $this->imgDAO->getImage($imageId);
        $prevImage = $this->imgDAO->getPrevImage($currentImage);
        $imageId = $prevImage->getId();
        if($imageId<=0){
            $imageId=1;
        }
        $this->setContentView();
        $this->setMenuView();
    }

    public function random(){
        global $data,$imageId, $size,$zoom;
        //$data = new data();
        $this->getParams();
        $randomImage = $this->imgDAO->getRandomImage();
        $imageId = $randomImage->getId();
        $this->setContentView();
        $this->setMenuView();
    }

    public function zoomPlus(){
        $data = new data();
        $this->setMenuView();
    }

    public function zoomMoins(){
        $data = new data();
        $this->setMenuView();
    }
}

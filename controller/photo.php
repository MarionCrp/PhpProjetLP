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

class photo
{

    private $data;
    private $imgDAO;
    private $currentImgId;

    # Chemin URL où se trouvent les images
    const urlPath = "http://localhost/image/image/model/IMG/";

    public function __construct()
    {
        $this->imgDAO = new imageDAO();
    }

    protected function getParams()
    {
        global $imageId, $size, $zoom;

        if (isset($_GET["imageId"])) {
            $imageId = $_GET["imageId"];
        } else {
            $imageId = 1;
        }

        if (isset($_GET['size'])) {
            $size = $_GET["size"];
        } else {
            $size = 480;
        }

        if (isset($_GET['zoom'])) {
            $zoom = $_GET["zoom"];
        } else {
            $zoom = 1.0;
        }
    }

    private function setMenuView()
    {
        global $data, $imageId, $size, $zoom;
        //charge la vue
        $data->menu['Home'] = "index.php";
        $data->menu['A propos'] = "index.php?controller=home&action=aPropos";
        $data->menu['First'] = "index.php?controller=photo&action=first&imageId=1&size=" . $size . "&zoom=" . $zoom;
        $data->menu['Random'] = "index.php?controller=photo&action=random&imageId=" . $imageId . "&size=" . $size . "&zoom=" . $zoom;
        $data->menu['More'] = "index.php?controller=photoMatrix&action=more&imageId=" . $imageId . "&size=" . $size . "&zoom=" . $zoom . "&nbImg=1";
        $data->menu['Zoom +'] = "index.php?controller=photo&action=zoomPlus&imageId=" . $imageId . "&size=" . $size . "&zoom=1.25";
        $data->menu['Zoom -'] = "index.php?controller=photo&action=zoomMoins&imageId=" . $imageId . "&size=" . $size . "&zoom=0.8";
        $data->menu['Suppression catégory'] = "index.php?controller=photo&action=updateCategories";
        require_once("view/mainView.php");
    }

    private function setContentView()
    {
        global $data, $imageId, $size, $zoom, $img;
        $data = new data();
        $data->listCat = $this->imgDAO->getCategoryList();

        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'changeCategory':
                    $data->content = "view/changeCategoryView.php";
                    $data->actualCategory = $this->imgDAO->getCategory($imageId);
                    $data->imageId = $imageId;
                    $data->listCat = $this->imgDAO->getCategoryList();
                    break;

                case 'changeCommentary':
                    $data->content = "view/changeCommentaryView.php";
                    $data->actualComment = $this->imgDAO->getCommentary($imageId);
                    $data->imageId = $imageId;
                    break;

                case 'top10Popularity':
                    $listTop = $this->imgDAO->getTop10pop();
                    $urlList= [];
                    foreach ($listTop as $id){
                        $image = $this->imgDAO->getImage($id);
                        array_push($urlList,$image->getURL());
                    }
                    $data->urlListTop = $urlList;
                    $data->content = "view/popularityTopBotView.php";
                    $data->state = "top";
                    $data->urlBack = "index.php?controller=photo&action=show&imageId=" . $imageId . "&size=" . $size;
                    break;

                case 'bot10Popularity':
                    $listBot = $this->imgDAO->getBot10Pop();
                    $urlList= [];
                    foreach ($listBot as $id){
                        $image = $this->imgDAO->getImage($id);
                        array_push($urlList,$image->getURL());
                    }
                    $data->urlListBot = $urlList;
                    $data->content = "view/popularityTopBotView.php";
                    $data->state = "bot";
                    $data->urlBack = "index.php?controller=photo&action=show&imageId=" . $imageId . "&size=" . $size;
                    break;

                case 'updateCategories':
                  $data->listCat = $this->imgDAO->getCategoryList();
                  $data->content = "view/updateCategoriesView.php";
                  break;

                default:
                    $newImage = $this->imgDAO->getImage($imageId);
                    $data->imageURL = $newImage->getURL();
                    $data->size = $size;
                    $data->imageId = $imageId;
                    $data->popularity = $this->imgDAO->getPopularity($imageId);

                    $data->urlLike = "index.php?controller=photo&action=onLikeClick&imageId=" . $imageId;
                    $data->urlDislike = "index.php?controller=photo&action=onDisLikeClick&imageId=" . $imageId;
                    $data->urlTop10Pop = "index.php?controller=photo&action=top10Popularity&imageId=" . $imageId;
                    $data->urlBot10Pop = "index.php?controller=photo&action=bot10Popularity&imageId=" . $imageId;

                    $data->prevURL = "index.php?controller=photo&action=prevPicture&imageId=" . $imageId . "&size=" . $size;
                    $data->nextURL = "index.php?controller=photo&action=nextPicture&imageId=" . $imageId . "&size=" . $size;

                    $data->URLChangeCategory = "index.php?controller=photo&action=changeCategory&imageId=" . $imageId;
                    $data->URLChangeCommentary = "index.php?controller=photo&action=changeCommentary&imageId=" . $imageId;

                    $data->imageCategory = $newImage->getCategory();
                    $data->imageCommentary = $newImage->getCommentary();
                    $data->content = "view/photoView.php";
                    break;
            }
        }
    }

    public function first()
    {
        global $data, $imageId, $size, $zoom;
        // $data = new data();
        $this->getParams();
        $image = $this->imgDAO->getFirstImage();
        $imageId = $image->getId();
        $this->setContentView();
        $this->setMenuView();
    }

    public function nextPicture()
    {
        global $data, $imageId, $size, $zoom;
        //$data = new data();
        $this->getParams();
        $currentImage = $this->imgDAO->getImage($imageId);
        $nextImage = $this->imgDAO->getNextImage($currentImage);
        $imageId = $nextImage->getId();
        $this->setContentView();
        $this->setMenuView();
    }

    public function prevPicture()
    {
        global $data, $imageId, $size, $zoom;
        $this->getParams();
        $currentImage = $this->imgDAO->getImage($imageId);
        $prevImage = $this->imgDAO->getPrevImage($currentImage);
        $imageId = $prevImage->getId();
        $this->setContentView();
        $this->setMenuView();
    }

    public function show()
    {
        global $data, $imageId, $size, $zoom;
        $this->getParams();
        $this->setContentView();
        $this->setMenuView();
    }

    public function random()
    {
        global $data, $imageId, $size, $zoom;
        $this->getParams();
        $randomImage = $this->imgDAO->getRandomImage();
        $imageId = $randomImage->getId();
        $this->setContentView();
        $this->setMenuView();
    }

    public function zoomPlus()
    {
        global $data, $imageId, $size, $zoom;
        $this->getParams();
        $zoom = 1.25;
        $size = $size * $zoom;
        $this->setContentView();
        $this->setMenuView();
    }

    public function zoomMoins()
    {
        global $data, $imageId, $size, $zoom;
        $this->getParams();
        $zoom = 0.75;
        $size = $size * $zoom;
        $this->setContentView();
        $this->setMenuView();
    }


    /* partie pour changer catégorie */
    public function changeCategory()
    {
        global $data, $imageId, $size, $zoom;
        $this->getParams();
        $this->setContentView();
        $this->setMenuView();
    }

    public function validateChangeCategory()
    {
        global $data, $imageId, $size, $zoom;
        if(isset($_POST['new_category']) && !empty($_POST['new_category'])){
          $this->getParams();
          $cat = $_POST['new_category'];
          $this->imgDAO->updateImageCategory($imageId, $cat);
        } else {
          if (isset($_POST["category"])) {
            $this->getParams();
            $cat = $this->imgDAO->getCategoryList()[$_POST["category"]];
            $this->imgDAO->updateImageCategory($imageId, $cat);
          }
        }
        $this->setContentView();
        $this->setMenuView();
    }

    /* partie pour changer commentaire */
    public function changeCommentary()
    {
        global $data, $imageId, $size, $zoom;
        $this->getParams();

        $this->setContentView();
        $this->setMenuView();
    }

    public function validateChangeCommentary()
    {
        global $data, $imageId, $size, $zoom;
        if (isset($_POST["commentary"])) {
            $this->getParams();
            $this->imgDAO->updateImageCommentary($imageId, $_POST["commentary"]);
            $this->setContentView();
            $this->setMenuView();
        }
    }

    public function onLikeClick()
    {
        global $data, $imageId, $size, $zoom;
        $this->getParams();
        $this->imgDAO->updateImagePopularity($imageId, "like");
        $this->setContentView();
        $this->setMenuView();
    }

    public function onDisLikeClick()
    {
        global $data, $imageId, $size, $zoom;
        $this->getParams();
        $this->imgDAO->updateImagePopularity($imageId, "dislike");
        $this->setContentView();
        $this->setMenuView();
    }

    public function top10Popularity(){
        global $data, $imageId, $size, $zoom;
        $this->getParams();
        $this->setContentView();
        $this->setMenuView();
    }

    public function bot10Popularity(){
        global $data, $imageId, $size, $zoom;
        $this->getParams();

        $this->setContentView();
        $this->setMenuView();
    }

    public function updateCategories(){
      global $data;
      $this->getParams();
      $this->setContentView();
      $this->setMenuView();
    }

    public function deleteCategory(){
      global $data;
      $cat = $this->imgDAO->getCategoryList()[$_POST['category']];
      $this->imgDAO->deleteCategory($cat);
      $this->getParams();
      $this->setContentView();
      $this->setMenuView();
    }
}

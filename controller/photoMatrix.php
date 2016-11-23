<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 31/10/2016
 * Time: 10:28
 */
require_once("model/data.php");
require_once("model/image.php");
require_once("model/imageDAO.php");

class photoMatrix
{

    private $data;
    private $imgDAO;

    public function __construct()
    {
        $this->imgDAO = new imageDAO();
    }

    protected function getParams()
    {
        global $data, $imageId, $size, $zoom,$nbImg,$imgMatrixURL, $img;

        if (isset($_GET["imageId"])) {
            $imageId = $_GET["imageId"];
            $img = $this->imgDAO->getImage($imageId);
        } else {
            // Pas d'image, se positionne sur la première
            $img = $this->imgDAO->getFirstImage();
            // Conserve son id pour définir l'état de l'interface
            $imageId = $img->getId();
        }

        // Récupère le nombre d'images à afficher
        if (isset($_GET["nbImg"])) {
            $nbImg = $_GET["nbImg"];
        } else {
            # sinon débute avec 2 images
            $nbImg = 2;
        }
        // Regarde si une taille pour l'image est connue
        if (isset($_GET["size"])) {
            $size = $_GET["size"];
        } else {
            # sinon place une valeur de taille par défaut
            $size = 480;
        }
    }

    private function setMenuView()
    {
        global $data, $imageId, $size, $zoom, $nbImg;
        //charge la vue
        $data->menu['Home'] = "index.php";
        $data->menu['A propos'] = "index.php?controller=home&action=aPropos";
        $data->menu['First'] = "index.php?controller=photoMatrix&action=first&imageId=1&size=" . $size . "&zoom=" . $zoom;
        $data->menu['Random'] = "index.php?controller=photo&action=random&imageId=" . $imageId . "&size=" . $size . "&zoom=" . $zoom;
        $data->menu['More'] = "index.php?controller=photoMatrix&action=more&imageId=" . $imageId . "&size=" . $size . "&zoom=" . $zoom . "&nbImg=" . $nbImg;
        $data->menu['Less'] = "index.php?controller=photoMatrix&action=less&imageId=" . $imageId . "&size=" . $size . "&zoom=" . $zoom . "&nbImg=" . $nbImg;
        $data->content = "view/photoMatrixView.php";
        require_once("view/mainView.php");
    }

    private function setContentView()
    {
        global $data, $imageId, $size, $img, $nbImg, $imgLst, $imgMatrixURL;
        $data = new data();
        $newImage = $this->imgDAO->getImage(1); //récupère l'image (la première du groupe)

        // Réalise l'affichage de l'image
        # Adapte la taille des images au nombre d'images présentes
        $size = 480 / sqrt(count($imgMatrixURL));
        $data->size = $size;
        $data->nbImg =  $nbImg;
        $data->imgId = $imageId;
        $data->prevURL = "index.php?controller=photoMatrix&action=prevPicture&imageId=" . $imageId . "&size=" . $size . "&nbImg=" . $data->nbImg;
        $data->nextURL = "index.php?controller=photoMatrix&action=nextPicture&imageId=" . $imageId . "&size=" . $size . "&nbImg=" . $data->nbImg;

        $data->imgLst = $imgMatrixURL;
    }

    private function init()
    {
        //TODO: init ne fonctionne pas encore -> modifier code
        global $data, $imageId, $size, $zoom, $imgMatrixURL;

        $this->getParams();
        $this->setMenuView();
        $this->setContentView();
    }

    public function more()
    {
        global $data, $imageId, $size, $zoom,$nbImg,$imgMatrixURL;
        $this->getParams();
        $nbImg = $nbImg*2;
        $this->getPictureList();
        $this->setContentView();
        $this->setMenuView();
    }

    public function less()
    {
      global $data, $imageId, $size, $zoom,$nbImg,$imgMatrixURL;
      $this->getParams();
      if($nbImg >= 2) $nbImg = $nbImg/2;
      $this->getPictureList();
      $this->setContentView();
      $this->setMenuView();
    }

    public function first()
    {
        //TODO: first ne fonctionne pas -> modifier code pour MATRIX
        global $data, $imageId, $size, $zoom;
        $this->getParams();
        $image = $this->imgDAO->getFirstImage();
        $imageId = $image->getId();
        $this->setContentView();
        $this->setMenuView();
    }

    public function nextPicture()
    {
        //TODO: next ne fonctionne pas encore
        global $data, $imageId, $size, $zoom, $nbImg, $imgLst;
        $this->getParams();
        $this->getPictureList();
        $currentImage = $this->imgDAO->getImage($imageId); //récupère l'image courante (la première du  groupe)

        // pre-calcul de la page d'images suivante
        $newNextFirstImage = $this->imgDAO->jumpToImage($currentImage, $nbImg); //récupère la première precédente image (première du précédent groupe)
        $imageId = $newNextFirstImage->getId(); //ID de la précédente première image
        $this->setContentView();
        $this->setMenuView();
    }

    public function prevPicture()
    {
        //TODO: prev ne fonctionne pas encore
        global $data, $imageId, $size, $zoom, $nbImg, $imgLst;
        $this->getParams();
        $this->getPictureList();
        $currentImage = $this->imgDAO->getImage($imageId); //récupère l'image courante (la première du  groupe)

        // pre-calcul de la page d'images précédente
        $newPrevFirstImage = $this->imgDAO->jumpToImage($currentImage, -$nbImg); //récupère la première precédente image (première du précédent groupe)
        $imageId = $newPrevFirstImage->getId(); //ID de la précédente première image
        $this->setContentView();
        $this->setMenuView();
    }

    public function getPictureList(){
      global $data, $imageId, $size, $zoom,$nbImg,$imgMatrixURL, $img;

      # Calcul la liste des images à afficher
      $imgLst = $this->imgDAO->getImageList($img, $nbImg);
      # Transforme cette liste en liste de couples (tableau a deux valeurs)
      # contenant l'URL de l'image et l'URL de l'action sur cette image
      foreach ($imgLst as $i) {
          # l'identifiant de cette image $i
          $iId = $i->getId();
          # Ajoute à imgMatrixURL
          #  0 : l'URL de l'image
          #  1 : l'URL de l'action lorsqu'on clique sur l'image : la visualiser seul
          $imgMatrixURL[] = array($i->getURL(), "index.php?controller=photo&action=first&imageId=" . $iId . "&size=" . $size);
      }
    }
}

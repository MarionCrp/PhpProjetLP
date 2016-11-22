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
        if (isset($_GET["imgId"])) {
            $imgId = $_GET["imgId"];
            $img = $this->imgDAO->getImage($imgId);
        } else {
            // Pas d'image, se positionne sur la première
            $img = $this->imgDAO->getFirstImage();
            // Conserve son id pour définir l'état de l'interface
            $imgId = $img->getId();
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
            $imgMatrixURL[] = array($i->getURL(), "index.php?controller=photo&action=first&imgId=" . $iId . "&size=" . $size);
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
        global $data, $imgId, $size, $img, $nbImg, $imgLst, $imgMatrixURL;

        //$newImage = $this->imgDAO->getImage($imgId); //récupère l'image (la première du groupe)

        // Réalise l'affichage de l'image
        # Adapte la taille des images au nombre d'images présentes
        //$size = 480 / sqrt(count($imgMatrixURL));

        $data->prevURL = "index.php?controller=photo&action=prevPicture&imageId=" . $imgId . "&size=" . $size . "&nbImg=" . $nbImg;
        $data->nextURL = "index.php?controller=photo&action=nextPicture&imageId=" . $imgId . "&size=" . $size . "&nbImg=" . $nbImg;

        /*foreach ($imgMatrixURL as $i) {
            $data->imgList['imgURL'] = $i;
        }*/
        $data->imgLst = $imgMatrixURL;

    }

    private function init()
    {
        //TODO: init ne fonctionne pas encore -> modifier code
        global $data, $imgId, $size, $zoom, $imgMatrixURL;

        $this->getParams();
        $this->setMenuView();
        $this->setContentView();
    }

    public function more()
    {
        //TODO: more ne fonctionne pas -> modifier code
        global $data, $imgId, $size, $zoom,$nbImg;
        $this->getParams();
        /*$newNbImg = $nbImg * 2;
        $nbImg = $newNbImg;*/
        $this->setContentView();
        $this->setMenuView();
    }

    public function less()
    {
        //TODO: less ne fonctionne pas -> modifier code
        global $data, $imgId, $size, $zoom,$nbImg;
        $this->getParams();
        $newNbImg = $nbImg / 2;
        $nbImg = $newNbImg;
    }

    public function first()
    {
        //TODO: first ne fonctionne pas -> modifier code pour MATRIX
        global $data, $imgId, $size, $zoom;
        // $data = new data();
        $this->getParams();
        $image = $this->imgDAO->getFirstImage();
        $imgId = $image->getId();
        $this->setContentView();
        $this->setMenuView();
    }

    public function nextPicture()
    {
        //TODO: next ne fonctionne pas encore
        global $data, $imgId, $size, $zoom, $nbImg, $imgLst;
        $this->getParams();
        $currentImage = $this->imgDAO->getImage($imgId); //récupère l'image courante (la première du  groupe)

        // pre-calcul de la page d'images suivante
        $newNextFirstImage = $this->imgDAO->jumpToImage($currentImage, $nbImg); //récupère la première precédente image (première du précédent groupe)
        $imgId = $newNextFirstImage->getId(); //ID de la précédente première image
    }

    public function prevPicture()
    {
        //TODO: prev ne fonctionne pas encore
        global $data, $imgId, $size, $zoom, $nbImg, $imgLst;

        $currentImage = $this->imgDAO->getImage($imgId); //récupère l'image courante (la première du  groupe)

        // pre-calcul de la page d'images précédente
        $newPrevFirstImage = $this->imgDAO->jumpToImage($currentImage, -$nbImg); //récupère la première precédente image (première du précédent groupe)
        $imgId = $newPrevFirstImage->getId(); //ID de la précédente première image
    }
}
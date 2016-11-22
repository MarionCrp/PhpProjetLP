<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 31/10/2016
 * Time: 10:27
 */
require_once("model/data.php");

class home
{
    private $data;

    private function init(data $data){
        //charge la vue
        $data->menu['Home'] = "index.php";
        $data->menu['A propos'] = "index.php?controller=home&action=aPropos";
        $data->menu['Voir photos'] = "index.php?controller=photo&action=first";
        require_once("view/mainView.php");
    }

    public function index()
    {
        $data = new data();
        $data->content = "view/homeView.php";
        $this->init($data);
    }

    public function aPropos()
    {
        $data = new data();
        $data->content = "view/aproposView.php";
        $this->init($data);
    }
}
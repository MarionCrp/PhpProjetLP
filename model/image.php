<?php
  
  # Notion d'image
  class Image {
    private $url=""; 
    private $id=0;
    private $commentary ="";
    private $category="";
    
    function __construct($u,$id,$cat,$com) {
        $this->url = $u;
        $this->id = $id;
        $this->category = $cat;
        $this ->commentary = $com;
    }
    
    # Retourne l'URL de cette image
    function getURL() {
		return $this->url;
    }
    function getId() {
      return $this->id;
    }

    function getCategory(){
        return $this->category;
    }

    function getCommentary(){
        return $this->commentary;
    }
  }
  
  
?>
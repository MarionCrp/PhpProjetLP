<?php
  
  # Notion d'image
  class Image {
    private $url=""; 
    private $id=0;
    private $commentary ="";
    private $category="";
    private $popularity = 0;
    
    function __construct($u,$id,$cat,$com) {
        $this->url = $u;
        $this->id = $id;
        $this->category = $cat;
        $this->commentary = $com;
        $this->popularity = 0;
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
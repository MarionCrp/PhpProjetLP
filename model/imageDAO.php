<?php
	require_once("image.php");
	# Le 'Data Access Object' d'un ensemble images
	class ImageDAO {

		# !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		# A MODIFIER EN FONCTION DE VOTRE INSTALLATION
		# !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		# Chemin LOCAL où se trouvent les images
		private $path="model/IMG";
		# Chemin URL où se trouvent les images
		const urlPath="http://localhost/imageWithMVC/model/img/";

		# Tableau pour stocker tous les chemins des images
		private $imgEntry;

        private $dbh;

		# Lecture récursive d'un répertoire d'images
		# Ce ne sont pas des objets qui sont stockes mais juste
		# des chemins vers les images.
		private function readDir($dir) {
			# build the full path using location of the image base
			$fdir=$this->path.$dir;
			if (is_dir($fdir)) {
				$d = opendir($fdir);
				while (($file = readdir($d)) !== false) {
					if (is_dir($fdir."/".$file)) {
						# This entry is a directory, just have to avoid . and .. or anything starts with '.'
						if (($file[0] != '.')) {
							# a recursive call
							$this->readDir($dir."/".$file);
						}
					} else {
						# a simple file, store it in the file list
						if (($file[0] != '.')) {
							$this->imgEntry[]="$dir/$file";
						}
					}
				}
			}
		}

		function __construct() {
            $this->dbh = new PDO('mysql:host=localhost;dbname=tp2', 'root', '');
		}

		# Retourne le nombre d'images référencées dans le DAO
		function size() {
			$res = $this->dbh->query('SELECT * FROM image',PDO::FETCH_ASSOC);
            //return count($res->fetchAll());
            return $res->rowCount();
		}

		# Retourne un objet image correspondant à l'identifiant
		function getImage($imgId){
           if(!($imgId>=1 and $imgId<=$this->size())){
               $size = $this->size();
               print $size;
               debug_print_backtrace();
               die('error');
            }

            foreach ($this->dbh->query('SELECT * FROM image WHERE id='.$imgId,PDO::FETCH_ASSOC) as $row){
                return new Image(self::urlPath.$row['path'],$row['id'],$row['category'],$row['commentary']);
            }

        }

		# Retourne une image au hazard
		function getRandomImage() {
			$id=rand(1,$this->size());
            $img = $this->getImage($id);
            return $img;
		}

		# Retourne l'objet de la premiere image
		function getFirstImage() {
			return $this->getImage(1);
		}

		# Retourne l'image suivante d'une image
		function getNextImage(image $img) {
			$id = $img->getId();
			$req = $this->dbh->prepare('SELECT * FROM image WHERE id > :id ORDER BY id LIMIT 1');
			$req->execute(array(
				'id' => $id));
			$donnees = $req->fetch(PDO::FETCH_ASSOC);
			if($donnees == false){
				return $img;
			} else {
				return new Image(
					self::urlPath.$donnees['path'],$donnees['id'],$donnees['category'],$donnees['commentary']
				);
			}
		}

		# Retourne l'image précédente d'une image
		function getPrevImage(image $img) {
			$id = $img->getId();
			$req = $this->dbh->prepare('SELECT * FROM image WHERE id < :id ORDER BY id DESC LIMIT 1');
			$req->execute(array(
				'id' => $id));
			$donnees = $req->fetch(PDO::FETCH_ASSOC);
			if($donnees == false){
				return $img;
			} else {
				return new Image(
					self::urlPath.$donnees['path'],$donnees['id'],$donnees['category'],$donnees['commentary']
				);
			}
		}

		# saute en avant ou en arrière de $nb images
		# Retourne la nouvelle image
		function jumpToImage(image $img,$nb) {
			$id=$img->getId();
			if(($id+$nb) < 1 || ($id+$nb) > $this->size()){
				 return $img;
			} else {
				$max_id = $id+$nb;
				$newImg= $this->getImage($max_id);
				return $newImg;
			}
		}

		# Retourne la liste des images consécutives à partir d'une image
		function getImageList(image $img,$nb) {
			# Verifie que le nombre d'image est non nul
			if (!$nb > 0) {
				debug_print_backtrace();
				trigger_error("Erreur dans ImageDAO.getImageList: nombre d'images nul");
			}
			$id = $img->getId();
			if(($id+$nb-1) <= $this->size()){
				$max = $id + $nb - 1;
			} else {
				$max = $this->size();
			}
			while ($id <= $max ) {
				$res[] = $this->getImage($id);
				$id++;
			}
			return $res;
		}

		# Retourne la liste de toute les différentes catégorie enregistrées dans la base
		function getCategoryList(){
			$rqt = $this->dbh->query('SELECT DISTINCT category FROM image');
			$list = $rqt->fetchAll();
			$category_list = [];
			foreach($list as $key => $value){
				array_push($category_list, $value[0]);
			}
			return $category_list;
		}

		# Retourne la catégorie pour un imgId donné
		function getCategory($imgId){
			//return $this->dbh->query('SELECT category FROM image WHERE id='.$imgId,PDO::FETCH_ASSOC);
			$rqt = $this->dbh->query('SELECT category FROM image WHERE id='.$imgId);
			$category = $rqt->fetchColumn();
			return $category;
		}

		# Change la catégorie de l'image
		function updateImageCategory($imgId, $newCategory){
			$rqt = $this->dbh->prepare('UPDATE image SET category = :category WHERE id = :id');
			$rqt->bindValue(':id',$imgId);
			$rqt->bindValue(':category',$newCategory);
			$rqt->execute();
		}

		# Retounrne le commentaire pour un imgId donné
		function getCommentary($imgId){
				$rqt = $this->dbh->query('SELECT commentary FROM image WHERE id='.$imgId);
				$commentary = $rqt->fetchColumn();
				return $commentary;
		}

		# Change le commentaire de l'image
		function updateImageCommentary($imgId, $newComment){
			$rqt = $this->dbh->prepare('UPDATE image SET commentary = :commentary WHERE id = :id');
			$rqt->bindValue(':id',$imgId);
			$rqt->bindValue(':commentary',$newComment);
			$rqt->execute();
			/*echo("cat update");*/
		}

		# Retounre le taux de popularité pour un imgId donné
		function getPopularity($imgId){
			$rqt = $this->dbh->query('SELECT popularity FROM image WHERE id='.$imgId);
			$popularity = $rqt->fetchColumn();
			return $popularity;
		}

		# Change la popularité d'une image
		function updateImagePopularity($imgId, $choix){
			$pop = $this->getPopularity($imgId);
			if($choix == "like"){
				$newPop = (int) $pop+1;
			}elseif ($choix == "dislike"){
				$newPop = (int) $pop-1;
			}
			$rqt = $this->dbh->prepare('UPDATE image SET popularity = :popularity WHERE id = :id');
			$rqt->bindValue(':id',$imgId);
			$rqt->bindValue(':popularity',$newPop);
			$rqt->execute();
		}

		function getTop10pop(){

		}

		function getBot10Pop(){

		}
	}

	# Test unitaire
	# Appeler le code PHP depuis le navigateur avec la variable test
	# Exemple : http://localhost/image/model/imageDAO.php?test
	if (isset($_GET["test"])) {
		echo "<H1>Test de la classe ImageDAO</H1>";
		$imgDAO = new ImageDAO();
		echo "<p>Creation de l'objet ImageDAO.</p>\n";
		echo "<p>La base contient ".$imgDAO->size()." images.</p>\n";
		$img = $imgDAO->getFirstImage("");
		echo "La premiere image est : ".$img->getURL()."</p>\n";
		# Affiche l'image
		echo "<img src=\"".$img->getURL()."\"/>\n";
	}


	?>

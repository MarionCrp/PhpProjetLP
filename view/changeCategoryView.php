<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 23/11/2016
 * Time: 08:40
 */

?>
  <div>
      <h1>Changement de catégorie de l'image</h1>
      <form method="post" action="index.php?controller=photo&action=validateChangeCategory&imageId=<?= $data->imageId?>">
          <p>Catégorie actuelle: <?= $data->actualCategory ?></p>
          <select name="category">
              <?php
                foreach ($data->listCat as $key => $value) {
                  echo "<option value=".$value["category"].">".$value["category"]."</option>";
              }?>
          </select>
          <input type="submit" name="envoyer" value="Sauvegarder"/>
      </form>
  </div>

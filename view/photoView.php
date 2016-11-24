<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 31/10/2016
 * Time: 10:28
 */

require_once ("view/mainView.php");
?>

  <div id="filter_form">
    <form method="post" action="index.php?controller=photo&action=searchByCategory">
        <select name="category">
            <?php
            var_dump($data->listCat);
              foreach ($data->listCat as $key => $value) {
                echo "<option value=".$key.">".$value."</option>";
            }?>
        </select>
        <input type="submit" name="envoyer" value="Chercher"/>
    </form>
  </div>
<?php
print "<p>\n";
print "<a href=\"".$data->prevURL."\">Prev</a> ";
print "<a href=\"".$data->nextURL."\">Next</a>";
print "</p>\n";
print "<img src=\"".$data->imageURL."\"width=\"".$data->size."\">\n";
print "</a>\n";
print "<p>".$data->imageCategory." - <a href=\"".$data->URLChangeCategory."\">Changer catégorie de l'image</a></p>";
print "<p>".$data->imageCommentary." - <a href=\"".$data->URLChangeCommentary."\">Changer commentaire de l'image</p>";
?>

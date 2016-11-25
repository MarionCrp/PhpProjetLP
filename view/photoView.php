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
print  "<a href=\"".$data->urlLike."\">Like</a> ";
print  "<a href=\"".$data->urlDislike."\">Dislike</a>";
print "<p>Nb like: ".$data->popularity.", <a href=\"".$data->urlTop10Pop."\">10 plus populaire</a>, <a href=\"".$data->urlBot10Pop."\">10 moins populaire</a></p>";
print "</p>\n";
print "<img src=\"".$data->imageURL."\"width=\"".$data->size."\">\n";
print "</a>\n";
print "<p>".$data->imageCategory." - <a href=\"".$data->URLChangeCategory."\">Changer catégorie de l'image</a></p>";
print "<p>".$data->imageCommentary." - <a href=\"".$data->URLChangeCommentary."\">Changer commentaire de l'image</p>";
?>

<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 31/10/2016
 * Time: 10:28
 */

require_once ("view/mainView.php");


print "<p>\n";
print "<a href=\"".$data->prevURL."\">Prev</a> ";
print "<a href=\"".$data->nextURL."\">Next</a>";
print "</p>\n";
print "<img src=\"".$data->imageURL."\"width=\"".$data->size."\">\n";
print "</a>\n";
print "<p>".$data->imageCategory." - <a href=\"".$data->URLChangeCategory."\">Changer catégorie de l'image</a></p>";
print "<p>".$data->imageCommentary." - <a href=\"".$data->URLChangeCommentary."\">Changer commentaire de l'image</p>";
?>

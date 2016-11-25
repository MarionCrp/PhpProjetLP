<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 31/10/2016
 * Time: 10:29
 */

require_once("view/mainView.php");
?>

  <div id="filter_form">
    <form method="post" action="index.php?controller=photo&action=<?= $_GET['action'] ?>">
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
print "<a href=\"" . $data->prevURL . "\">Prev</a> ";
print "<a href=\"" . $data->nextURL . "\">Next</a>";
print "</p>\n";
if (is_array($data->imgLst) || is_object($data->imgLst)) {
    foreach ($data->imgLst as $i) {
        print "<a href=\"$i[1]\"><img src=\"".$i[0]."\"width=\"".$data->size."\"height=\"" . $data->size . "\">\n</a>";
    }
};
?>

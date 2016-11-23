<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 31/10/2016
 * Time: 10:29
 */

require_once("view/mainView.php");

print "<p>\n";
print "<a href=\"" . $data->prevURL . "\">Prev</a> ";
print "<a href=\"" . $data->nextURL . "\">Next</a>";
print "</p>\n";
if (is_array($data->imgLst) || is_object($data->imgLst)) {
    foreach ($data->imgLst as $i) {
        print "<img src=\"".$i[0]."\"width=\"".$data->size."\"height=\"" . $data->size . "\">\n";
    }
};
?>
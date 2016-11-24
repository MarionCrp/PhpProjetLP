<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 24/11/2016
 * Time: 20:00
 */

if ($data->state == "top") {
    foreach ($data->urlListTop as $value) {
        print "<img src=\"" . $value . "\"width=480 height=480\">\n";

    }
} elseif ($data->state == "bot") {
    foreach ($data->urlListBot as $value) {
        print "<img src=\"" . $value . "\"width=480 height=480\">\n";

    }
}

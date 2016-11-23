<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 23/11/2016
 * Time: 08:40
 */

?>
<html>
    <div>
        <h1>Changement de catégorie de l'image</h1>
        <form method="post" action="index.php?controller=photo&action=validateChangeCategory&imageId=<?= $data->imageId?>">
            <p>id: <?= $data->imageId ?></p>
            <p>Catégorie actuelle: <?= $data->actualCategory ?></p>
            <select name="category">
                <option value="Tourist">Tourist</option>
                <option>Beltaine</option>
                <option>Attractions</option>
            </select>
            <input type="submit" name="envoyer" value="Envoyer"/>
        </form>
    </div>
</html>

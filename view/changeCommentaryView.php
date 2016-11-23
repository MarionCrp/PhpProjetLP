<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 23/11/2016
 * Time: 14:55
 */
?>
<div>
    <h1>Changement du commentaire de l'image</h1>
    <form method="post" action="index.php?controller=photo&action=validateChangeCommentary&imageId=<?= $data->imageId?>">
        <p>Commentaire actuel: <?= $data->actualComment?></p>
        <br/>
        <label class="labelClass">Nouveau commentaire: <input type="text" name="commentary" required/></label>
        <br/>
        <input type="submit" name="envoyer" value="Sauvegarder"/>
    </form>
</div>

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
        <datalist id="category">
            <option value="NASA"></option>
            <option value="Fish & Wildlife"></option>
            <option value="Ireland"></option>
            <option value="User Pics"></option>
            <option value="Work"></option>
            <option value="Airshow"></option>
            <option value="Plants"></option>
            <option value="Wild Animal"></option>
            <option value="Family"></option>
            <option value="Los Angeles"></option>
            <option value="Home"></option>
            <option value="SCA"></option>
            <option value="Cooking"></option>
            <option value="Balboa Park"></option>
            <option value="Misc"></option>
            <option value="Festivals"></option>
            <option value="Recreation"></option>
            <option value="Ocean"></option>
            <option value="San Diego"></option>
            <option value="SD Zoo"></option>
            <option value="Jon"></option>
            <option value="Tourist"></option>
            <option value="Beltaine"></option>
            <option value="Attractions"></option>
        </datalist>

        <h1>Chamgement de catégorie de l'image</h1>
        <form method="post" action="index.php?controller=photo&action=validateChangeCategory">
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






















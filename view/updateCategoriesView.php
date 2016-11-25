<form method="post" action="index.php?controller=photo&action=deleteCategory">
    <select name="category">
        <?php
          foreach ($data->listCat as $key => $value) {
            ?> <option value="<?= $key ?>"> <?= $value ?> </option>
            <?php
        }?>
    </select>
    <input type="submit" name="envoyer" value="Supprimer"/>
</form>

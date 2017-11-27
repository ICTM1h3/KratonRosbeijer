<?php
if(isset($_GET['dish'])){
    setTitle("Gerecht aanpassen");
}else{
    setTitle("Gerecht toevoegen");
}

if(isset($_POST['save_dish'])){
    base_query("INSERT INTO `dish` (`Category`, `Name`, `Description`, `Price`) VALUES (:Category, :Name, :Description, :Price);", array(
        ':Category' => $_POST['Category'],
        ':Name' => $_POST['Name'],
        ':Description' =>$_POST ['Description'],
        ':Price' => $_POST["Price"])

    );
}

$categories=base_query("SELECT * FROM dishcategory")->fetchAll();
if(empty($categories)){
    echo("Er is geen categorie aangemaakt");
    ?>
    <a href="?p=admin_editcategory">Categorie toevoegen</a>
    <?php
        return;
    }
    ?>

?>
<style>
    textarea {
        resize: none;
    }
</style>
<form method="POST">
<table>
    <th></th>
    <tr>
        <td>Categorie</td>
        <td>
            <select name="Category" >
            <option>Selecteer de categorie</option><?php 

                foreach($categories as $category){
                    ?>
                    <option value="<?= $category['Id'] ?>"><?= $category['Name'] ?></option>
                    <?php   
                }
            
            ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Naam</td>
        <td>
            <input type="text" name="Name" value="">
        </td>
    </tr>
    <tr>
        <td>Omschrijving</td>
        <td>
            <textarea name="Description"></textarea>
        </td>
    </tr>
    <tr>
        <td>Prijs (&#8364;)</td>
        <td>
            <input type="number" step="0.01" name="Price" value="0.00">
        </td>
    </tr>
    <tr>
        <td>
            <input name="save_dish" value="Gerecht opslaan" type="submit"/>
        </td>
    </tr>
</table>
</form>
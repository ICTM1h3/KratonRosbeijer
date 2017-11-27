<?php
//Set the title/subtitle of the page to 'categorie aanpassen'
//and set the title/subtitle of the page to 'categorie toegvoegen'

if(isset($_GET['dish'])){
    setTitle("Categorie aanpassen");
}else{
    setTitle("Categorie toevoegen");
}


$categories=base_query("SELECT * FROM dishcategory")->fetchAll();
?>

<!--Style for the page-->
<style>
    .errors > p {
    color: red;
    }
</style>



<!--Form for adding/change a category-->
<form method="POST">
    <table>
        <tr>
            <td>Naam *</td>
            <td><input type="tekst" name="Name"/></td>
        </tr>
        <tr>
            <td>Titel beschrijving</td>
            <td><input type="tekst" name="Title_description"></td>
        </tr>        
        <tr>
            <td>Categorie beschrijving *</td>
            <td><input type="tekst" name="Category_description"></td>
        </tr>
        <tr>
            <td>Subcategory van</td>
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
            <td>Positie</td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>
</form>
<i>* Velden zijn verplicht</i>
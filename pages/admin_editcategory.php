<?php
//Set the title/subtitle of the page to 'categorie aanpassen'
//and set the title/subtitle of the page to 'categorie toegvoegen'

if(isset($_GET['dish'])){
    setTitle("Categorie aanpassen");
}else{
    setTitle("Categorie toevoegen");
}


$categories=base_query("SELECT * FROM dishcategory")->fetchAll();

//Inserts a new dish into the database with a function.
function insert_menu(){
    
        //Insert the dish into the database.
        base_query("INSERT INTO `dishcategory` (`Name`, `TitleDescription`, `Description`) VALUES ( :Name, :Description, :Price);", array(
            ':Category' => $_POST['Category'],
            ':Name' => $_POST['Name'],
            ':Description' =>$_POST ['Description'],
            ':Price' => $_POST["Price"])
    
        );
    
        header("Location: ?p=admin_managemenu");
    
    }

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
            <td><input type="tekst" name="TitleDescription"></td>
        </tr>        
        <tr>
            <td>Categorie beschrijving *</td>
            <td><input type="text" name="CategoryDescription"></td>
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
        <td>Prijs (&#8364;)</td>
        <td>
            <input type="number" step="0.01" name="Price" value="0">
        </td>
    </tr>
    <tr>
        <td>
            <input name="save_category" value="Category opslaan" type="submit"/>
        </td>
    </tr>
    </table>
</form>
<i>* Velden zijn verplicht.</i><br>
<i>De (sub)category wordt geplaats onderaan in de subcategory.</i><br>
<i>De positie kan gewijzigd worden in het beheeroverzicht van het menu.</i>
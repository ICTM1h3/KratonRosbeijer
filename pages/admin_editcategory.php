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
function insert_category(){
        $Parentcategoryid=0;

        if ($_POST['Category'] != '') {
            $highestPosition = base_query("SELECT MAX(Position) AS HighestPosition FROM dishcategory WHERE ParentCategoryId = :parentCategoryId", [
                ':parentCategoryId' => $_POST['Category']
            ])->fetch()['HighestPosition'];
            $Parentcategoryid = $highestPosition == null ? 0 : $highestPosition + 1;
        } 
        else {
            $highestPosition = base_query("SELECT MAX(Position) AS HighestPosition FROM dishcategory dc1 WHERE ParentCategoryId IS NULL")->fetch()['HighestPosition'];
            $Parentcategoryid = $highestPosition == null ? 0 : $highestPosition + 1;
        }

        //Insert the dish into the database.
        $stmt = base_query("INSERT INTO `dishcategory` (`Name`, `TitleDescription`, `Description`, `ParentCategoryid`, `Position`, `Price`) 
        VALUES ( :Name, :TitleDescription, :Description, :Parentcategoryid, :Position, :Price);", array(
            ':Name' => $_POST['Name'],
            ':TitleDescription' => $_POST['TitleDescription'],
            ':Description' =>$_POST ['CategoryDescription'],
            ':Parentcategoryid' => $_POST['Category'] == '' ? null : $_POST['Category'],
            ':Position' => $Parentcategoryid,
            ':Price' => $_POST['Price']
            )
    
        );
        ("Location: ?p=admin_managemenu");
    
    }

// Checks if the filled in data is complete
function getFilledInDataErrors()
{
    $errors = [];

    if (empty($_POST['Name'])) {
        $errors[] = "Naam is leeg";
    } 
    if (empty($_POST['CategoryDescription'])) {
        $errors[] = "Category beschrijving leeg ";
    } 

    return $errors;
}



$errors = [];

    
// Update or insert the menu.
if (isset($_POST['save_category'])) {
    $errors = getFilledInDataErrors();

    // Only actually insert/update if there are no problems with the filled in values.
    if (empty($errors)) {
        if (isset($_GET['category'])) {
            //IN PROGRESS
            update_category();
        } else {
            insert_category();
            
        }
        
        // Send the administrator to the vacancy overview page.
        header("Location: ?p=admin_managemenu");
    }

}
?>

<!--Style for the page-->
<style>
    .errors > p {
    color: red;
    }
</style>

<!--Print the errors-->
<div class="errors">
    <?php foreach ($errors as $error) {
        ?><p><?= $error ?></p><?php
    }
    ?>
</div>


<!--Form for adding/change a category-->
<form method="POST">
    <table>
        <tr>
            <td>Naam (verplicht)</td>
            <td><input type="tekst" name="Name"/></td>
        </tr>
        <tr>
            <td>Titel beschrijving</td>
            <td><input type="tekst" name="TitleDescription"></td>
        </tr>        
        <tr>
            <td>Categorie beschrijving </td>
            <td><input type="text" name="CategoryDescription"></td>
        </tr>
        <tr>
            <td>Subcategory van</td>
            <td>
            <select name="Category" >
                <option value=''>Selecteer de categorie</option><?php 

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
<i>De (sub)category wordt geplaats onderaan in de (sub)category.</i><br>
<i>De positie kan gewijzigd worden in het beheeroverzicht van het menu.</i>
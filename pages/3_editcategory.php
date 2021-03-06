<?php
//Set the title/subtitle of the page to 'categorie aanpassen'
//and set the title/subtitle of the page to 'categorie toegvoegen'

if(isset($_GET['dish'])){
    setTitle("Categorie aanpassen");
}else{
    setTitle("Categorie toevoegen");
}

//Get the data from the table dishcategory
$categories = base_query("SELECT * FROM dishcategory")->fetchAll();

//Inserts a new dish into the database with a function.
function insert_category(){
    $Parentcategoryid = 0;
        if (!empty($_POST['Category'])) {
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
        base_query("INSERT INTO `dishcategory` (`Name`, `TitleDescription`, `Description`, `ParentCategoryid`, `Position`, `Price`) 
        VALUES ( :Name, :TitleDescription, :Description, :Parentcategoryid, :Position, :Price);", [
            ':Name' => $_POST['Name'],
            ':TitleDescription' => $_POST['TitleDescription'],
            ':Description' =>$_POST ['Description'],
            ':Parentcategoryid' => $_POST['Category'] == '' ? null : $_POST['Category'],
            ':Position' => $Parentcategoryid,
            ':Price' => $_POST['Price']
            
    
        ]);
       
        header("Location: ?p=managemenu");
    
    }
    
//Send the updated category into the database.
function update_category(){
    $category = empty($_POST['Category']) ? null : $_POST['Category'];
    base_query("UPDATE dishcategory
    SET Name = :name,
    TitleDescription =:titledescription,
    Description = :description,
    Price = :price,
    ParentCategoryId = :parentCategoryId
    WHERE Id = :categoryid
    ",[
        ':categoryid' => $_GET['category'],
        ':name' => $_POST['Name'],
        ':titledescription' => $_POST['TitleDescription'],
        ':description' => $_POST['Description'],
        ':parentCategoryId' => $category,
        ':price' => $_POST['Price']
]);
    
}

function getCategoryValue($categoryupdate, $name){
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        return $_POST[$name];
    } elseif (isset($categoryupdate[$name])) {
        return $categoryupdate[$name];
    } else {
        return '';
    }
}

// Checks if the filled in data is complete
function getFilledInDataErrors()
{
    $errors = [];

    if (empty($_POST['Name'])) {
        $errors[] = "Naam is leeg";
    } 
    
    return $errors;
}


$categoryupdate=[];
$errors = [];

    
// Update or insert the menu.
if (isset($_POST['save_category'])) {
    $errors = getFilledInDataErrors();

    // Only actually insert/update if there are no problems with the filled in values.
    if (empty($errors)) {
        if (isset($_GET['category'])) {
            update_category();
        } else {
            insert_category();
            
        }
        
        // Send the administrator to the vacancy overview page.
        header("Location: ?p=managemenu");
    }

}


    //If dish is activated through 'wijzig' get the right id into the form also ad the right subtitle to the form.
    if(isset($_GET['category'])){
        $categoryupdate = base_query("SELECT * FROM dishcategory WHERE Id = :id", [
            ":id" => $_GET['category'],
            ])->fetch();
            
        ?><h2>Categorie aanpassen</h2><?php
    }else{
            
        ?><h2>Categorie toevoegen</h2><?php
}
?>

<!--Style for the page-->
<style>
    .errors > p {
    color: red;
    }
</style>

<!--Print the errors-->
    <?php foreach ($errors as $error) {
        ?><div class="alert alert-danger" role="alert">
        <p><?= $error ?></p></div><?php
    }
    ?>

<!--Form for adding/change a category-->
<div class="container">
      <form method="post" class="form-signin">
      <a class="btn btn-secondary" href="?p=managemenu">Ga terug naar het overzicht</a>
    <table>
        <tr>
            <td>Naam *</td>
            <td><input class="form-control" type="tekst" name="Name" value="<?= getCategoryValue($categoryupdate,'Name') ?>"/></td>
        </tr>
        <tr>
            <td>Titel beschrijving</td>
            <td><input class="form-control" type="tekst" name="TitleDescription" value="<?= getCategoryValue($categoryupdate, 'TitleDescription') ?>"></td>
        </tr>        
        <tr>
            <td>Categorie beschrijving</td>
            <td><input class="form-control" type="text" name="Description" value="<?= getCategoryValue($categoryupdate, 'Description')?>"></td>
        </tr>
        <tr>
            <td>Subcategory van</td>
            <td>
            <select class="form-control" name="Category" >
            <?php 
            
                $currentSubCategory = isset($categoryupdate['ParentCategoryId']) ? $categoryupdate['ParentCategoryId'] : null;
                if ($currentSubCategory == null) {
                    ?><option value=''>Selecteer de categorie</option><?php
                }
                else {
                    ?><option value=''>Maak hoofdcategorie</option><?php
                }
                
                foreach($categories as $category){
                    ?>
                    <option value="<?= $category['Id'] ?>" <?= $category['Id'] == $currentSubCategory ? 'selected' : '' ?>><?= $category['Name'] ?></option>
                    <?php   
                }
            

        
            ?>

            </select>
            </td>
        </tr>
        <tr>
        <td>Prijs (&#8364;)</td>
        <td>
            <input class="form-control" type="number" step="0.01" name="Price" value="<?= getCategoryValue($categoryupdate, 'Price')?>">
        </td>
    </tr>
    <tr>
        <td>
            <input class="btn btn-secondary" name="save_category" value="Category opslaan" type="submit"/>
        </td>
    </tr>
    </table>
</form>
</div>
<i>* Is verpicht.</i><br>
<i>De (sub)category wordt geplaats onderaan in de (sub)category.</i><br>
<i>De positie kan gewijzigd worden in het beheeroverzicht van het menu.</i>
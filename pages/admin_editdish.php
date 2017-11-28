<?php

//Set title of the page to 'gerecht aanpassen' when the admin wants to change a dish 
//and set the title of the page to 'gerecht toevoegen' when the admin wants to add a dish.
if(isset($_GET['dish'])){
    setTitle("Gerecht aanpassen");
}else{
    setTitle("Gerecht toevoegen");
}


//Inserts a new dish into the database with a function.
function insert_menu(){

    //Insert the dish into the database.
    base_query("INSERT INTO `dish` (`Category`, `Name`, `Description`, `Price`) VALUES (:Category, :Name, :Description, :Price);", array(
        ':Category' => $_POST['Category'],
        ':Name' => $_POST['Name'],
        ':Description' =>$_POST ['Description'],
        ':Price' => $_POST["Price"])

    );

    header("Location: ?p=admin_managemenu");

}

// Checks if the filled in data is complete
function getFilledInDataErrors()
{
    $errors = [];
    if($_POST['Category']=="Selecteer de categorie"){
        $errors[]="Er is geen categorie geselecteerd";
    }
    
    if (empty($_POST['Name'])) {
        $errors[] = "Naam is leeg";
    } 
    
    if (empty($_POST['Description'])) {
        $errors[] = "Omschrijving is leeg";
    } 
    
    if ($_POST['Price']==0) {
        $errors[] = "Prijs mag niet 0 zijn";
    } 

    return $errors;
}



$errors = [];


// Update or insert the menu.
if (isset($_POST['save_dish'])) {
    $errors = getFilledInDataErrors();

    // Only actually insert/update if there are no problems with the filled in values.
    if (empty($errors)) {
        if (isset($_GET['dish'])) {
            //IN PROGRESS
            update_menu();
        } else {
            insert_menu();
        }
        
        // Send the administrator to the vacancy overview page.
        header("Location: ?p=admin_managemenu");
    }

}


//Set the subtitle to what the user wants, change a dish or add a dish to the database.
if (isset($_GET['dish'])) {
    ?><h2>Gerecht aanpassen</h2><?php

} else {
    ?><h2>Gerecht toevoegen</h2><?php

}

//Check if ther is an category in the database.
//If ther is one add it to the list. If there is none give the user the option to go to the page for adding a category.
$categories=base_query("SELECT * FROM dishcategory")->fetchAll();
if(empty($categories)){
    echo("Er is geen categorie aangemaakt");
    ?>
    <a href="?p=admin_editcategory">Categorie toevoegen</a>
    <?php
        return;
    }
    ?>

<!--Style for the page-->
<style>
    textarea {
        resize: none;
    }
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

<!--Form for adding/change a dish.-->
<form method="POST">
<table>
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
            <input type="number" step="0.01" name="Price" value="0">
        </td>
    </tr>
    <tr>
        <td>
            <input name="save_dish" value="Gerecht opslaan" type="submit"/>
        </td>
    </tr>
</table>
</form>
<i>Alle velden zijn verplicht</i>
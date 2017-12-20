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
    $dishposition = 0;

        //Add the right position to the dish.
        $highestPosition = base_query("SELECT MAX(Position) AS HighestPosition FROM dish WHERE Category = :categoryId", [
            ':categoryId' => $_POST['Category']
        ]) ->fetchColumn();
        $dishposition = $highestPosition == null ? 0 : $highestPosition +1; 

    //Insert the dish into the database.
    base_query("INSERT INTO `dish` (`Category`, `Name`, `Description`, `Price`, `Position`) VALUES (:Category, :Name, :Description, :Price, :Position);", [
        ':Category' => $_POST['Category'],
        ':Name' => $_POST['Name'],
        ':Description' =>$_POST ['Description'],
        ':Price' => $_POST["Price"],
        ':Position' => $dishposition
    ]);

    //When a dish is inserted into the database let the admin go to the managemenu page.
    header("Location: ?p=managemenu");

}

//Update the dish and put it into the database with a function.
function update_menu(){

        //Update the dish into the database.
        base_query("UPDATE dish
        SET Category = :category,
            Name = :name,
            Description= :description,
            Price= :price
        WHERE Id = :dishId", [
            ':category' => $_POST['Category'],
            ':name' => $_POST['Name'],
            ':description' => $_POST['Description'],
            ':price' => $_POST['Price'],
            ':dishId' => $_GET['dish']
        

        ]);
}

//Get the right data into the form, when the admin wants to change the data.
function getDishValue($dishupdate, $name)
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        return $_POST[$name];
    } elseif (isset($dishupdate[$name])) {
        return $dishupdate[$name];
    } else {
        return '';
    }
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

    return $errors;
}


$dishupdate=[];
$errors = [];


// Update or insert the menu.
if (isset($_POST['save_dish'])) {
    $errors = getFilledInDataErrors();

    // Only actually insert/update if there are no problems with the filled in values.
    if (empty($errors)) {
        if (isset($_GET['dish'])) {
            update_menu();
        } else {
            insert_menu();
        }
        
        // Send the administrator to the vacancy overview page.
        header("Location: ?p=managemenu");
    }

}




//Check if ther is an category in the database.
//If ther is one add it to the list. If there is none give the user the option to go to the page for adding a category.
$categories = base_query("SELECT * FROM dishcategory")->fetchAll();
if(empty($categories)){
    echo("Er is geen categorie aangemaakt");
    ?>
    <a href="?p=editcategory">Categorie toevoegen</a>
    <?php
        return;
}

//If dish is activated through 'wijzig' get the right id into the form also ad the right subtitle to the form.
if(isset($_GET['dish'])){
        $dishupdate = base_query("SELECT * FROM dish WHERE Id = :id", [
            ":id" => $_GET['dish'],
            ])->fetch();
            
        ?><h2>Gerecht aanpassen</h2><?php
    }else{
            
        ?><h2>Gerecht toevoegen</h2><?php
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
    <?php foreach ($errors as $error) {
        ?><div class="alert alert-danger">
        <p><?= $error ?></p></div><?php
    }
    ?>

<!--Form for adding/change a dish.-->
<div class="container">
      <form method="post" class="form-signin">
      <a class="btn btn-secondary" href="?p=managemenu">Ga terug naar het overzicht</a>
      <table>
      <tr>
        <td>Categorie</td>
        <td>
            <select class="form-control" name="Category" >
            <?php 
            
                $currentCategory = isset($dishupdate['Category']) ? $dishupdate['Category'] : null;
                if ($currentCategory == null) {
                    ?><option>Selecteer de categorie</option><?php
                }
                
                foreach($categories as $category){
                    ?>
                    <option value="<?= $category['Id'] ?>" <?= $category['Id'] == $currentCategory ? 'selected' : '' ?>><?= $category['Name'] ?></option>
                    <?php   
                }
            
            ?>
            </select>
        </td>
        </tr>
        <tr>
        <td>Naam</td>
        <td>
            <input class="form-control" type="text" name="Name" value="<?=getDishValue($dishupdate, 'Name') ?>">
        </td>
        </tr>
        <tr>
        <td>Omschrijving</td>
        <td>
            <textarea class="form-control" name="Description" ><?=getDishValue($dishupdate, 'Description') ?></textarea>
        </td>
        </tr>
        <tr>
        <td>Prijs (&#8364;)</td>
        <td>
            <input class="form-control" type="number" step="0.01" name="Price" value="<?=getDishValue($dishupdate, 'Price') ?>">
        </td>
        </tr>
        <tr>
        <td>
            <input class="btn btn-secondary" name="save_dish" value="Gerecht opslaan" type="submit"/>
        </td>
        </tr>
</table>
</form>

<i>Alle velden zijn verplicht</i>
</div>
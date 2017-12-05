<?php
//Set title
setTitle("Beheren menu");

//If requested, remove the provided dishes
if (isset($_POST['delete']) && isset($_POST['dishesToRemove'])) {
    foreach($_POST['dishesToRemove'] as $iddish){
        base_query("DELETE FROM dish WHERE Id = :dishid", [':dishid'=>$iddish]);

    }
}

//If requested, remove the provided dishes, only when a category is empty (no dishes or subcategory).
if(isset($_POST['delete']) && isset($_POST['categoriesToRemove'])){
    foreach($_POST['categoriesToRemove'] as $idcategory){
        base_query("DELETE FROM dishcategory WHERE Id = :categoryid", [':categoryid'=>$idcategory]);

    }

}

if (isset($_POST['move_category_down'])) {
    $categoryId = $_POST['categoryid'];
    $category = base_query("SELECT * FROM dishcategory WHERE id = :id", [':id' => $categoryId])->fetch();

    $categoryAbove = base_query("SELECT * FROM dishcategory WHERE Position > :targetPosition AND ParentCategoryId <=> :parentCategory ORDER BY Position", [
        ':parentCategory' => $category['ParentCategoryId'],
        ':targetPosition' => $category['Position']
    ])->fetch();

    // var_dump();

    base_query("UPDATE dishcategory SET Position = Position + 1 WHERE id = :id", [':id' => $categoryId]);
    base_query("UPDATE dishcategory SET Position = :newPosition WHERE Id = :id", [':id' => $categoryAbove['Id'], ':newPosition' => $categoryAbove['Position']]);
}


// Boolean. true when the user is trying to delete vacancies, false otherwise.
$changingModus = isset($_GET['changingModus']) ? ($_GET['changingModus'] == 'true') : false;
$changingPlace = isset($_GET['changingPlace']) ? ($_GET['changingPlace'] == 'true') : false;
?>

<!-- Style of the page-->
<style>
    .errors > p {
    color: red;
    }

    */
    ul {
        list-style: none;
    }
    
    * {
        font-family: Arial;
    }
    
    #categoryPrice {
        float:right;
        font-weight: normal;
        font-size:17px;
    }
    
    #price {
        float:right;
    }
</style>



<!--Form for adding/changing categories/dishes-->
<form method="POST">


<div>
    <div>
        <a href="?p=admin_editdish">
            Gerechten toevoegen
        </a>
    </div>

    <div>
        <a href="?p=admin_editcategory">
            Categorie toevoegen
        </a>
    </div>
    <div>
    <div>
        <?php if($changingModus){?>
        <a href="?p=admin_managemenu">
            Terug
        </a>
        <?php }else{?>
            <a href="?p=admin_managemenu&changingModus=true">
            Menu items wijzigen of verwijderen
        </a>
        <?php }?>
    </div>
    <div>
    <?php if($changingPlace){?>
    <a href="?p=admin_managemenu">
        Terug
    </a>
    <?php }else{?>
        <a href="?p=admin_managemenu&changingPlace=true">
        Menu items verplaatsen
    </a>
    <?php }?>
</div>
</div>





    
<?php

function echoCategory($categoryId, $changingModus, $changingPlace, $size = 1) {
    //Getting the categories from the database
    $subcategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    $category = base_query("SELECT * FROM DishCategory WHERE Id = :categoryId", [':categoryId' => $categoryId])->fetch();
    //Getting the dishes from the database and put them in the right (sub)category
    $dishes = base_query("SELECT * FROM Dish WHERE Category = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
?>
<!-- Echo category name -->
<div style="margin-left:<?= $size * 10 ?>px">
    <?php if ($changingModus){
        if (empty($subcategories) && empty($dishes)){ ?>
        <input type="checkbox" name="categoriesToRemove[]" style="float:left;position:relative;left:-20px" value="<?= $categoryId ?>"/>

        <?php }
        } ?>

        <h<?= $size ?>>
        <?= $category['Name']?> 
        </h<?= $size ?>> 
        <?= $category['TitleDescription'] ?>

        <?php if ($changingModus) { ?>
        <a href="?p=admin_editcategory&category=<?= $categoryId?>">Wijzig category</a>
        <?php } elseif($changingPlace){
           ?> <form method="POST">
               <input type='submit' name="move_category_down" value="Naar beneden"/>
               <input type='hidden' name="categoryid" value="<?= $categoryId ?>"/>
                </form>
                <form method="POST">
               <input type='submit' name="move_category_up" value="Naar boven"/>
               <input type='hidden' name="categoryid" value="<?= $categoryId ?>"/>
                </form>
        <?php } ?>

        
        <!-- Checks if a category has a price attached to itself, if the price is not set (value is 0) then dont give the price -->
        <?php if(isset($category['Price']) && $category['Price'] != 0.00) {
            ?><span id='categoryPrice'><?= $category['Price'] ?></span><?php
        } 
    ?>
 
<!-- Echo category description -->
     <i><p>
        <?= $category['Description'] ?>
    </p></i> 


 
  <ul> <?php
foreach ($dishes as $dishValue)
    {
        ?><li>
            <?php if ($changingModus) {
                ?><input type="checkbox" name="dishesToRemove[]" style="float:left;position:relative;left:-20px" value="<?= $dishValue['Id'] ?>"/>
                <b><?= $dishValue['Name']?></b><a href="?p=admin_editdish&dish=<?= $dishValue['Id']?>">Wijzig gerecht</a><span id="price"><?= $dishValue['Price'] ?></span><?= "<br>" . "" . $dishValue['Description']?>
                
                <?php
            }else{ ?>
                <b><?= $dishValue['Name']?></b><span id="price"><?= $dishValue['Price'] ?></span><?= "<br>" . "" . $dishValue['Description']?> 
        <?php
            }
    }
?> </ul>
<?php 
// If there are still subcategories the function will keep being called upon
    if (!empty($subcategories)) {
        foreach ($subcategories as $category) {
            echoCategory($category['Id'], $changingModus, $changingPlace, $size + 1);
        }
        
    }
    // If there are no subcategories anymore the function will echo all the dishes attached to the category
    ?>
    </div>
<?php 
    
}

// Calling upon the function with 'Headcategories'
$mainCategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId IS NULL ORDER BY Position")->fetchAll();
foreach ($mainCategories as $category) {
    echoCategory($category['Id'], $changingModus, $changingPlace);
}
?>
    <?php if ($changingModus) { 
        // Show a delete button if we're in delete mode 
        ?><input type="submit" name="delete" value="Verwijder geselecteerde onderdelen"/><?php
    } ?>
</form>
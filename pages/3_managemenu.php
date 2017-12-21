<?php
//Set the title of the page.
setTitle("Beheren menu");

//If requested, change the status of the dishes.
if (isset($_POST['switch_status']) && isset($_POST['dishesToRemove'])) {
    foreach($_POST['dishesToRemove'] as $iddish){
        base_query("UPDATE dish SET ");
        base_query("DELETE FROM dish WHERE Id = :dishid", [':dishid'=>$iddish]);

    }
}

//If requested, remove the provided dishes, only when a category is empty (no dishes or subcategory).
if(isset($_POST['switch_status']) && isset($_POST['categoriesToRemove'])){
    foreach($_POST['categoriesToRemove'] as $idcategory){
        base_query("DELETE FROM dishcategory WHERE Id = :categoryid", [':categoryid'=>$idcategory]);

    }

}
//Move a category down when requested.
if (isset($_POST['move_category_down'])) {
    $categoryId = $_POST['categoryid'];
    $category = base_query("SELECT * FROM dishcategory WHERE id = :id", [':id' => $categoryId])->fetch();

    $categoryAbove = base_query("SELECT * FROM dishcategory WHERE Position > :targetPosition AND ParentCategoryId <=> :parentCategory ORDER BY Position", [
        ':parentCategory' => $category['ParentCategoryId'],
        ':targetPosition' => $category['Position']
    ])->fetch();

    

    base_query("UPDATE dishcategory SET Position = :newPosition WHERE Id = :id", [':id' => $categoryId, ':newPosition' => $categoryAbove['Position']]);
    base_query("UPDATE dishcategory SET Position = :newPosition WHERE Id = :id", [':id' => $categoryAbove['Id'], ':newPosition' => $category['Position']]);
}

//Move a category up when requested.
if (isset($_POST['move_category_up'])) {
    $categoryId = $_POST['categoryid'];
    $category = base_query("SELECT * FROM dishcategory WHERE id = :id", [':id' => $categoryId])->fetch();

    $categoryAbove = base_query("SELECT * FROM dishcategory WHERE Position < :targetPosition AND ParentCategoryId <=> :parentCategory ORDER BY Position DESC", [
        ':parentCategory' => $category['ParentCategoryId'],
        ':targetPosition' => $category['Position']
    ])->fetch();

    

    base_query("UPDATE dishcategory SET Position = :newPosition WHERE Id = :id", [':id' => $categoryId, ':newPosition' => $categoryAbove['Position']]);
    base_query("UPDATE dishcategory SET Position = :newPosition WHERE Id = :id", [':id' => $categoryAbove['Id'], ':newPosition' => $category['Position']]);
}

//Move a dish down when requested. 
if (isset($_POST['move_dish_down'])){
    $dishid = $_POST['dishid'];
    $dish = base_query("SELECT * FROM dish Where Id = :id", [':id' => $dishid]) ->fetch();

    // $dishAbove = base_query("SELECT * FROM dish WHERE Pos = :id AND category = :samecategory ORDER BY id", [
    $dishAbove = base_query("SELECT * FROM dish WHERE Position > :targetPosition AND Category = :sameCategory ORDER BY Position", [
        ':targetPosition' => $dish['Position'],
        ':sameCategory' => $dish['Category']
    ])->fetch();

    base_query("UPDATE dish SET Position = :newPosition WHERE Id = :id", [':id' => $dishid, ":newPosition" => $dishAbove['Position']]);
    base_query("UPDATE dish SET Position = :newPosition WHERE Id = :id", [':id' => $dishAbove['Id'], ':newPosition' => $dish['Position']]);
}

//Move a dish up when requested. 
if (isset($_POST['move_dish_up'])){
    $dishid = $_POST['dishid'];
    $dish = base_query("SELECT * FROM dish Where Id = :id", [':id' => $dishid]) ->fetch();

    // $dishAbove = base_query("SELECT * FROM dish WHERE Pos = :id AND category = :samecategory ORDER BY id", [
    $dishAbove = base_query("SELECT * FROM dish WHERE Position < :targetPosition AND Category = :sameCategory ORDER BY Position DESC", [
        ':targetPosition' => $dish['Position'],
        ':sameCategory' => $dish['Category']
    ])->fetch();

    base_query("UPDATE dish SET Position = :newPosition WHERE Id = :id", [':id' => $dishid, ":newPosition" => $dishAbove['Position']]);
    base_query("UPDATE dish SET Position = :newPosition WHERE Id = :id", [':id' => $dishAbove['Id'], ':newPosition' => $dish['Position']]);
}


//Change status of the dish.
if (isset($_POST['switch_status_dish'])) {
    // Deactivate the specified dish
    base_query("UPDATE dish SET Activated = NOT Activated WHERE Id = :id", [
        ':id' => $_POST['iddish']
    ]);
}

//Change status of the category.
if (isset($_POST['switch_status_category'])) {
    // Deactivate the specified dish
    base_query("UPDATE dishcategory SET Activated = NOT Activated WHERE Id = :id", [
        ':id' => $_POST['idcategory']
    ]);
}

// Boolean. true when the user is trying to delete vacancies, false otherwise.
$changingModus = isset($_GET['changingModus']) ? ($_GET['changingModus'] == 'true') : false;
$changingPlace = isset($_GET['changingPlace']) ? ($_GET['changingPlace'] == 'true') : false;
?>

<!-- Style of the page-->
<style>
    ul {
        list-style: none;
    }
    
    * {
        font-family: Arial;
    }
    
    .categoryPrice {
        float:right;
        font-weight: normal;
        font-size:17px;
    }
    
    .price {
        float:right;
    }

    .menu_button{
    border-style: solid;
    color: black;
    padding: 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer; 
    border-radius: 50%; 
    }

    .menu_button:hover{
        background-color: #E8E7ED;
    }

    a {
        color: inherit; /* blue colors for links too */
        text-decoration: inherit; /* no underline */
    }

    a:hover{
        color: inherit; /* blue colors for links too */
        text-decoration: inherit; /* no underline */
    }

    .option_table{
        width: 100%;
        text-align: center;
    }
  
    .category_header {
        display: inline-block
    }
</style>


<h2>Beheren menu</h2>
<!--Form for adding/changing categories/dishes-->
<form method="POST">


<table class="option_table">
    <tr>
        <td>
        <div class="menu_button">
            <a href="?p=editdish">
                Gerecht toevoegen
            </a>
        </div>
        </td>
        <td>
        <div class="menu_button">
            <a href="?p=editcategory">
                Categorie toevoegen
            </a>
        </div>
        </td>
        <td>
        <div>
        <div class="menu_button">
            <?php if($changingModus){?>
            <a href="?p=managemenu">
                Terug
            </a>
            <?php }else{?>
                <a href="?p=managemenu&changingModus=true">
                Menu items wijzigen
            </a>
            <?php }?>
        </div>
        </td>
        <td>
        <div class="menu_button">
        <?php if($changingPlace){?>
        <a href="?p=managemenu">
            Terug
        </a>
        <?php }else{?>
            <a href="?p=managemenu&changingPlace=true">
            Menu items verplaatsen
        </a>
        <?php }?>
        </td>
    </tr>
    </div>
</table>


<?php


function echoCategory($categoryId, $changingModus, $changingPlace, $size = 1, $isFirst, $isLast) {
    $category = base_query("SELECT * FROM DishCategory WHERE Id = :categoryId", [':categoryId' => $categoryId])->fetch();
    if (!$changingModus && ($category['Activated'] == 0)) {
        return;
    }
    
    //Getting the categories from the database
    $subcategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    //Getting the dishes from the database and put them in the right (sub)category
    $dishes = base_query("SELECT * FROM Dish WHERE Category = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();

    
?>

<!-- Echo category name -->
<div style="margin-left:<?= $size * 10 ?>px">


    <!--Add the right buttons, only when the use can do something when clicking on the button.-->
    <h<?= $size ?> class="category_header">
    <?= $category['Name']?> 
    </h<?= $size ?>> 
    <?php 
    if ($changingModus) { 
    ?>
        
        <!-- Checks if a category has a price attached to itself, if the price is not set (value is 0) then dont give the price -->
        <?php if(isset($category['Price']) && $category['Price'] != 0.00) { ?>
            <span class='categoryPrice'><?= number_format($category['Price'], 2, ',', '.') ?></span>
        <?php } ?>

        <p><?= $category['TitleDescription'] ?></p>

        <a href="?p=editcategory&category=<?= $categoryId?>">Wijzig category</a>
        <form method="POST">
            <input type="hidden" name="idcategory" value="<?= $categoryId?>" />
            <input type="submit" name="switch_status_category" value="<?= $category['Activated'] == 0 ? 'Activeer' : 'Deactiveer' ?>"/>
        </form>
    <?php
    } elseif($changingPlace){
    ?>
        <!-- <h<?= $size ?>>
        <?= $category['Name']?> 
        </h<?= $size ?>> 
        <?= $category['TitleDescription']?> -->
        <?php
        if(!$isLast){
        ?> 
        <form method="POST">
            <input type='submit' name="move_category_down" value="Naar beneden"/>
            <input type='hidden' name="categoryid" value="<?= $categoryId ?>"/>
        </form>
        <?php 
        }   
        if(!$isFirst){
        ?>
            <form method="POST">
               <input type='submit' name="move_category_up" value="Naar boven"/>
               <input type='hidden' name="categoryid" value="<?= $categoryId ?>"/>
            </form>
        <?php 
        }
    }
    ?>
        <!-- <h<?= $size ?>>
        <?= $category['Name']?> 
        </h<?= $size ?>> 
        <?= $category['TitleDescription'] ?> -->

 
<!-- Echo category description -->
     <i><p>
        <?= $category['Description'] ?>
    </p></i> 


 
<ul> 
<?php
//Printing the right dishes and put the right chaning options.
$maxValueDish = count($dishes) - 1;
for ($i = 0; $i <= $maxValueDish; $i++) {
    $dishValue = $dishes[$i];
    $isFirst = $i == 0;
    $isLast = $i == $maxValueDish;

    $price = (($dishValue['Price'] != '0.00') && !empty($dishValue['Price'])) ? number_format($dishValue['Price'], 2, ',', '.') : '';
        ?><li>
            <?php if ($changingModus) { ?>
                <form method="POST">
                    <input type="hidden" name="iddish" value="<?= $dishValue['Id']?>" />
                    <input type="submit" name="switch_status_dish" value="<?= $dishValue['Activated'] == 0 ? 'Activeer' : 'Deactiveer' ?>">
                </form>
                <b><?= $dishValue['Name']?></b>
                <a href="?p=editdish&dish=<?= $dishValue['Id']?>">Wijzig gerecht</a>
                <span class="price"><?= $price ?></span>
                <?= "<br>" . "" . $dishValue['Description']?>
                
                <?php
            } elseif($changingPlace){ ?>
                <b><?= $dishValue['Name']?></b><span class="price"><?= $price ?></span><?= "<br>" . "" . $dishValue['Description']?>
                <?php if(!$isLast){ ?>
                    <form method="POST">
                        <input type='submit' name="move_dish_down" value="Naar beneden"/>
                        <input type='hidden' name="dishid" value="<?= $dishValue['Id']?>"/>
                    </form>

                <?php } 
                if(!$isFirst){ ?>
                <form method="POST">
                    <input type='submit' name="move_dish_up" value="Naar boven"/>
                    <input type='hidden' name="dishid" value="<?= $dishValue['Id'] ?>"/>
                </form> 
                <?php } ?>
            <?php } else{ ?>
        
                <b><?= $dishValue['Name']?></b><span class="price"><?= $price ?></span><?= "<br>" . "" . $dishValue['Description']?>
            <?php }
}
?> 
</ul>
<?php 

// If there are still subcategories the function will keep being called upon
if (!empty($subcategories)) {
    $maxValueCategory = count($subcategories) -1;
        for($i = 0; $i <= $maxValueCategory; $i++){
            $category = $subcategories[$i];
            $isFirst = $i == 0;
            $isLast = $i == $maxValueCategory;
            echoCategory($category['Id'], $changingModus, $changingPlace, $size + 1, $isFirst, $isLast);
        }
        
    }
    // If there are no subcategories anymore the function will echo all the dishes attached to the category
    ?>
    </div>
<?php 
    
}

// Calling upon the function with 'Headcategories'
$mainCategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId IS NULL ORDER BY Position")->fetchAll();
$maxValueHeadCategories = count($mainCategories) -1;
for($i = 0; $i <= $maxValueHeadCategories; $i++){
        $category = $mainCategories[$i];
        $isFirst = $i == 0;
        $isLast = $i == $maxValueHeadCategories;
        echoCategory($category['Id'], $changingModus, $changingPlace, 1, $isFirst, $isLast);
}

//Give an message when there are no items when in changingmodus or in changingplacemodus.
if(($changingModus || $changingPlace) && empty($mainCategories)){
        echo ("Er zijn geen items om te verwijderen/wijzigen. Ga terug."); 
}
?>
</form>

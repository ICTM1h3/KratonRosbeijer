<?php


function echoCategory($categoryId, $size = 1) {
    $subcategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    $category = base_query("SELECT * FROM DishCategory WHERE Id = :categoryId", [':categoryId' => $categoryId])->fetch();
?>
<!-- Echo category name -->
    <h<?= $size ?>>
        <?= $category['Name'] ?>
    </h<?= $size ?>> 
<?php
// If a category has a price attached, the price will be shown
    if (isset($category['Price'])) {
?> 
    <span style="float:right">
        <?= $category['Price'] ?>
    </span> 
<?php
    }

?> 
<!-- Echo category description -->
     <i><p>
        <?= $category['Description'] ?>
    </p></i> 

<?php
// If there are still subcategories the function will keep being called upon
    if (!empty($subcategories)) {
        foreach ($subcategories as $category) {
            echoCategory($category['Id'], $size + 1);
        }
    }
    // If there are no subcategories anymore the function will echo all the dishes attached to the category
    else {
        $dishes = base_query("SELECT * FROM Dish WHERE Category = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
?>  <ul> <?php
        foreach ($dishes as $dishValue)
        {
            ?><li><?= $dishValue['Name'] . " " . $dishValue['Description'] . " " . $dishValue['Price']; ?><?php
        }
    ?> </ul> 
<?php 
    }
}

// Calling upon the function with 'Headcategories'
$mainCategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId IS NULL ORDER BY Position")->fetchAll();
foreach ($mainCategories as $category) {
    echoCategory($category['Id']);
}
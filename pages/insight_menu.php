<?php

function echoCategory($categoryId, $size = 1) {
    $subcategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    $category = base_query("SELECT * FROM DishCategory WHERE Id = :categoryId", [':categoryId' => $categoryId])->fetch();
    ?>

<!-- Echo category name -->
    <h<?= $size ?>>
        <?= $category['Name']?>
        <!-- Checks if a category has a price attached to itself -->
        <?php if(isset($category['Price'])) {
            ?><span id='categoryPrice'><?= $category['Price'] ?></span><?php
        } ?>
    </h<?= $size ?>> 

<!-- Echo category description -->
     <i><p>
        <?= $category['Description'] ?>
    </p></i> 

<?php

    // If there are still subcategories the function will keep being called upon
    $dishes = base_query("SELECT * FROM Dish WHERE Category = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    // var_dump($dishes);
    foreach($dishes as $dishValue){ ?>
       <ul> <?php
            ?><li><span id="dishTitle"><?= $dishValue['Name']?></span><span id="price"><?= $dishValue['Price'] ?></span><?= "<br>" . "" . $dishValue['Description']?><?php
        ?> </ul>
    <?php
    }
        foreach ($subcategories as $category) {
            echoCategory($category['Id'], $size + 1);
        }
    }

// Calling upon the function with 'Headcategories'
$mainCategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId IS NULL ORDER BY Position")->fetchAll();
foreach ($mainCategories as $category) {
    echoCategory($category['Id']);
}
?>

<style>

ul {
    list-style: none;
}

* {
    font-family: Arial;
}

#dishTitle {
    font-weight: bold;
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
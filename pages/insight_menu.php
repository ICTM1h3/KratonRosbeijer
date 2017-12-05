<?php

function echoCategory($categoryId, $size = 1) 
{
    $subcategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    $category = base_query("SELECT * FROM DishCategory WHERE Id = :categoryId", [':categoryId' => $categoryId])->fetch();
    $dishes = base_query("SELECT * FROM Dish WHERE Category = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    
    ?>
    <!-- Echo category name -->
    <div style="margin:<?= $size * 10 ?>px">
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

        foreach($dishes as $dishValue)
        { ?>
            <ul>
        <li><span id="dishTitle"><?= $dishValue['Name']?></span><span id="price"><?php if($dishValue['Price'] != 0.00){?><?= $dishValue['Price']?><?php }?></span><?= "<br>" . "" . $dishValue['Description']?>
            </ul>
        <?php
        }
        foreach ($subcategories as $category) 
        {
            echoCategory($category['Id'], $size + 1);
        }
        ?></div><?php
    }

// Calling upon the function with 'Headcategories'
$mainCategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId IS NULL ORDER BY Position")->fetchAll();
if(empty($mainCategories))
{
    echo ("Er zijn geen gerechten op dit moment");
}
else
{
    foreach ($mainCategories as $category) 
        {
            echoCategory($category['Id']);
        }
}
?>

<a href="?p=download_menu&no_layout=true" id="muchWow">Download het menukaart</a>

<style>

ul {
    list-style: none;
}

* {
    font-family: Arial;
}

body {
    background-image: url('pages/MenuBackground.jpg');
    margin: 0px;
}

#muchWow {
    margin-top: 20px;
  
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
<?php

setTitle('Menukaart inzien');

if (!empty($mainCategories)) {
    $BG = "EMPTY";
} else {
    $BG = "aBody";
}?>
<div class="<?=$BG?>"><?php

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
            <?php if($category['Price'] != 0.00) {
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
<?php 
    if(!empty($mainCategories)){?>
<a href="?p=download_menu&no_layout=true" id="muchWow" style="display:non";>Download het menukaart</a>
        <?php
    }
?>
</div>
<style>

.aBody {
    overflow: auto; height: 100%; width: 800px; margin: 0 auto; /* center */ padding: 0 20px;
    border-width: 0 1px;
    background-image: url('pages/MenuBackground.jpg');
    background-size: 840px;
    background-repeat: repeat-y;
    background-position: center;
}

@page {
    margin: 0;
}

ul {
    list-style: none;
}

* {
    font-family: Arial;
}

body {

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
    display: inline-block;
}

#price {
    float:right;
    display: inline-block;
}

</style>
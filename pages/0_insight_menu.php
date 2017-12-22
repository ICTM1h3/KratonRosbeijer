<?php
// function to set the tilte page
setTitle('Menukaart inzien');


function echoCategory($categoryId, $size = 1) 
{
    $category = base_query("SELECT * FROM DishCategory WHERE Id = :categoryId", [':categoryId' => $categoryId])->fetch();
    // Don't show categories which are disabled
    if ($category['Activated'] == 0) {
        return;
    }
// variabels with queries to get the correct info from the database
    $subcategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    $dishes = base_query("SELECT * FROM Dish WHERE Category = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    ?>
    <!-- Echo category name -->
    
        <h<?= $size ?>>
            <?= $category['Name']?>
            <!-- Checks if a category has a price attached to itself -->
            <?php if($category['Price'] != 0.00) {
                ?><span class='categoryPrice'><?= number_format($category['Price'], 2, ',', '.') ?></span><?php
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
                <li>
                    <span class="dishTitle"><?= $dishValue['Name']?></span>
                    <span class="price"><?php if($dishValue['Price'] != 0.00){?>
                        <?= number_format($dishValue['Price'], 2, ',', '.') ?>
                    <?php }?>
                    </span>
                    <p><?=  $dishValue['Description']?></p>
            </ul>
        <?php
        }
        foreach ($subcategories as $category) 
        {
            ?><div style ="margin-left:<?=$size * 10?>px"><?php
            echoCategory($category['Id'], $size + 1);
            ?></div><?php
        }
        ?><?php
    }

// Calling upon the function with 'Headcategories'
$mainCategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId IS NULL ORDER BY Position")->fetchAll();
if(empty($mainCategories))
{
    echo ("Er zijn geen gerechten op dit moment");
}
else
{
    ?><div style="margin:3px"><?php
    foreach ($mainCategories as $category) 
    {
        echoCategory($category['Id']);
    }
    ?></div><?php
}
?>
<?php 
    if(!empty($mainCategories) && !isset($_GET['no_layout'])){ ?>
        <a class="btn btn-info" href="?p=download_menu&no_layout=true" id="download-link" >Download de menukaart</a>
<?php
    }

?>

<style>


p {
    max-width: 50%;
    margin-top: 2px;
}

@page {
    margin: 0;
}

ul {
    list-style: none;
}

* {
    /* font-family: Arial; */
}

<?php if (isset($_GET['no_layout'])) { ?>
body {
    padding-top:260px;
    top: 0;
    left: 0;

    background-image: url('img/MenuBackground.jpg');
    background-position: left top;
    background-repeat: repeat-y;
}
<?php } ?>

#download-link {
    margin-top: 20px;
}


.dishTitle {
    font-weight: bold;
}

.categoryPrice {
    float:right;
    font-weight: normal;
    font-size:17px;
    display: inline-block;
}

.price {
    float:right;
    display: inline-block;
}

</style>
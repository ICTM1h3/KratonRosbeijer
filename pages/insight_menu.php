<?php

setTitle('Menukaart inzien');


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
                ?><span class='categoryPrice'><?= $category['Price'] ?></span><?php
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
                        <?= $dishValue['Price']?>
                    <?php }?>
                    </span>
                    <p><?=  $dishValue['Description']?></p>
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
    if(!empty($mainCategories) && !isset($_GET['no_layout'])){ ?>
        <a href="?p=download_menu&no_layout=true" id="download-link" >Download het menukaart</a>
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
    font-family: Arial;
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
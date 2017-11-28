<?php
setTitle("Beheren menu");

$queryDish = base_query('SELECT * FROM Dish')->fetchAll();
$queryCategory = base_query('SELECT * FROM DishCategory')->fetchAll();

?>
<
<style>
    .menu_container {
        display:flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .menu_container > div {
        width:49%;
        padding:2px;
        overflow-wrap: break-word;
    }

    .menu_container > div:nth-child(n+3) > div:first-child {
        width:100%;
        border-bottom: solid black 1px;
    }

    .menu_container > div:nth-child(n+3) > div:first-child > span {
        font-weight:bold;
    }

    .menu_container > div:nth-child(n+3) > div:first-child > a, .menu_container > div:nth-child(n+3) > div:first-child > input {
        font-style: italic;
        float:right;
    }

    .menu_container > div:first-child, .menu_container > div:nth-child(2)  {
        text-align:center;
    }

    .menu_container > div:first-child div, .menu_container > div:nth-child(2)  div {
        border: 1px solid black;
        border-radius: 50%;
        width: 100px;
        height: 100px;
        margin-left: auto;
        margin-right: auto;
        font-size: 100px;
        line-height: 100px;
    }

    .menu_button a {
        color: inherit;
        text-decoration: none;
    }
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


<form method="POST">


<div class="menu_container">
    <div class="menu_button">
        <a href="?p=admin_editdish">
            Gerechten toevoegen
        <div>+</div>
        </a>
    </div>

    <div class="menu_button">
        <a href="?p=admin_editcategory">
            Categorie toevoegen
        <div>+</div>
        </a>
    </div>
</div>


    
<!--<table>
    <th>Menu</th>
    <tr>
        <td>
        <?php
            $category= base_query("SELECT * FROM dishcategory")->fetchAll(); 
            var_dump ($category);
        ?>
        </td>
    </tr>
    </table>

</form>-->

<?php

function echoCategory($categoryId, $size = 1) {
    $subcategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    $category = base_query("SELECT * FROM DishCategory WHERE Id = :categoryId", [':categoryId' => $categoryId])->fetch();
?>
<!-- Echo category name -->
<div style="margin-left:<?= $size * 10 ?>px">
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
            ?><li>- <?= $dishValue['Name']?><span id="price"><?= $dishValue['Price'] ?></span><?= "<br>" . "" . $dishValue['Description']?><?php
        }
    ?> </ul> 
    </div>
<?php 
    }
}

// Calling upon the function with 'Headcategories'
$mainCategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId IS NULL ORDER BY Position")->fetchAll();
foreach ($mainCategories as $category) {
    echoCategory($category['Id']);
}
?>
<?php
//set title to the correct name
setTitle("Gerechten toevoegen");

function str_starts_with($value, $start){
    return substr($value, 0, strlen($start)) === $start;
}

$total = 0;
$subTotal = 0;
$cumulative = 0;
$sDishes = [];
$sCategories = [];
$sDish = [];
$sCategory = [];
$dishName = [];
$categoryName = [];
$dishPrices = [];
$categoryPrices = [];
$dishSubTotal = [];
$categorySubTotal = [];
$dishCumulative = [];
$categoryCumulative = [];
$dishNames = [];
$categoryNames = [];
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    foreach ($_POST as $key => $value) {
        if (str_starts_with($key, 'dish_amount_') && !empty($value)) { 
            $id = substr($key, 12);
            $dishPrice = base_query("SELECT * FROM Dish WHERE Id = :dishId", [':dishId' => $id])->fetch();
            $total += ($value * $dishPrice['Price']);
            $subTotal = ($value * $dishPrice['Price']);
            $cumulative += $subTotal;
            $dishPrices[] = $dishPrice['Price'];
            $dishSubTotal[] = $subTotal;
            $dishCumulative[] = $cumulative;
            $sDish[] = $dishPrice['Id'];
            $sDishes[] = $dishPrice['Id'];
            $dishName[] = $dishPrice['Name'];
            for ($i = 1; $i < $value; $i++) {
                $sDishes[] = $dishPrice['Id'];
                $dishNames[] = $dishPrice['Name'];
            }
        } elseif (str_starts_with($key, 'category_amount_') && !empty($value)) {
            $id = substr($key, 16);
            $categoryPrice = base_query("SELECT * FROM DishCategory WHERE Id = :categoryId", [':categoryId' => $id ])->fetch();
            $total += ($value * $categoryPrice['Price']);
            $subTotal = ($value * $categoryPrice['Price']);
            $cumulative += $subTotal;
            $categoryPrices[] = $categoryPrice['Price'];
            $categorySubTotal[] = $subTotal;
            $categoryCumulative[] = $cumulative;
            $sCategory[] = $categoryPrice['Id'];
            $sCategories[] = $categoryPrice['Id'];
            $categoryName[] = $categoryPrice["Name"];
        for ($i = 1; $i < $value; $i++) {
            $sCategories[] = $categoryPrice['Id'];
            $categoryNames[] = $categoryPrice['Name'];
        }
        }
    }
    // Creating 2 types of sessions for dishes and categories so that the amount for each dish can be counted
    $_SESSION["dishes"] = $sDishes;
    $_SESSION["categories"] = $sCategories;
    $_SESSION["dish"] = $sDish;
    $_SESSION["category"] = $sCategory;
    $_SESSION["totalPrice"] = $total;
    $_SESSION["categoryname"] = $categoryName;
    $_SESSION["dishname"] = $dishName;
    $_SESSION["dishPrices"] = $dishPrices;
    $_SESSION["categoryPrices"] = $categoryPrices;
    $_SESSION["dishSubTotal"] = $dishSubTotal;
    $_SESSION["categorySubTotal"] = $categorySubTotal;
    $_SESSION["dishCumulative"] = $dishCumulative;
    $_SESSION["categoryCumulative"] = $categoryCumulative;
    if (empty($sDish) && empty($sCategory)){
        echo "U heeft geen gerechten geselecteerd";
    }
    else {
    header("Location: ?p=Order_confirm");
    }
}

function echoCategory($categoryId, $size = 1) 
{
    $subcategories = base_query("SELECT * FROM DishCategory WHERE ParentCategoryId = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    $category = base_query("SELECT * FROM DishCategory WHERE Id = :categoryId", [':categoryId' => $categoryId])->fetch();
    $dishes = base_query("SELECT * FROM Dish WHERE Category = :categoryId ORDER BY Position", [':categoryId' => $categoryId])->fetchAll();
    ?>
    <!-- Echo category name -->
    
        <h<?= $size ?>>
            <?= $category['Name']?>
            <!-- Checks if a category has a price attached to itself -->
            <?php if($category['Price'] != 0.00) {
                ?><span class='categoryPrice'><?= number_format($category['Price'], 2, ',', '.');
                if(!empty($category['Price']) && $category['Price'] != 0.00){
                ?><input class="form-control" type="number" name="category_amount_<?= $category['Id'] ?>" min="0" value = "0" formmethod="POST" class="inputNumber"></span><?php }
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
                        <?= number_format($dishValue['Price'], 2, ',', '.')?>
                    <?php }
                    if(!empty($dishValue['Price']) && $dishValue['Price'] != 0.00) {?>
                        <input class="form-control" type="number" name="dish_amount_<?= $dishValue['Id'] ?>" value = "0" min="0" class="inputNumber">
                    <?php } ?>
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
    ?><form method="POST">
    <div style="margin:3px"><?php
    foreach ($mainCategories as $category) 
    {
        echoCategory($category['Id']);
    }
    ?><input class="btn btn-primary" type="submit" name="bestel" value="Bestel!">
    </form>
    </div><?php
}?>

<style>

.form-control {
    margin: 0px;
    width: 30%;
    padding: 4px;
}

.inputNumber {
    width: 17%;
    text-align: center;
}

.overview_dishes {
    border: 1px black solid;
}

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
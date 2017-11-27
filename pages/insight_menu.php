<?php

$queryDish = base_query('SELECT * FROM Dish')->fetchAll();
$queryCategory = base_query('SELECT * FROM DishCategory')->fetchAll();

?>
<style> 

* {
    font-family:Arial;
}

#categoryName 
{
    font-weight: bold;
    font-size: 15px;
}

</style>

<?php
// Going through each category
foreach ($queryCategory as $categoryValue)
{
    $catName = $categoryValue['Name'];
?> 
<!-- Printing the category name -->
    <span id="categoryName"> <?= $catName . "<br>" ?> </span>
    <?php
    // Each category goes through each dish
    foreach ($queryDish as $dishValue) 
    {
        //Checking if a category has dish(es) attached to itself
        if ($dishValue['Category'] == $categoryValue['Id'])
        {
            $name = $dishValue['Name'];
            $description = $dishValue['Description'];
            $price = $dishValue['Price'];
        ?>
        <table>
            <tr>
                <td> 
                    <?= $name ?> 
                </td>
                <td> 
                    <?= $description ?> 
                </td>
                <td> 
                    <?= $price ?> 
                </td>
            </tr>
        </table>
        <?php
        }
    }
}

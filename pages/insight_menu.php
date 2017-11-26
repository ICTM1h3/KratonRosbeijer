<?php

 /*$query = base_query('SELECT * FROM Dish D JOIN DishCategory DC ON D.Category = DC.Id ORDER BY DC.Id')->fetchAll();
$query_category = base_query('SELECT * FROM DishCategory WHERE SubCategoryId IS NULL')->fetchAll(); */

$queryDish = base_query('SELECT * FROM Dish');
$queryCategory = base_query('SELECT * FROM DishCategory');

foreach ($queryDish as $dishValue)
{
    echo $dishValue['Id'] . "<br>";
    echo $dishValue['Name'] . "<br>";
    echo $dishValue['Description'] . "<br>";
    echo $dishValue['Category'] . "<br><br><br>";
}

foreach ($queryCategory as $categoryValue)
{
    echo $categoryValue['Id'] . "<br>";
    echo $categoryValue['Name'] . "<br>";
    echo $categoryValue['SubCategoryId'] . "<br>";
}
<?php 
setTitle("Inzien gerechten");

$order = [];

$order = base_query("SELECT * FROM `order` WHERE Id = :id", [
    ":id" => $_GET['dishes']
    ])->fetch();


$dishes = base_query("SELECT DishId FROM dish_order WHERE OrderId = :id", [
    ":id" => $_GET['dishes']
]);
var_dump ($dishes);

$dish = base_query("SELECT * FROM dish WHERE Id = :dishid",[
    ":dishid" => $dishes
]);
var_dump($dish);
?>

<form method="POST">

</form>
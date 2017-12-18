<?php 
//Set the title of the page.
setTitle("Inzien gerechten");

//Get the dishes of the order from the database. 
$dishes = base_query("SELECT D.*, O.* FROM Dish D
            JOIN Dish_Order Q on D.Id = Q.DishId
            JOIN `Order` O on Q.OrderId = O.Id
            WHERE OrderId = :order",[
                ":order" => $_GET['dishes']
            ])->fetchAll();

//Get the number of dishes from the database. 
$number_of_dish = base_query ("SELECT * FROM Dish_Order
            WHERE OrderId = :orderid",[
                ":orderid" => $_GET['dishes']
            ])->fetchAll();

//Getting the personal information from the database.
$person = base_query("SELECT * FROM `order` WHERE Id = :id",[
                ":id" => $_GET['dishes']
            ])->fetchAll();



//Getting the number of dishes into the table.
foreach($number_of_dish as $number){
    $numberofdish[] = $number['CountDish'];
}
?>


<!--Style of the page-->
<style>
table, tr, td {
    border: 1px solid black;
    border-collapse: collapse;
    width: 66%;

}
</style>
<h2>Bestelling</h2>

<!--Form for showing the personal information.-->
<form>
    <table>
        <?php
        foreach($person as $info){
        ?>
        <tr>
            <td>Op naam van</td>
            <td><?= $info['InNameOf']?></td>
        </tr>
        <tr>
            <td>Telefoonnummer</td>
            <td><?= $info['TelephoneNumber']?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= $info['Email']?></td>
        </tr>

        <?php
        }    
        ?>
    </table>
    </form>
<!--Form for showing the dishes.-->
<form method="POST">
    <table>
        <tr>
            <td>Naam Gerecht</td>
            <td>Beschrijving</td>
            <td>Prijs</td>
            <td>Aantal</td>
        </tr>
        <?php
        foreach($dishes as $key => $dish){
            ?>
        <tr>
            <td><?= $dish['Name']?></td>
            <td><?= $dish['Description']?></td>
            <td><?= $dish['Price']?></td>
            <td><?= $numberofdish[$key] ?> </td>
        </tr>
        <?php
        }
        ?>      
    </table>
</form>



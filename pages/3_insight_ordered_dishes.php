<?php 
//Set the title of the page.
setTitle("Inzien bestelde gerechten");

//Get the categories of the order from the database. 
$categories = base_query("SELECT C.*, O.*, Q.CountCategory, C.Price AS Price FROM DishCategory C
            JOIN Category_Order Q on C.Id = Q.CategoryId
            JOIN `Order` O on Q.OrderId = O.Id
            WHERE OrderId =:order", [
                ":order" => $_GET['dishes']
            ])->fetchAll();

//Get the dishes of the order from the database. 
$dishes = base_query("SELECT D.*, O.*, Q.CountDish, D.Price AS Price FROM Dish D
            JOIN Dish_Order Q on D.Id = Q.DishId
            JOIN `Order` O on Q.OrderId = O.Id
            WHERE OrderId = :order",[
                ":order" => $_GET['dishes']
            ])->fetchAll();


//Getting the personal information from the database.
$person = base_query("SELECT * FROM `order` WHERE Id = :id",[
            ":id" => $_GET['dishes']
            ])->fetch();


//Getting the total price of the order.
$total = base_query("SELECT Price FROM `order` WHERE Id = :id",[
             ":id" => $_GET['dishes']
            ])->fetchColumn();

//Cancel the order
if(isset($_POST['cancel_order'])){
    base_query("UPDATE `order` SET Activated = 0");
    header("Location: ?p=manageorders");
}



//Save the date of finish the order.
if(isset($_POST['Finish'])){
    $date = format_date_and_time($_POST['Date'], $_POST['Time']);
    base_query ("UPDATE `order` SET FinishDate = :dateandtime WHERE Id= :id", [
        ":dateandtime" => $date,
        ":id" => $_GET['dishes']
        ]);

        if($date < $FinishDate){
            echo "Geen geldige tijd of tijd is eerder dan de besteldatum!";
        
        }else{
            echo"niets";
            header("Location: ?p=manageorders");
        }
        
        
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

<a href="?p=manageorders">Ga terug</a>

<!--Form for showing the personal information.-->
<form>
    <table>
        <tr>
            <td>Op naam van</td>
            <td><?= htmlentities($person['InNameOf'])?></td>
        </tr>
        <tr>
            <td>Telefoonnummer</td>
            <td><?= htmlentities($person['TelephoneNumber'])?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= htmlentities($person['Email'])?></td>
        </tr>
        <tr>
            <td>Besteldatum</td>
            <td><?= htmlentities($person['OrderDate'])?></td>
        </tr>
        <tr>
            <td>Afhaaldatum</td>
            <td><?= htmlentities($person['TargetDate'])?></td>
        </tr>
        <tr>
            <td>Betaald</td>
            <td>
            <?php 
            if($person['PayementStatus']== 0){
                echo "Bestelling door administrator";
            }else{
                echo "Betaald";
            }
            ?>
            </td>        
        </tr>
        </table>
        <table>
        <tr>
            <td>Naam Gerecht</td>
            <td>Beschrijving</td>
            <td>Aantal</td>
        </tr>
        <?php
        foreach($dishes as $key => $dish){
        ?>
        <tr>
            <td><?= $dish['Name']?></td>
            <td><?= $dish['Description']?></td>
            <td><?= $dish['CountDish']?></td>  
        </tr>
        <?php
        }
        foreach($categories as $key => $category){
        ?>
        <tr>
            <td><?= $category['Name']?></td>
            <td>
            <?php
            if(empty($category['TitleDescription'])){
                echo "Geen beschrijving gevonden";
            }else{
                echo $category['TitleDescription'];

            }
            ?>    
            </td>
            <td><?= $category['CountCategory']?></td>

        </tr>
        <?php
        }
        ?>
        <tr>
            <td>Totaal bedrag</td>
            <td>€ <?= $total ?></td>
        </tr>
    </table>
    <?php 
    //Option to cancel the order.
    if($person['Activated'] == 0){
        echo "Bestelling is geannuleerd";
    }else{
    ?>
        <input type="submit" name="cancel_order" value="Annuleren"/> 
    <?php
    }
    ?>
</form>


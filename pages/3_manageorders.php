<?php
//Set the title of the page.
setTitle("Beheren bestellingen");


//Set the IsNowShow to 1 when the order is picked up.
if(isset($_POST['pickedup'])){
    base_query("UPDATE `order` SET IsPickedUp = :ispickedup WHERE Id = :orderid",[
        ":ispickedup" => $_POST['pickedup'],
        ":orderid" => $_POST['OrderId']
        ]);
    }
    

// Use the specified date or today's date if not specified.
$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

// Get the date and time which we can use to compare to dates in the database.
$currentDate = date("Y-m-d H:i:s");

// Get all the orders on the specified day.
$orders = base_query("SELECT * 
FROM `order`
WHERE TargetDate BETWEEN :startDate AND :endDate
ORDER BY TargetDate", [
    ':startDate' => $date . ' 00:00:00',
    ':endDate' => $date . ' 23:59:59'
])->fetchAll();
?>

<h2>Overzicht bestellingen</h2>

<!--Form for going to the right date with orders.-->
<form action="?p=manageorders">
    <input type="hidden" name="p" value="<?= $_GET['p'] ?>">
    <label for="date">Bestellingen voor</label>
    <input id="date" value="<?= $date ?>" type="date" name="date" onchange="this.form.submit()" />
</form>

<!--Chek of there are orders on this date-->
<?php 
if(empty($orders)){ 
?>
<p>Op deze datum zijn geen bestellingen.</p>
<?php 
}else{ 
?>

 <!--Form for showing the orders-->
<div class="table-responsive">
    <table class = "table">
    <tr>
        <th>Op naam van </th>
        <th>Email </th>
        <th>Telefoonnummer </th>
        <th>Besteldatum </th>
        <th>Afhaaldatum </th>
        <th>Afgehaald</th>
        <th>Status</th>
    </tr>
    <?php
    foreach($orders as $order){
    ?>
    <tr>
        <td><?= $order['InNameOf']?></td>
        <td><?= $order['Email']?></td>
        <td><?= $order['TelephoneNumber']?></td>
        <td><?= $order['OrderDate']?></td>
        <td><?= $order['TargetDate']?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="OrderId" value="<?= $order["Id"] ?>">
                <input type="hidden" name="pickedup" value="<?= $order['IsPickedUp'] ?>">
                <?php
                if($order['Activated']== 0){
                ?>
                Kan niet meer worden afgehaald
                <?php
                }else{
                ?>
                <input type="checkbox" <?= $order['IsPickedUp'] == 1 ? 'checked' : ''?> onclick="this.previousElementSibling.value=this.checked ? 1 : 0; this.form.submit();"/>
                <?php
                }
                ?>
            </form>
        </td>
        <?php
        if($order['Activated']== 0){
        ?>
        <td>Bestelling is geannuleerd</td>
        <?php
        }else{
        ?>
        <td>Geactiveerde bestelling</td>
        <?php
        }    
        ?>
        <td><a href="?p=insight_ordered_dishes&dishes=<?= $order['Id']?>">Inzien bestelling</a></td>
    </tr>
    <?php
    }
    ?>
</table>
</div>
<?php 
} 
?>
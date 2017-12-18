<?php
setTitle("Beheren bestellingen");

$orders = base_query('SELECT * FROM `order`ORDER BY TargetDate')->fetchAll();
?>

<!--Style of the page-->
<style>
table, tr, td {
    border: 1px solid black;
    border-collapse: collapse;
    width: 66%;

}
</style>

<h2>Overzicht bestellingen</h2>
<form method="GET"> 
    <div class="talbe-responsive">
    <table>
    <?php
   foreach($orders as $order){?>
    <tr>
        <td>Op naam van </td>
        <td>Email </td>
        <td>Telefoonnummer </td>
        <td>Besteldatum </td>
        <td>Afhaaldatum </td>
        <td>Klaar op </td>

    </tr>
    <tr>
        <td><?= $order['InNameOf']?></td>
        <td><?= $order['Email']?></td>
        <td><?= $order['TelephoneNumber']?></td>
        <td><?= $order['OrderDate']?></td>
        <td><?= $order['TargetDate']?></td>
        <td><?php
        if($order['FinishDate'] == "0000-00-00 00:00:00"  || empty($order['Finishdate'])){
            echo "Niet af!";
        }else{
            echo $order['FinishDate'];
        } ?></td>
        <td><a href="?p=insight_ordered_dishes&dishes=<?= $order['Id']?>">Inzien gerechten</a></td>
        <td><a href="?p=change_order">Wijzig gegevens</a></td>
    </tr>
    <?php
        }
    ?>
</table>
</div>
</form>
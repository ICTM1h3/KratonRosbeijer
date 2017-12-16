<?php
setTitle("Beheren bestellingen");

$orders = base_query('SELECT * FROM `order`')->fetchAll();
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
<form method="POST"> 
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
        if($order['FinishDate'] == "0000-00-00 00:00:00"){
            echo "Niet af!";
        }else{
            echo $order['FinishDate'];
        } ?></td>
    </tr>
    <?php
        }
    ?>
</table>
</form>
<?php
setTitle("Beheer cadeaubonnen");

//Retrieve all current giftcards.
$giftcards = base_query("SELECT * FROM coupon");
?>

<h2>Aangemaakte cadeaukaarten</h2>
<form>
    <table>
        <?php 
        foreach($giftcards as $card){
        ?>
        <tr>
            <td>Gegevens</td>
        </tr>
        <tr>
            <td>Oorspronkelijke koper:</td>
            <td><?= $card['InNameOf']?></td>
        </tr>    
        <?php }?>
    </table>
</form>
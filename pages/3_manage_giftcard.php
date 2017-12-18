<?php
//Set the title of the page
setTitle("Beheer cadeaubonnen");



//Update the value of the giftcard or give an error.
function changeGiftCard($code, $value) {
    if (base_query("SELECT InitialValue FROM Coupon WHERE CouponCode = :couponcode", [':couponcode' => $code])->fetchColumn() < $value || $value < 0) {
        echo "Waarde is kleiner dan 0 of is hoger dan het beginbedrag van de kaart!";
        
    }else{
        base_query("UPDATE coupon SET CurrentValue = :currentvalue WHERE CouponCode = :couponcode",[
            ':currentvalue' => $value,
            ':couponcode' => $code
            ]);
            
            header("Location: ?p=manage_giftcard");
        }
    }
    
    //Get the right coupon the admin wants to change. 
    if (isset($_POST['changecoupon'])) {
        foreach ($_POST['changecoupon'] as $couponCode => $value) {
            changeGiftCard($couponCode, $_POST['currentvalue'][$couponCode]);
        }
        
    }
    
//Retrieve all current giftcards.
$giftcards = base_query("SELECT * FROM coupon ORDER BY CurrentValue DESC")->fetchAll();
?>

<!--Style of the page-->
<style>
table, tr, td {
    border: 1px solid black;
    border-collapse: collapse;
    width: 66%;
}
</style>

<h2>Aangemaakte cadeaukaarten</h2>

<!-- Form for showing the giftcards, if the exist-->
<form method="POST">
    <div class="table-responsive">
    <table><?php
        if(!empty($giftcards)){?>
            <tr>
                <td>Code</td>
                <td>Oorspronkelijk tegoed</td>
                <td>Tegoed</td>
                <td>Oorspronkelijke besteller</td>
                <td>E-mail</td>
            </tr>
        <?php
            foreach($giftcards as $card){ ?>
            <tr>
                <td><?= $card['CouponCode']?></td>
                <td><?= $card['InitialValue']?></td>
                <td><input type="text" name="currentvalue[<?= $card['CouponCode']?>]" value="<?= $card['CurrentValue']?>"/></td>
                <td><?= $card['Email']?></td>
                <td><?= $card['InNameOf']?></td>
                <td><input type="submit" name="changecoupon[<?= $card['CouponCode']?>]" value="Opslaan"/></td>
            </tr><?php 
            }
        }else{
            echo "Geen cadeaukaarten om weer te geven!";
        }?>
    </table>
    </div>
</form>

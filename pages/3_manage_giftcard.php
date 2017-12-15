<?php
setTitle("Beheer cadeaubonnen");

$code = base_query("SELECT CouponCode FROM coupon ORDER BY CurrentValue ");



//Retrieve all current giftcards.
$giftcards = base_query("SELECT * FROM coupon");



function changeGiftCard($code, $value) {
    if (base_query("SELECT InitialValue FROM Coupon WHERE CouponCode = :couponcode", [':couponcode' => $code])->fetchColumn() < $value) {
       echo "fout";

    }else if($value >0){
        base_query("UPDATE coupon SET CurrentValue = :currentvalue WHERE CouponCode = :couponcode",[
            ':currentvalue' => $value,
            ':couponcode' => $code
	    ]);
    
        header("Location: ?p=manage_giftcard");
    }
}


if (isset($_POST['changecoupon'])) {
	foreach ($_POST['changecoupon'] as $couponCode => $value) {
		changeGiftCard($couponCode, $_POST['currentvalue'][$couponCode]);
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

<h2>Aangemaakte cadeaukaarten</h2>
<form method="POST">
    <table>
        <?php 
        if(!empty($giftcards)){ ?>
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
        }?>
    </table>
</form>
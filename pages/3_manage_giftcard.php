<?php
setTitle("Beheer cadeaubonnen");

$code = base_query("SELECT CouponCode FROM coupon");


function str_start_with($value, $start){
       return substr($value, 0, strlen($start)) === $start;
} 

function changeGiftCard($code){
    echo $code;
    if(isset($_POST['change_'][$code]))
        echo "het is ";
        base_query("UPDATE coupon SET CurrentValue = :currentvalue WHERE CouponCode = :couponcode",[
        ':currentvalue' => $_POST['currentvalue'],
        ':couponcode' => $code
    ]);


}

//Retrieve all current giftcards.
$giftcards = base_query("SELECT * FROM coupon");

foreach($_POST as $key => $value){
    if(str_start_with($key, "change_")){
        $coupon = substr($key, 7);
        changeGiftCard($coupon);
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
            foreach($giftcards as $card){
            ?>
        <tr>
            <td><?= $card['CouponCode']?></td>
            <td><?= $card['InitialValue']?></td>
            <td><input type="text" name="currentvalue" value="<?= $card['CurrentValue']?>"/></td>
            <td><?= $card['Email']?></td>
            <td><?= $card['InNameOf']?></td>
            <td><input type="submit" name="change_<?= $card['CouponCode']?>" value="Opslaan"/></td>
        </tr>
        </tr>
        <?php }
        }?>
    </table>
</form>
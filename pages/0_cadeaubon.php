<?php 

//Set title
setTitle("Cadeaubon");

//Function for getting the right ammount of pressing on the add button and getting the right value of this pressed button.
function str_start_with($value, $start){
    return substr($value, 0, strlen($start)) === $start;
}



//Function for adding items to a list of giftcart items.
function addCard($giftCardValue) {
    if (!isset($_SESSION['giftcards'][$giftCardValue])) {
        $_SESSION['giftcards'][$giftCardValue] = 1;
    } else {
        $_SESSION['giftcards'][$giftCardValue]++;
    }
}

//Function for remove items from the list of choosen giftcart items.
function removeCard($giftCardValue) {
    if(!isset($_SESSION['giftcards'][$giftCardValue])){
        return;
    }
    if ($_SESSION['giftcards'][$giftCardValue] <= 1) {
        unset($_SESSION['giftcards'][$giftCardValue]);
    } else {
        $_SESSION['giftcards'][$giftCardValue]--;
    }
}   

//Function for errors.
$errors = [];

function getFilledInDataErrors()
{
    $errors = [];
    
    if(empty($_POST['Email'])){
        $errors[] = "Mail adres is niet opgegeven!";
    }
    
    if(empty($_POST['InNameOf'])){
        $errors[] = "Op naam van is niet opgegeven!";
    }
    
    return $errors;
}

//If there are no giftcard items to add, let the array of $_SESSION['giftcards'] empty.
if (!isset($_SESSION['giftcards'])) {
    $_SESSION['giftcards'] = [];
}

//Look for the value for add items or remove items to the list, witch stands behind the name of the value (add_).
foreach($_POST as $key => $value){
    if(str_start_with($key, "remove_")){
        $remove = substr($key, 7);
        removeCard($remove);
    }elseif(str_start_with($key, "add_")){
        $add = substr($key, 4);  
        addCard($add);
    }
    
}



//Create a random unique giftcard code. 
function createRandomCode() { 
    
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $j = 0; 
    $code = '' ; 
    
    while ($j <= 7) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $code = $code . $tmp; 
        $j++; 
    } 
    while (base_query("SELECT CouponCode FROM Coupon WHERE CouponCode = :couponcode", [':couponcode' => $code])->fetch() != false) {
        $code = createRandomCode();
    }
    
    return $code; 
    
} 



//Put the choosen giftcards with the required data into the database. 
if(isset($_POST['order_gift_card'])){
    $errors = getFilledInDataErrors();
    if(empty($errors)){
        foreach($_SESSION['giftcards'] as $value => $count){
            for($i= 0; $i<$count; $i++){
                $code = createRandomCode();
                base_query("INSERT INTO `coupon` (`CouponCode`, `InitialValue`, `Currentvalue`, `Email`, `InNameOf`) 
                VALUES (:couponcode, :initialvalue, :currentvalue, :email, :innameof);", [
                    ':couponcode' => $code,
                    ':initialvalue' => $value,
                    ':currentvalue' => $value,
                    ':email' => $_POST['Email'],
                    ':innameof' => $_POST['InNameOf']
                ]);
            }
        }
    //REMOVE AFTHER IDEAL IS WORKING!
    echo"Bestelling is met succes opgeslagen!";

    }

}

//Looks if the varied ammount of a giftcard not null is, otherwise add it tho the list of choosen giftcards.
if(isset($_POST['varied'])){
    $add = $_POST['varied_ammount'];
    if($add <= 0){
        $errors[] = "Het gekozen bedrag is kleiner of gelijk aan 0!";   
    }
    else {
        addCard($add);
    }
}    

?>

<!--Style for the page-->
<style>
    .errors > p {
    color: red;
    }
</style>

<h2>Cadeaubon bestellen</h2>
<p>Bonnen kunnen ook in het restaurant worden opgehaald.</p>

<!--Print the errors-->
<div class="errors">
    <?php foreach ($errors as $error) {
        ?><p><?= $error ?></p><?php
    }
    ?>
</div>


<form method="POST">

    <table>
        <tr>
            <th>Cadeaubonnen</th>
        </tr>
        <tr>
            <td>Cadeaubon € 25</td>
            <td>
                <input type="submit" name="add_25" value="Toevoegen"/>
            </td>
        </tr>
        <tr>
            <td>Cadeaubon € 50</td>
            <td>
                <input type="submit" name="add_50" value="Toevoegen"/>
            </td>
        </tr>
        <tr>
            <td>Cadeaubon € 75</td>
            <td>
                <input type="submit" name="add_75" value="Toevoegen"/>
            </td>
        </tr>
        <tr>
            <td>Cadeaubon € 100</td>
            <td>
                <input type="submit" name="add_100" value="Toevoegen"/>
            </td>
        </tr>
        <tr>
            <td>Cadeaubon variable € </td>
            <td>
                <input type="number" step="1" name="varied_ammount" value="0"/>
                <input type="submit" name="varied" value="Toevoegen"/>
            </td>
        </tr>
    </table>
</form>

<!--Form for choosen gift cart items. -->
<form method="POST">
    <table>
        <?php
        if (!empty($_SESSION['giftcards'])) {
        echo "<tr><th>Bestelde cadeaubonnen</th></tr>";
        
            $total = 0;
            foreach($_SESSION['giftcards'] as $value => $count){
                $subtotal = $value * $count;
                $total += $subtotal;
                ?>
                    <tr>
                        <td><?= $count ?> X Cadeaubon € <?= $value ?></td>
                        <td> € <?= $subtotal ?></td>
                        <td><input type="submit" name="remove_<?= $value?>" value = "Delete" /></td>
                    </tr>
                <?php    
            }
        ?>
            <tr>
                <td>Totaal</td>
                <td>€ <?= $total ?>
            </tr>
            <tr>
                <td>Op naam van (verplicht):</td>
                <td><input type="text" name="InNameOf"/></td>
            </tr>
            <tr>
                <td>E-mail adres (verplicht):</td>
                <td><input type="text" name="Email"/></td>
            </tr>
            <tr>
                <td><input type="submit" name="order_gift_card" value="Cadeaubon afrekenen"/></td>
            </tr>
        
        <?php } ?>
        

    </table>
</form>
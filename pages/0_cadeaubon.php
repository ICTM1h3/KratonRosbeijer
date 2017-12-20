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

function getFilledInDataErrors(){
    $errors = [];
    
    if(empty($_POST['Email'])){
        $errors[] = "Mail adres is niet opgegeven!";
    }    
    elseif(!is_email_valid($_POST['Email'])){
        $errors[] = "Geen geldig emailadres opgegeven!";
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
    $code = '' ; 
    
    for ($j = 0; $j <= 7; $j++) { 
        $num = rand() % strlen($chars); 
        $tmp = substr($chars, $num, 1); 
        $code = $code . $tmp; 
        
    } 

    //Checks if the code is unique, compare the generated code with the codes in the database.
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
        
        //REMOVE AFTER IDEAL IS WORKING!
        echo"Bestelling is met succes opgeslagen!";

    }

}

//Looks if the varied ammount of a giftcard not null is, otherwise add it tho the list of choosen giftcards.
if(isset($_POST['varied'])){
    $add = $_POST['varied_ammount'];
    if($add <= 0){
        $errors[] = "Geen geldig bedrag of het gekozen bedrag is kleiner of gelijk aan 0!";   
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

<!-- Form for adding giftcard items -->

<table>
    <tr>
        <th>Cadeaubonnen</th>
    </tr>
    <tr>
        <td>Cadeaubon € 25</td>
        <td>
            <form method="POST">
                <input type="submit" name="add_25" value="Toevoegen"/>
            </form>
        </td>
    </tr>
    <tr>
        <td>Cadeaubon € 50</td>
        <td>
            <form method="POST">
                <input type="submit" name="add_50" value="Toevoegen"/>
            </form>
        </td>
    </tr>
    <tr>
        <td>Cadeaubon € 75</td>
        <td>
            <form method="POST">
                <input type="submit" name="add_75" value="Toevoegen"/>
            </form>
        </td>
    </tr>
    <tr>
        <td>Cadeaubon € 100</td>
        <td>
            <form method="POST">
                <input type="submit" name="add_100" value="Toevoegen"/>
            </form>
        </td>
    </tr>
    <tr>
        <td>Cadeaubon variable € </td>
        <td>
            <form method="POST">
                <input min="0" step="1" type="number" name="varied_ammount" value="0"/>
                <input type="submit" name="varied" value="Toevoegen"/>
            </form>
        </td>
    </tr>
</table>

<!--Form for choosen gift cart items. -->

    <table>
        <?php
        //If there is an item to show, print it. Also give the option to order the giftcard.
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
                        <td>
                            <form method="POST">
                                <input type="submit" name="remove_<?= $value?>" value = "Delete" />
                            </form>
                        </td>
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
                <td><input form="order_giftcard" type="text" name="InNameOf" required/></td>
            </tr>
            <tr>
                <td>E-mail adres (verplicht):</td>
                <td><input form="order_giftcard" type="email" name="Email" required/></td>
            </tr>
            <tr>
                <td>
                    <form id="order_giftcard" method="POST">
                        <input type="submit" name="order_gift_card" value="Cadeaubon afrekenen"/>
                    </form>
                </td>
            </tr>
        
        <?php } ?>
        

    </table>
</form>
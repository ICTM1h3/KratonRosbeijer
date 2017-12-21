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

function createPaymentCode() {
    $paymentCode = md5(uniqid(rand(), true));

    while (base_query("SELECT PaymentCode FROM Coupon WHERE PaymentCode = :paymentCode", [':paymentCode' => $paymentCode])->fetch() != false) {
        $paymentCode = md5(uniqid(rand(), true));
    }
    return $paymentCode;
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

function createCoupons($paymentCode = NULL) {
    foreach($_SESSION['giftcards'] as $value => $count){
        for($i= 0; $i<$count; $i++){
            $code = createRandomCode();
            base_query("INSERT INTO `coupon` (`CouponCode`, `InitialValue`, `Currentvalue`, `Email`, `InNameOf`, PaymentCode) 
            VALUES (:couponcode, :initialvalue, :currentvalue, :email, :innameof, :paymentCode);", [
                ':couponcode' => $code,
                ':initialvalue' => $value,
                ':currentvalue' => $value,
                ':email' => $_POST['Email'],
                ':innameof' => $_POST['InNameOf'],
                ':paymentCode' => $paymentCode
            ]);
        }
    }
}

$couponCodes = [];
$couponPrizes = [];
//Put the choosen giftcards with the required data into the database. 
if(isset($_POST['order_gift_card'])){
    $errors = getFilledInDataErrors();
    if (isset($_SESSION['UserId'])) {
        $userData = base_query("SELECT * FROM User WHERE Id = :id", [
            ':id' => $_SESSION["UserId"]
        ])->fetch();
        if ($userData['Role'] == ROLE_ADMINISTRATOR) {
            $errors = NULL;
            createCoupons();
            echo"Bestelling is met succes opgeslagen!";
        }
        elseif (empty($errors)) {
            $_SESSION['email'] = $_POST['Email'];
            $_SESSION['name'] = $_POST['InNameOf'];
            $paymentCode = createPaymentCode();
            foreach($_SESSION['giftcards'] as $value => $count){
                for($i= 0; $i<$count; $i++){
                    $code = createRandomCode();
                    $couponCodes[] = $code;
                    $couponPrizes[] = $value;
                    base_query("INSERT INTO `coupon` (`CouponCode`, `InitialValue`, `Currentvalue`, `Email`, `InNameOf`, `PaymentCode`) 
                    VALUES (:couponcode, :initialvalue, :currentvalue, :email, :innameof, :paymentCode);", [
                        ':couponcode' => $code,
                        ':initialvalue' => $value,
                        ':currentvalue' => $value,
                        ':email' => $_POST['Email'],
                        ':innameof' => $_POST['InNameOf'],
                        ':paymentCode' => $paymentCode
                    ]);
                }
            }
            $_SESSION['paymentCode'] = $paymentCode;
            $_SESSION['couponCodes'] = $couponCodes;
            $_SESSION['couponPrizes'] = $couponPrizes;
            echo "Bestelling van de cadeaubon is opgslagen!";
            header('Location: ?p=IDEAL_payment_giftcards');
        }
    }
    elseif(empty($errors)){
        $_SESSION['email'] = $_POST['Email'];
        $_SESSION['name'] = $_POST['InNameOf'];
        $paymentCode = createPaymentCode();
        foreach($_SESSION['giftcards'] as $value => $count){
            for($i= 0; $i<$count; $i++){
                $code = createRandomCode();
                $couponCodes[] = $code;
                $couponPrizes[] = $value;
                base_query("INSERT INTO `coupon` (`CouponCode`, `InitialValue`, `Currentvalue`, `Email`, `InNameOf`, `PaymentCode`) 
                VALUES (:couponcode, :initialvalue, :currentvalue, :email, :innameof, :paymentCode);", [
                    ':couponcode' => $code,
                    ':initialvalue' => $value,
                    ':currentvalue' => $value,
                    ':email' => $_POST['Email'],
                    ':innameof' => $_POST['InNameOf'],
                    ':paymentCode' => $paymentCode
                ]);
            }
        }
        $_SESSION['paymentCode'] = $paymentCode;
        $_SESSION['couponCodes'] = $couponCodes;
        $_SESSION['couponPrizes'] = $couponPrizes;
        echo "Bestelling van de cadeaubon is opgslagen!";
        header('Location: ?p=IDEAL_payment_giftcards');
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

<!-- Form for adding giftcard items -->
<div class="container">
    
<h2>Cadeaubon bestellen</h2>

<?php
if(isset($_SESSION['UserId'])) {
    $userData = base_query("SELECT * FROM User WHERE Id = :id", [
        ':id' => $_SESSION["UserId"]
    ])->fetch();
    if ($userData['Role'] == ROLE_ADMINISTRATOR) {
        $userName = "administrator";
        $userEmail = $userData['Email'];
    }
    elseif (!empty($userData['MiddleName'])) {
        $userName = $userData['Firstname'] . " " . $userData['MiddleName'] . " " . $userData['Lastname'];
        $userEmail = $userData['Email'];
    }
    else {
        $userName = $userData['Firstname'] . " " . $userData['Lastname'];
        $userEmail = $userData['Email'];
    }
    
}
else {
    $userName = "";
    $userEmail = "";
}
?>

<ul>
    <li>Bonnen kunnen ook in het restaurant worden opgehaald.</li>
    <li>U rekent de bestellde bonnen samen af.</li>
    <li>U ontvangt een mail met per bestelde cadeaubon een unieke code.</li>
</ul>


<!--Print errors if there were any.-->
    <?php if (!empty($errors)) { ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error) { ?>
                    <p><?= $error ?></p> 
            <?php } ?>
        </div>
    <?php } ?>

<!-- Form for adding giftcard items -->
<form method="POST">
    <table>
        <tr>
            <th>Cadeaubonnen</th>
        </tr>
        <tr>
            <td>Cadeaubon € 25</td>
            <td>
                <form method="POST">
                    <input class="btn btn-secondary" type="submit" name="add_25" value="Toevoegen"/>
                </form>
            </td>
        </tr>
        <tr>
            <td>Cadeaubon € 50</td>
            <td>
                <form method="POST">
                    <input class="btn btn-secondary" type="submit" name="add_50" value="Toevoegen"/>
                </form>
            </td>
        </tr>
        <tr>
            <td>Cadeaubon € 75</td>
            <td>
                <form method="POST">
                    <input class="btn btn-secondary" type="submit" name="add_75" value="Toevoegen"/>
                </form>
            </td>
        </tr>
        <tr>
            <td>Cadeaubon € 100</td>
            <td>
                <form method="POST">
                    <input class="btn btn-secondary" type="submit" name="add_100" value="Toevoegen"/>
                </form>
            </td>
        </tr>
        <tr>
            <td>Cadeaubon variable € </td>
            <td>
                <form method="POST">
                    <input class="form-control"  min="0" step="1" type="number" name="varied_ammount" value="0"/>
                    <input class="btn btn-secondary" type="submit" name="varied" value="Toevoegen"/>
                </form>
            </td>
        </tr>
    </table>
<!-- </div> -->
</form>
  
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
                                <input class="btn btn-secondary" type="submit" name="remove_<?= $value?>" value = "Delete" />
                            </form>
                        </td>
                    </tr>
                <?php    
            }
            $_SESSION["totalPrice"] = $total;
        ?>
            <tr>
                <td>Totaal</td>
                <td>€ <?= $total ?>
            </tr>
            <tr>
                <td>Op naam van (verplicht):</td>
                <td><input form="order_giftcard" class="form-control" type="text" name="InNameOf" value="<?=$userName?>" required/></td>
            </tr>
            <tr>
                <td>E-mail adres (verplicht):</td>
                <td><input form="order_giftcard" class="form-control" type="email" name="Email" value="<?=$userEmail?>" required/></td>
            </tr>
            <tr>
                <td>
                    <form id="order_giftcard" method="POST">
                        <input type="submit" class="btn btn-secondary" name="order_gift_card" value="Cadeaubon afrekenen"/>
                    </form>
                </td>
            </tr>
        
        <?php } ?>
        

    </table>
</div>
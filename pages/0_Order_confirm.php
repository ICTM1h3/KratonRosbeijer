<?php
setTitle("Bestelling bevestigen");

// Sets the timezone
date_default_timezone_set("Europe/Amsterdam");

function validateData() {
    $errors = [];
    if (empty($_POST['inNameOf'])) {
        $errors[] = "U heeft uw naam niet ingevuld.";
    }
    if (empty($_POST['email'])) {
        $errors[] = "U heeft uw emailadres niet ingevuld.";
    }
    elseif (!is_email_valid($_POST['email'])) {
        $errors[] = "U heeft geen geldig email ingevuld.";
    }
    if (empty($_POST['telNumber'])) {
        $errors[] = "U heeft uw telefoonnummer niet ingevuld.";
    }
    elseif (!is_valid_telephone_number($_POST['telNumber'])) {
        $errors[] = "U heeft geen geldig telefoonnummer opgegeven.";
    }
    if (empty($_POST['date'])) {
        $errors[] = "U heeft geen datum opgegeven.";
    }
    elseif ($_POST['date'] < date('Y-m-d')) {
        $errors[] = "Er kan niet in het verleden bestelt worden.";
    }
    if (empty($_POST['time'])) {
        $errors[] = "U heeft geen tijdstop opgegeven.";
    }
    elseif ($_POST['time'] > "18:00") {
        $errors[] = "U mag niet later dan 18:00 uur het eten afhalen.";
    }
    return $errors;
}

function countItems($items, $Id){
    $count = 0;
    foreach($items as $value) {
        if ($value == $Id) {
            $count++;
        }
    }
    return $count;
}

// Checks how many times the dish is ordered
function countDishes($items, $Id) {
    return countItems($items, $Id);
} 

// Checks how many times the category(dish) is ordered
function countCategories($items, $Id) {
    return countItems($items, $Id);
} 

function createPaymentCode() {
    $paymentCode = md5(uniqid(rand(), true));

    while (base_query("SELECT PaymentCode FROM `Order` WHERE PaymentCode = :paymentCode", [':paymentCode' => $paymentCode])->fetch() != false) {
        $paymentCode = md5(uniqid(rand(), true));
    }
    return $paymentCode;
}

// Inserts everything into the database
$amountDishes = [];
$amountCategories = [];
function insertOrderData() {

    // Gets the newest Order Id
    $newOrderId = base_query("SELECT MAX(Id) AS newestOrderId FROM `Order`")->fetchColumn();
    $newOrderId++;
    // Saves the current time when the Order is made
    $currentDateTime = date('Y-m-d H:i:s');
    $targetTime = ($_POST['date'] . " " . $_POST['time']);
    $paymentCode = createPaymentCode();
    $_SESSION["paymentCode"] = $paymentCode;
    $_SESSION["name"] = $_POST['inNameOf'];
    $_SESSION["telephoneNumber"] = $_POST['telNumber'];
    $_SESSION["date"] = $_POST['date'];
    $_SESSION["time"] = $_POST['time'];

    base_query("INSERT INTO `Order` (OrderDate, TargetDate, InNameOf, TelephoneNumber, Email, Price, PaymentCode) VALUES
    (:orderDate, :targetDate, :inNameOf, :telephoneNumber, :email, :price, :paymentCode)", [
        ':orderDate' => $currentDateTime,
        ':targetDate' => $targetTime,
        'inNameOf' => $_POST['inNameOf'],
        ':telephoneNumber' => $_POST['telNumber'],
        ':email' => $_POST['email'],
        ':price' => $_SESSION['totalPrice'],
        ':paymentCode' => $paymentCode
    ]);

    // Inserting the dishes into the database
    foreach ($_SESSION["dish"] as $value) {
        $countedDishes = countDishes($_SESSION["dishes"], $value);
        $amountDishes[] = $countedDishes;
        base_query("INSERT INTO Dish_Order (OrderId, DishId, CountDish) VALUES
        (:orderId, :dishId, :countDish)", [
            ':orderId' => $newOrderId,
            ':dishId' => $value,
            ':countDish' => $countedDishes
        ]);
    }

    // Inserting the categories into the database
    foreach ($_SESSION["category"] as $value) {
        $countedCategories = countCategories($_SESSION["categories"], $value);
        $amountCategories[] = $countedCategories;
        base_query("INSERT INTO Category_Order (OrderId, CategoryId, CountCategory) VALUES
        (:OrderId, :CategoryId, :countCategory)", [
            ':OrderId' => $newOrderId,
            ':CategoryId' => $value,
            ':countCategory' => $countedCategories
        ]);
    }
    $_SESSION["amountDishes"] = $amountDishes;
    $_SESSION["amountCategories"] = $amountCategories;
}


function getValue($key) {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }
    return '';
}

$errors = [];

// Checks for errors. If there are, it will show errors
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['bestelGegevens'])) {
        $errors = validateData();
        if (empty($errors)) {
            $_SESSION["e-mail"] = $_POST["email"];
            insertOrderData();
            echo "Uw bestelling is aangemaakt<br>";
            header('Location: ?p=IDEAL_payment_orders');
        }
        else {
            foreach ($errors as $error) {
                ?><div class="errormsg">
                    <?=$error?>
                </div><?php
            }
        }
    }
}

?>
<form method="POST">
    <table>
        <tr>
            <td>Totale prijs: <?=$_SESSION["totalPrice"] ?></td>
        </tr>
        <tr>
            <td><b>Naam:</b></td>
            <td><input type="text" name="inNameOf" value=<?=getValue('inNameOf')?>><td>
        </tr>
        <tr>
            <td><b>Emailadres:</b></td>
            <td><input type="email" name="email" value=<?=getValue('email')?>></td>
        </tr>
        <tr>
            <td><b>Telefoonnummer:</b></td>
            <td><input type="tel" name="telNumber" value=<?=getValue('telNumber')?>></td>
        <tr>
            <td><b>Datum van afhalen:</b></td>
            <td><input type="date" name="date" value=<?=getValue('date')?>></td>
        </tr>
        <tr>
            <td><b>Tijdstip van afhalen:</b></td>
            <td><input type="time" name="time" value=<?=getValue('time')?>></td>
        </tr>
        <tr>
            <td><input type="submit" name="bestelGegevens" value="Bestellen!"/>
        </tr>
    </table>
</form>

<style>

.errormsg {
    color:red;
}

</style>
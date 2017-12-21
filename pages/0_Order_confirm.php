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
function countDishes($Id) {
    return countItems($_SESSION['dishes'], $Id);
} 

// Checks how many times the category(dish) is ordered
function countCategories($Id) {
    return countItems($_SESSION['categories'], $Id);
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
function insertOrderData($paymentCode = null) {

    // Gets the newest Order Id
    $newOrderId = base_query("SELECT MAX(Id) AS newestOrderId FROM `Order`")->fetchColumn();
    $newOrderId++;
    // Saves the current time when the Order is made
    $currentDateTime = date('Y-m-d H:i:s');
    $targetTime = ($_POST['date'] . " " . $_POST['time']);
    $_SESSION["paymentCode"] = $paymentCode;
    $_SESSION["name"] = $_POST['inNameOf'];
    $_SESSION["telephoneNumber"] = $_POST['telNumber'];
    $_SESSION["date"] = $_POST['date'];
    $_SESSION["time"] = $_POST['time'];

    $discount = 0;
    if (isset($_SESSION['UserId'])) {
        $discount = base_query("SELECT Discount FROM User WHERE Id = :id", [':id' => $_SESSION['UserId']])->fetchColumn();
    }

    base_query("INSERT INTO `Order` (OrderDate, TargetDate, InNameOf, TelephoneNumber, Email, Price, PaymentCode) VALUES
    (:orderDate, :targetDate, :inNameOf, :telephoneNumber, :email, :price, :paymentCode)", [
        ':orderDate' => $currentDateTime,
        ':targetDate' => $targetTime,
        'inNameOf' => $_POST['inNameOf'],
        ':telephoneNumber' => $_POST['telNumber'],
        ':email' => $_POST['email'],
        ':price' => $_SESSION['totalPrice'] * ((100 - $discount) / 100),
        ':paymentCode' => $paymentCode
    ]);

    // Inserting the dishes into the database
    foreach ($_SESSION["dish"] as $value) {
        $countedDishes = countDishes($value);
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
        $countedCategories = countCategories($value);
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

$cumulative = 0;
?><table class="overview_order">
    <tr>
        <th>Gerecht</th>
        <th>Prijs</th>
        <th>Aantal</th>
        <th>Subtotaal</th>
        <th>Cumulatief</th>
    <?php
foreach ($_SESSION['dish'] as $value) {
    $countedDishes = countDishes($value);
    $dishPrice = base_query("SELECT * FROM Dish WHERE Id = :id", [
        ':id' => $value
    ])->fetch();
    $subTotal = $dishPrice['Price'] * $countedDishes;
    $cumulative += $subTotal;
    ?><tr>
        <td><?=$dishPrice['Name']?></td>
        <td><?=$dishPrice['Price']?></td>
        <td><?=$countedDishes?></td>
        <td><?=$subTotal?></td>
        <td><?=$cumulative?></td>
    </tr><?php
}
foreach ($_SESSION['category'] as $value) {
    $countedCategories = countCategories($value);
    $categoryPrice = base_query("SELECT * FROM DishCategory WHERE Id = :id", [
        ':id' => $value
    ])->fetch();
    $subTotal = $categoryPrice['Price'] * $countedCategories;
    $cumulative += $subTotal;
    ?><tr>
        <td><?=$categoryPrice['Name']?></td>
        <td><?=$categoryPrice['Price']?></td>
        <td><?=$countedCategories?></td>
        <td><?=$subTotal?></td>
        <td><?=$cumulative?></td>
    </tr><?php
}

if (isset($_SESSION['UserId'])) {
    $userData = base_query("SELECT * FROM User WHERE Id = :id", [
        ':id' => $_SESSION['UserId']
    ])->fetch();
    if (!empty($userData['MiddleName'])) {
        $userName = $userData['Firstname'] . " " . $userData['MiddleName'] . " " . $userData['Lastname'];
    }
    else {
        $userName = $userData['Firstname'] . " " . $userData['Lastname'];
    }
    $role = $userData['Role'];
    $userEmail = $userData['Email'];
    $userTelNumber = $userData['TelephoneNumber'];
    $discount = ($role == ROLE_VIP_USER) ? $userData['Discount'] : 0;
}
else {
    $userName = "";
    $userEmail = "";
    $userTelNumber = "";
    $discount = 0;
    $role = ROLE_VISITOR;
}

?>


<tr>
    <td></td>
    <td></td>
    <td></td>
    <th>Totaal:</th>
    <th><?=$_SESSION['totalPrice']?></th>
</tr>
<?php if ($role == ROLE_VIP_USER) {?>
<tr>
    <td></td>
    <td></td>
    <td></td>
    <th>Korting:</th>
    <th><?= $discount ?>%</th>
</tr>
<tr>
    <td></td>
    <td></td>
    <td></td>
    <th>Berekend totaal:</th>
    <th><?= $_SESSION['totalPrice'] * ((100 - $discount) / 100) ?></th>
</tr>
<?php } ?>
</table><?php

$errors = [];

// Checks for errors. If there are, it will show errors
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['bestelGegevens'])) {
        $errors = validateData();
        if (empty($errors)) {
            $_SESSION["e-mail"] = $_POST["email"];
            if (!isset($_SESSION['UserId']) || base_query("SELECT Role FROM User WHERE Id = :id", [':id' => $_SESSION['UserId']])->fetchColumn() != ROLE_ADMINISTRATOR) {
                $code = createPaymentCode();
                insertOrderData($code);
                header('Location: ?p=IDEAL_payment_orders');
            }
            else {
                insertOrderData();
                echo "<p style='color:green'><b>Uw bestelling is aangemaakt</b></p>";
            }
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
            <td><b>Naam:</b></td>
            <td>
                <input type="text" name="inNameOf" value=<?=$userName?>>
            <td>
        </tr>
        <tr>
            <td><b>Emailadres:</b></td>
            <td><input type="email" name="email" value=<?=$userEmail?>></td>
        </tr>
        <tr>
            <td><b>Telefoonnummer:</b></td>
            <td><input type="tel" name="telNumber" value=<?=$userTelNumber?>></td>
        <tr>
            <td><b>Datum van afhalen:</b></td>
            <td><input type="date" name="date"></td>
        </tr>
        <tr>
            <td><b>Tijdstip van afhalen:</b></td>
            <td><input type="time" name="time"></td>
        </tr>
        <tr>
            <td><input type="submit" name="bestelGegevens" value="Bestellen!"/>
        </tr>
        <tr>
            <td><?php
                if(!isset($_SESSION['UserId'])) {
                    ?><a href="?p=inlogpage">U kunt hier eventueel inloggen</a><?php
                }?>
            </td>
        </tr>
    </table>
</form>

<style>

.overview_order {
    border: 1px solid black;
    text-align: center;
}    

.errormsg {
    color:red;
}

</style>
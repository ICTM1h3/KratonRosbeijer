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

// Checks how many times the dish is ordered
function countDishes($Id) {
    $count = 0;
    foreach ($_SESSION["dishes"] as $value) {
        if ($value == $Id) {
            $count++;
        }
    }
    return $count;
} 

// Checks how many times the category(dish) is ordered
function countCategories($Id) {
    $count = 0;
    foreach ($_SESSION["categories"] as $value) {
        if ($value == $Id) {
            $count++;
        }
    }
    return $count;
} 

// Inserts everything into the database
function insertOrderData() {

    // Gets the newest Order Id
    $newOrderId = base_query("SELECT MAX(Id) AS newestOrderId FROM `Order`")->fetchColumn();
    $newOrderId++;
    // Saves the current time when the Order is made
    $currentDateTime = date('Y-m-d H:i:s');
    $targetTime = ($_POST['date'] . " " . $_POST['time']);

    base_query("INSERT INTO `Order` (OrderDate, TargetDate, InNameOf, TelephoneNumber, Email) VALUES
    (:orderDate, :targetDate, :inNameOf, :telephoneNumber, :email)", [
        ':orderDate' => $currentDateTime,
        ':targetDate' => $targetTime,
        'inNameOf' => $_POST['inNameOf'],
        ':telephoneNumber' => $_POST['telNumber'],
        ':email' => $_POST['email']
    ]);

    // Inserting the dishes into the database
    foreach ($_SESSION["dish"] as $value) {
        $countedDishes = countDishes($value);
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
        base_query("INSERT INTO Category_Order (OrderId, CategoryId, CountCategory) VALUES
        (:OrderId, :CategoryId, :countCategory)", [
            ':OrderId' => $newOrderId,
            ':CategoryId' => $value,
            ':countCategory' => $countedCategories
        ]);
    }
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
            insertOrderData();
            echo "Uw bestelling is aangemaakt<br>";
            send_email_to($_POST['email'], "Confirmatie bestelling", "confirm_order", [
                'inNameOf' => $_POST['inNameOf'],
                'targetDate' => $_POST['date'],
                'telNumber' => $_POST['telnumber'],
                'dish' => $_SESSION['dish'],
                'amount' => 5
            ]);
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
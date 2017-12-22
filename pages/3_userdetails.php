<?php

// Checks if all posted data is valid.
function validateData() {
    $errors = [];
    if (empty($_POST['Firstname'])) {
        $errors[] = "U heeft geen voornaam ingevult.";
    }

    if (empty($_POST['Lastname'])) {
        $errors[] = "U heeft geen achternaam ingevult.";
    }

    if (empty($_POST['TelephoneNumber'])) {
        $errors[] = "U heeft geen telefoonnummer ingevult.";
    }
    elseif (!is_valid_telephone_number($_POST['TelephoneNumber'])) {
        $errors[] = "U heeft geen geldig telefoonnummer ingevult.";
    }

    if (empty($_POST['Email'])) {
        $errors[] = "U heeft geen email ingevult.";
    }
    elseif (!is_email_valid($_POST['Email'])) {
        $errors[] = "U heeft geen geldig email ingevult.";
    }

    return $errors;
}

// Updates the user with the values from the POST.
function update_user() {
    // Check if there isn't another use with the same email.
    if (base_query("SELECT * FROM User WHERE Email = :email AND Id <> :id", [':email' => $_POST['Email'], ':id' => $_GET['userId']])->fetch() != false) {
        return [false, "Er bestaat al een gebruiker met de gegeven email"];
    }

    base_query("UPDATE User
    SET Firstname = :firstname, 
        Lastname = :lastname,
        MiddleName = :middlename,
        TelephoneNumber = :telephonenumber,
        Email = :email,
        Discount = :discount
    WHERE Id = :id", [
        ':firstname' => $_POST['Firstname'],
        ':lastname' => $_POST['Lastname'],
        ':middlename' => $_POST['MiddleName'],
        ':telephonenumber' => $_POST['TelephoneNumber'],
        ':email' => $_POST['Email'],
        ':discount' => isset($_POST['Discount']) ? $_POST['Discount'] : 0,
        ':id' => $_GET['userId'],
    ]);

    return [true];
}


if (!isset($_GET['userId'])) {
    return;
}

$inChangingMode = isset($_GET['changemode']);
$errors = [];

// Update the user if requested.
if (isset($_POST['save'])) {
    $errors = validateData();
    if (empty($errors)) {
        list($success, $msg) = update_user();
        if ($success) {
            header("Location: ?p=manageaccounts");
        }
        else {
            $errors[] = $msg;
        }
    }
}

// Get the user details, amount of reservations and orders and the amount of times they didn't show up.
$user = base_query("SELECT u.*, 
    (SELECT COUNT(r.Id) FROM Reservation r WHERE r.UserId = u.Id) AmountReservations,
    (SELECT COUNT(o.Id) FROM `Order` o WHERE o.UserId = u.Id) AmountOrders,
    (SELECT COUNT(r.Id) FROM Reservation r WHERE r.UserId = u.Id AND IsNoShow = 1) AmountNoShowReservations,
    (SELECT COUNT(o.Id) FROM `Order` o WHERE o.UserId = u.Id AND IsPickedUp = 0 AND TargetDate < NOW()) AmountNoShowOrders
FROM User u 
WHERE u.Id = :id", [
    ':id' => $_GET['userId']
])->fetch();

function getValue($user, $key) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        return $_POST[$key];
    }
    return $user[$key];
}
?>

<style>
    .edit {
        font-size:15px;
    }

    [disabled] {
        border: none;
        color: black;
        background-color: inherit;
    }

    table td {
        padding: 3px;
    }
</style>

<h2>Gebruiker details
    <?php if (!$inChangingMode) { ?>
        <a class="edit" href="?<?= $_SERVER['QUERY_STRING'] ?>&changemode=true">(Wijzig)</a>
    <?php } else {
        unset($_GET['changemode']); ?>
        <a class="edit" href="?<?= http_build_query($_GET) ?>">(Terug)</a>
    <?php } ?>
</h2>

<?php if (!empty($errors)) { ?>
    <div style="color:red">
        <?php foreach ($errors as $error ) { ?>
            <p><?= $error ?></p>
        <?php } ?>
    </div>
<?php } ?>

<?php $disabled = $inChangingMode ? '' : 'disabled'; ?>

<form method="POST">
    <table>
        <tr>
            <td>Voornaam</td>
            <td><input class="form-control" <?= $disabled ?> name="Firstname" value="<?= htmlentities(getValue($user, "Firstname")) ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Tussenvoegsel</td>
            <td><input class="form-control" <?= $disabled ?> name="MiddleName" value="<?= htmlentities(getValue($user, "MiddleName")) ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Achternaam</td>
            <td><input class="form-control" <?= $disabled ?> name="Lastname" value="<?= htmlentities(getValue($user, "Lastname")) ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Telefoonnummer</td>
            <td><input class="form-control" <?= $disabled ?> name="TelephoneNumber" value="<?= htmlentities(getValue($user, "TelephoneNumber")) ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input class="form-control" <?= $disabled ?> name="Email" value="<?= htmlentities(getValue($user, "Email")) ?>" type="Email" /></td>
        </tr>
        <?php if ($user['Role'] == ROLE_VIP_USER) { ?>
            <tr>
                <td>Korting (%)</td>
                <td><input class="form-control" <?= $disabled ?> min="0" name="Discount" value="<?= htmlentities(getValue($user, "Discount")) ?>" type="number" /></td>
            </tr>
        <?php } ?>
        
        <?php if (!$inChangingMode) { ?>
        <tr>
            <td>Hoeveelheid reserveringen</td>
            <td class="form-control" ><?= $user['AmountReservations'] ?> </td>
        </tr>
        <tr>
            <td>Hoeveelheid bestellingen</td>
            <td class="form-control"><?= $user['AmountOrders'] ?> </td>
        </tr>
        <tr>
            <td>Hoeveelheid no show reserveringen</td>
            <td class="form-control"><?= $user['AmountNoShowReservations'] ?> </td>
        </tr>
        <tr>
            <td>Hoeveelheid no show bestellingen</td>
            <td class="form-control"><?= $user['AmountNoShowOrders'] ?> </td>
        </tr>
        <?php } else { ?>
        <tr>
            <td colspan="2">
                <input class="btn btn-secondary" type="submit" style="width:100%;" name="save" value="Opslaan">
            </td>
        </tr>
        <?php } ?>
    </table>
</form>
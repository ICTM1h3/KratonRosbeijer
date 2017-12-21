<?php

// Makes sure the user details are correct
function validateUserDetailsData() {
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

$user_details_errors = [];
$user_details_success = [];
$password_errors = [];
$password_success = [];


// Update the details if requested
if (isset($_POST['change_details'])) {
    $user_details_errors = validateUserDetailsData();
    if (empty($errors)) {
        base_query("UPDATE User 
        SET Firstname = :firstname,
            MiddleName = :middlename,
            Lastname = :lastname,
            Email = :email,
            TelephoneNumber = :telephonenumber
        WHERE Id = :id", [
                ':firstname' => $_POST['Firstname'],
                ':middlename' => $_POST['MiddleName'],
                ':lastname' => $_POST['Lastname'],
                ':email' => $_POST['Email'],
                ':telephonenumber' => $_POST['TelephoneNumber'],
                ':id' => $_SESSION['UserId'],
        ]);
        $user_details_success[] = "Uw details zijn veranderd";
    }
}

$user = base_query("SELECT * FROM User WHERE Id = :id", [':id' => $_SESSION['UserId']])->fetch();

// Change the password if requested
if (isset($_POST['change_password'])) {
    // Make sure the user knows the current password.
    if (!password_verify($_POST['current_password'], $user['Password'])) {
        $password_errors[] = "Uw huidig wachtwoord is incorrect.";
    }
    else {
        // Make sure the confirmation password is the same as the new password.
        if ($_POST['new_password'] != $_POST['confirm_password']) {
            $password_errors[] = "Het nieuwe wachtwoord komt niet overeen met het bevstigings wachtwoord.";
        }
        else {
            base_query("UPDATE User SET Password = :password WHERE Id = :id", [
                ':password' => password_hash($_POST['new_password'], PASSWORD_BCRYPT),
                ':id' => $_SESSION['UserId']
            ]);
            $password_success[] = 'Uw wachtwoord is aangepast';
        }
    }
}
?>
<style>
    .errors {
        color: red;
    }

    .success {
        color:green;
    }
</style>
<h2>Details veranderen</h2>
<div class="errors">
    <?php foreach ($user_details_errors as $error) {
        ?><p><?= $error ?></p>
    <?php } ?>
</div>
<div class="success">
    <?php foreach ($user_details_success as $success) {
        ?><p><?= $success ?></p>
    <?php } ?>
</div>
<form method="POST">
    <table class="table">
        <tr>
            <td>Voornaam</td>
            <td><input type="text" class="form-control" name="Firstname" value="<?= $user['Firstname'] ?>" required></td>
        </tr>
        <tr>
            <td>Tussenvoegsel</td>
            <td><input type="text" class="form-control" name="MiddleName" value="<?= $user['MiddleName'] ?>"></td>
        </tr>
        <tr>
            <td>Achternaam</td>
            <td><input type="text" class="form-control" name="Lastname" value="<?= $user['Lastname'] ?>" required></td>
        </tr>
        <tr>
            <td>Telephonenummer</td>
            <td><input type="text" class="form-control" name="TelephoneNumber" value="<?= $user['TelephoneNumber'] ?>"></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="email" class="form-control" name="Email" value="<?= $user['Email'] ?>"></td>
        </tr>
        <tr>
            <td colspan="2"><input name="change_details" class="btn btn-secondary" style="width:100%;" type="submit" value="Opslaan" /></td>
        </tr>
    </table>
</form>

<h2>Wachtwoord</h2>
<div class="errors">
    <?php foreach ($password_errors as $error) {
        ?><p><?= $error ?></p>
    <?php } ?>
</div>
<div class="success">
    <?php foreach ($password_success as $success) {
        ?><p><?= $success ?></p>
    <?php } ?>
</div>
<form method="POST">
    <table class="table">
        <tr>
            <td>Huidig wachtwoord</td>
            <td><input class="form-control" type="password" name="current_password"></td>
        </tr>
        <tr>
            <td>Nieuw wachtwoord</td>
            <td><input class="form-control" type="password" name="new_password"></td>
        </tr>
        <tr>
            <td>Wachtwoord herhalen</td>
            <td><input class="form-control" type="password" name="confirm_password"></td>
        </tr>
        <tr>
            <td colspan="2"><input name="change_password" class="btn btn-danger" style="width:100%;" type="submit" value="Opslaan" /></td>
        </tr>
    </table>
</form>
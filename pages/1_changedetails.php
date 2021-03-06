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
    if (empty($user_details_errors)) {
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

// Get the current use from the database.
$user = base_query("SELECT * FROM User WHERE Id = :id", [':id' => $_SESSION['UserId']])->fetch();

// Change the password if requested
if (isset($_POST['change_password'])) {
    // Make sure the user knows the current password.
    if (!password_verify($_POST['current_password'], $user['Password'])) {
        $password_errors[] = "Uw huidig wachtwoord is incorrect.";
    }
    else {
        if (empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
            // Make sure the provided passwords are not empty.
            $password_errors[] = "U heeft geen wachtwoord ingevult.";
        }
        elseif ($_POST['new_password'] != $_POST['confirm_password']) {
            // Make sure the confirmation password is the same as the new password.
            $password_errors[] = "Het nieuwe wachtwoord komt niet overeen met het bevestigings wachtwoord.";
        }
        else {
            list($isStrong, $msg) = is_password_strong($_POST['new_password']);
            if (!$isStrong) {
                $password_errors[] = $msg;
            }
            else {
                // Update the password of the user in the database with the new hash.
                base_query("UPDATE User SET Password = :password WHERE Id = :id", [
                    ':password' => password_hash($_POST['new_password'], PASSWORD_BCRYPT),
                    ':id' => $_SESSION['UserId']
                ]);
                $password_success[] = 'Uw wachtwoord is aangepast';
            }
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
<?php foreach ($user_details_errors as $error) { ?>
    <div class="alert alert-danger" role="alert">
        <p><?= $error ?></p>
    </div>
<?php } ?>
<?php foreach ($user_details_success as $success) { ?>
    <div class="alert alert-success" role="alert">
        <p><?= $success ?></p>
    </div>
<?php } ?>
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
            <td>Telefoonnummer</td>
            <td><input type="text" class="form-control" name="TelephoneNumber" value="<?= $user['TelephoneNumber'] ?>"></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="email" class="form-control" name="Email" value="<?= $user['Email'] ?>"></td>
        </tr>
        <tr>
            <td colspan="2"><input name="change_details" class="btn btn-primary" style="width:100%;" type="submit" value="Opslaan" /></td>
        </tr>
    </table>
</form>

<h2>Wachtwoord</h2>
<?php foreach ($password_errors as $error) { ?>
    <div class="alert alert-danger" role="alert">
        <p><?= $error ?></p>
    </div>
<?php } ?>
<?php foreach ($password_success as $success) { ?>
    <div class="alert alert-success" role="alert">
        <p><?= $success ?></p>
    </div>
<?php } ?>
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
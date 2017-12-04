<?php

function validateData() {
    $errors = [];
    if (empty($_POST['firstName'])) {
        $errors[] = "U heeft geen voornaam ingevult.";
    }

    if (empty($_POST['lastName'])) {
        $errors[] = "U heeft geen achternaam ingevult.";
    }

    if (empty($_POST['telephoneNumber'])) {
        $errors[] = "U heeft geen telefoonnummer ingevult.";
    }
    elseif (!is_valid_telephone_number($_POST['telephoneNumber'])) {
        $errors[] = "U heeft geen geldig telefoonnummer ingevult.";
    }

    if (empty($_POST['email'])) {
        $errors[] = "U heeft geen email ingevult.";
    }
    elseif (!is_email_valid($_POST['email'])) {
        $errors[] = "U heeft geen geldig email ingevult.";
    }

    if (empty($_POST['password'])) {
        $errors[] = "U heeft geen wachtwoord ingevult.";
    }
    elseif (empty($_POST['password_confirmation'])) {
        $errors[] = "U heeft het bevestigende wachtwoord niet ingevult";
    }
    elseif ($_POST['password'] != $_POST['password_confirmation']) {
        $errors[] = "Het opgegeven wachtwoord komt niet overeen met het bevestigende wachtwoord.";
    }
    return $errors;
}


// Checks if there already is a user with the provided email. Returns false with an error message of that is the case.
// Otherwise registers the user and sends a confirmation email to the user.
function register_user() {
    $user = base_query("SELECT * FROM User WHERE Email = :email", [':email' => $_POST['email']])->fetch();
    if ($user != null) {
        return [false, "De gebruiker bestaat al"];
    }

    // Generate a registration code
    $registrationCode = hash("sha512", rand());

    // Insert the new user into the database with the provided values, generated registration code and a default role of 1.
    base_query("INSERT INTO User (Firstname, Lastname, MiddleName, TelephoneNumber, Email, Password, RegistrationCode, Role) Values
    (:firstname, :lastname, :middlename, :telephonenumber, :email, :password, :registrationCode, 1)", [
        ':firstname' => $_POST['firstName'],
        ':lastname' => $_POST['lastName'],
        ':middlename' => $_POST['middleName'],
        ':telephonenumber' => $_POST['telephoneNumber'],
        ':email' => $_POST['email'],
        ':password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
        ':registrationCode' => $registrationCode,
    ]);

    // Send an email to the user with a link so he/she can finish the registration.
    send_email_to($_POST['email'], "Registratie afmaken", "finish_registration", [
        'firstname' => $_POST['firstName'],
        'lastname' => $_POST['lastName'],
        'registrationCode' => $registrationCode,
    ]);
    
    return [true, "Er is een email verzonden om uw registratie compleet te maken."];
}

$errors = [];
$successes = [];

// Try to register the user if the request method is a post.
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = validateData();
    if (empty($errors)) {
        list($success, $msg) = register_user();
        if ($success) {
            $successes[] = $msg;
        }
        else {
            $errors[] = $msg;
        }
    }
}


if (!empty($errors)) { ?>
    <div class="error-box">
    <?php foreach ($errors as $error ) { ?>
        <p><?= $error ?></p>
<?php } ?>
    </div> 
<?php 

}
if (!empty($successes)) { ?>
    <div class="success-box">
    <?php foreach ($successes as $success ) { ?>
        <p><?= $success ?></p>
<?php } ?>
    </div> 
<?php } ?>

<style>
.error-box {
    color: red;
}
.success-box {
    color: green;
}
</style>
<form method="POST">
    <table>
        <tr>
            <td>Voornaam</td>
            <td><input name="firstName" type="text" /></td>
        </tr>
        <tr>
            <td>Tussenvoegsel</td>
            <td><input name="middleName" type="text" /></td>
        </tr>
        <tr>
            <td>Achternaam</td>
            <td><input name="lastName" type="text" /></td>
        </tr>
        <tr>
            <td>Telefoonnummer</td>
            <td><input name="telephoneNumber" type="text" /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input name="email" type="email" /></td>
        </tr>
        <tr>
            <td>Wachtwoord</td>
            <td><input name="password" type="password" /></td>
        </tr>
        <tr>
            <td>Bevestig wachtwoord</td>
            <td><input name="password_confirmation" type="password" /></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" style="width:100%;" value="Registreren">
            </td>
        </tr>

    </table>
</form>
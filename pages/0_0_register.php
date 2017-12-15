<?php

// Make sure the title is set properly.
setTitle("Registreren");


// Checks if all posted data is valid.
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


// If the request is a post and the post has the correct key it returns the value from $_POST.
// Otherwise returns an empty string.
function getValue($key) {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }
    return '';
}


$errors = [];
$successes = [];

// Try to register the user if the request method is a post.
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = validateData();
    if (empty($errors)) {
        // Get two values from register_user. 
        // A boolean if the register attempt was successful, and a message telling what happened.
        list($success, $msg) = register_user();
        if ($success) {
            $successes[] = $msg;
        }
        else {
            $errors[] = $msg;
        }
    }
}


// If there are errors create a div with each error in it's own paragraph (<p>)
if (!empty($errors)) { ?>
    <div class="error-box">
        <?php foreach ($errors as $error ) { ?>
            <p><?= $error ?></p>
        <?php } ?>
    </div> 
<?php }


// If there is a message that things went well, create a div and fill it with the provided messages. 
// Do not continue with the rest of the page afterwards as the action went well.
if (!empty($successes)) { ?>
    <div>
        <?php foreach ($successes as $success ) { ?>
            <p><?= $success ?></p>
        <?php } ?>
    </div> 
<?php
    return;
} ?>

<style>
.error-box {
    color: red;
}
</style>
<form method="POST">
    <table>
        <tr>
            <td>Voornaam</td>
            <td><input name="firstName" value="<?= getValue("firstName") ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Tussenvoegsel</td>
            <td><input name="middleName" value="<?= getValue("middleName") ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Achternaam</td>
            <td><input name="lastName" value="<?= getValue("lastName") ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Telefoonnummer</td>
            <td><input name="telephoneNumber" value="<?= getValue("telephoneNumber") ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input name="email" value="<?= getValue("email") ?>" type="email" /></td>
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
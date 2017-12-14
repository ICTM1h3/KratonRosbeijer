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
    base_query("UPDATE User
    SET Firstname = :firstname, 
        Lastname = :lastname,
        MiddleName = :middlename,
        TelephoneNumber = :telephonenumber,
        Email = :email
    WHERE Id = :id", [
        ':firstname' => $_POST['Firstname'],
        ':lastname' => $_POST['Lastname'],
        ':middlename' => $_POST['MiddleName'],
        ':telephonenumber' => $_POST['TelephoneNumber'],
        ':email' => $_POST['Email'],
        ':id' => $_GET['userId'],
    ]);
}


if (!isset($_GET['userId'])) {
    return;
}

$errors = [];

if (isset($_POST['save'])) {
    $errors = validateData();
    if (empty($errors)) {
        update_user();
        header("Location: ?p=manageaccounts");
    }
}

$user = base_query("SELECT * FROM User WHERE Id = :id", [':id' => $_GET['userId']])->fetch();

function getValue($user, $key) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        return $_POST[$key];
    }
    return $user[$key];
}
?>

<h2>Gebruiker <?= $user['Lastname'] ?>
    <?php if (!isset($_GET['changemode'])) { ?>
        <a class="edit" href="?<?= $_SERVER['QUERY_STRING'] ?>&changemode=true">Wijzig</a>
    <?php } ?>
</h2>

<?php if (!empty($errors)) { ?>
    <div style="color:red">
        <?php foreach ($errors as $error ) { ?>
            <p><?= $error ?></p>
        <?php } ?>
    </div> 
<?php } ?>


<style>
    .edit {
        font-size:15px;
    }

    [disabled] {
        border: none;
        color: black;
        background-color: inherit;
    }
</style>

<?php $disabled = isset($_GET['changemode']) ? '' : 'disabled'; ?>

<form method="POST">
    <table>
        <tr>
            <td>Voornaam</td>
            <td><input <?= $disabled ?> name="Firstname" value="<?= getValue($user, "Firstname") ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Tussenvoegsel</td>
            <td><input <?= $disabled ?> name="MiddleName" value="<?= getValue($user, "MiddleName") ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Achternaam</td>
            <td><input <?= $disabled ?> name="Lastname" value="<?= getValue($user, "Lastname") ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Telefoonnummer</td>
            <td><input <?= $disabled ?> name="TelephoneNumber" value="<?= getValue($user, "TelephoneNumber") ?>" type="text" /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input <?= $disabled ?> name="Email" value="<?= getValue($user, "Email") ?>" type="Email" /></td>
        </tr>

        <?php if (isset($_GET['changemode'])) { ?>
        <tr>
            <td colspan="2">
                <input type="submit" style="width:100%;" name="save" value="Opslaan">
            </td>
        </tr>
        <?php } ?>
    </table>
</form>
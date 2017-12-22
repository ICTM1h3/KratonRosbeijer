<?php
// If registration is missing the following messages will apear.
if (!isset($_GET['registrationCode'])) {
    ?>U mist een registratiecode<?php
    return;
}

// Remove the registrationcode from the user.
$stmt = base_query("UPDATE User 
SET RegistrationCode = NULL
WHERE RegistrationCode = :registrationCode", [
    ":registrationCode" => $_GET['registrationCode'],
]);


// Check if the user row has been changed. If not, it means that there was no account
if ($stmt->rowCount() == 1) { ?>
<div class="alert alert-success" role="alert">
    <strong>U bent geregistreerd</strong>
</div>
<?php } else { ?>
<div class="alert alert-danger" role="alert">
    <strong>De activatiecode klopt of bestaat niet meer</strong>
</div>
<?php } ?>

<?php

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


if ($stmt->rowCount() == 1) { ?>
    <h2>U bent geregistreerd</h2>
<?php } else { ?>
    <h2>De activatiecode klopt of bestaat niet meer.</h2>
<?php } ?>

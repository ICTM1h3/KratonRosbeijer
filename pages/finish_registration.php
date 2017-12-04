<?php

if (!isset($_GET['registrationCode'])) {
    ?>U mist een registratiecode<?php
    return;
}

// Remove the registrationcode from the user.
base_query("UPDATE User 
SET RegistrationCode = NULL
WHERE RegistrationCode = :registrationCode", [
    ":registrationCode" => $_GET['registrationCode'],
]);

?>
<h2>U bent geregistreerd</h2>

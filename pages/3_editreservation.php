<?php

function getValue($reservation, $key) {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }
    else {
        return $reservation[$key];
    }
}
if (!isset($_GET['reservationId'])) {
    header("Location: ?p=managereservation");
    return;
}



$reservation = base_query("SELECT * FROM Reservation WHERE Id = :id", [':id' => $_GET['reservationId']])->fetch();
?>
<table>
    <tr>
        <td>Op naam van</td>
        <td><input type="text" name="InNameOf" value=<?= htmlentities(getValue($reservation, "InNameOf")) ?> /></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><input type="email" name="Email" value=<?= htmlentities(getValue($reservation, "Email")) ?> /></td>
    </tr>
    <tr>
        <td>Geactiveerd</td>
        <td><input type="checkbox" name="Activated" value=<?= htmlentities(getValue($reservation, "Activated")) ?> /></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><input type="email" name="Email" value=<?= htmlentities(getValue($reservation, "Email")) ?> /></td>
    </tr>
</table>
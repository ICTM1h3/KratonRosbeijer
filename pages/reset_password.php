<?php
session_start();

//function to change the page name
setTitle("Wachtwoord veranderen");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reset = base_query("UPDATE user
    SET Password
    ");
    //continue query, need to verify new password, new password set in database
}
?>

<form method="post" class="container">
    <h2>Nieuw Wachtwoord</h2>
    <div>
        <label>Nieuw Wachtwoord</label>
        <input type="password" name="password1" placeholder="Wachtwoord">
        <input type="password" name="password2" placeholder="Herhaal Wachtwoord">
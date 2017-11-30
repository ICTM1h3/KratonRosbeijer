<?php

//function to change the page name
setTitle("Wachtwoord veranderen");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reset = base_query("UPDATE User SET Password WHERE Password=:password" , array(":password" => $_POST["password1"]
    ))->fetch();
    if ($_POST["password1"] == $_POST["password2"]){
        password_hash($_POST["password1"]);
        echo("uw wachtwoord is reset.");
    } elseif ($_POST["password1"] || $_POST["password2"] == ""){
        echo("Vul beide velden in om uw wachtwoord te resetten.");
    } elseif ($_POST["password1"] != $_POST["password2"]){
        echo ("Wachtwoord is niet gelijk");
    }
    //continue query, need to verify new password, new password set in database
}
?>

<form method="post" class="container">
    <h2>Nieuw Wachtwoord</h2>
        <div class="form-group">
            <label>Nieuw Wachtwoord</label></br>
            <input type="password" name="password1" placeholder="Wachtwoord"></br>
            <input type="password" name="password2" placeholder="Herhaal Wachtwoord"></br>
        </div>
        <button class="btn btn-primary" type="submit" name="submit">RESET</button>
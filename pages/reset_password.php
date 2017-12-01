<?php

//function to change the page name
setTitle("Wachtwoord veranderen");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["password1"] || (empty($_POST["password2"])))){
        echo("Vul beide velden in om uw wachtwoord te resetten.");
    }
    elseif ($_POST["password1"] == $_POST["password2"]){
        $reset = base_query("UPDATE User SET Password=:password, ResetCode = null 
        WHERE ResetCode = :resetCode" ,
        array(":password" => password_hash($_POST["password1"],PASSWORD_BCRYPT), ":resetCode" => $_GET["resetCode"]));
        echo("uw wachtwoord is reset.");
    }else{
        echo ("Wachtwoord is niet gelijk");       
    }
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
</form>
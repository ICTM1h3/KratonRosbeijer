<?php

//function to change the page name
setTitle("Wachtwoord veranderen");

// checks if both field are filled in
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["password1"] || (empty($_POST["password2"])))){
        echo("Vul beide velden in om uw wachtwoord te resetten.");
    }
    elseif ($_POST["password1"] != $_POST["password2"]){
        echo ("Wachtwoord is niet gelijk");       
    }else{
        list($isStrong, $msg) = is_password_strong($_POST['password1']);
        if (!$isStrong) {
            echo $msg;
        }
        else {
            // Update the password of the user.
            $reset = base_query("UPDATE User SET Password=:password, ResetCode = null 
            WHERE ResetCode = :resetCode", array(
                ":password" => password_hash($_POST["password1"],PASSWORD_BCRYPT),
                ":resetCode" => $_GET["resetCode"])
            );

            // Check if one row (The row of the user account) has changed. If not it means there was no use with the provided resetcode.
            if ($reset->rowCount() == 1) {
                header("Location: ?p=inlogpage");
            }
            else {
                echo("Het account is niet gevonden.");
            }
        }
    }
}

?>

<div class="container">
      <form method="post" class="form-signin">
      <h2 class="form-signin-heading">Reset Wachtwoord</h2>
        <label for="inputPassword">Nieuw Wachtwoord</label>
        <input name="password1" type="password" id="inputPassword" class="form-control" placeholder="Nieuw Wachtwoord">
        
        <label for="inputPassword">Bevestig Nieuw Wachtwoord</label>
        <input name="password2" type="password" id="inputPassword" class="form-control" placeholder=" Herhaal Nieuw Wachtwoord">
        
        <button class="btn btn-lg btn-primary btn-block" type="submit">Reset</button>
    </form>
</div>
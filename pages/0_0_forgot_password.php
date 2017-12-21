<?php
//function to set the title name
setTitle("Wachtwoord vergeten");

//check if email is filled
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])){
        echo ("Vul een email in.");
    }
    else {
        
        $forget = base_query("SELECT * FROM User WHERE Email=:email", array(
            ":email" => $_POST["email"]
            ))->fetch();

        if ($forget != false) {
            $reset = hash("sha512", rand());
            base_query("UPDATE User SET ResetCode=:resetCode WHERE Email=:email", array(
                ":email" => $_POST["email"], ":resetCode" => $reset));
            send_email_to($_POST["email"], "Wachtwoord Vergeten", "reset_password", array("resetCode" => $reset));
            echo ("Er is een email verzonden.");
        }else{
            echo ("Onbekend emailadres.");
        }
            //var_dump($forget);
            //query to fetch all information from the user 
    }
}


    

?>

<div class="container">
      <form method="post" class="form-signin">
        <h2 class="form-signin-heading">Wachtwoord Vergeten</h2>
        <label for="inputEmail">E-mailadres</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="E-mailadres">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Versturen</button>
      </form>
    </div>


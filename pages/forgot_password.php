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

<form method="post" class="container">
    <h2>Wachtwoord vergeten</h2>
    <div class="form-group">
        <label>E-mailadres</label>
        <input type="email" name="email" placeholder="E-mailadres"/>
    </div>
        <button class="btn btn-primary" type="submit" name="send">VERSTUREN</button>
</form>


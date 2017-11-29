<?php
setTitle("Wachtwoord vergeten");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $forget = base_query("SELECT * FROM User WHERE Email=:email", array(
            ":email" => $_POST["email"]
    ))->fetch();
    var_dump($forget);
    if ($_POST["email"] == ""){
        echo ("Vul een email in."); 
    } elseif($_POST["email"] == $forget["Email"]) {
            mail("christiaanse.iris@gmail.com", "Wachtwoord vergeten", "Geachte Gast, \n Door op de volgende link te klikken kunt u een nieuw wachtwoord aanmaken:\n dit is een linkje whoop \n met vriendelijke groet, \n Iets");
            echo ("Er is een email verzonden.");
    } else {
        echo ("Onbekend emailadres.");
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


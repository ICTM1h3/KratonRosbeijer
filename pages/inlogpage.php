<?php
// sets the page name to the correct name
setTitle("Inlog pagina");

//query to fetch the information form the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = base_query("SELECT * FROM User WHERE Email=:email", array(
                ":email" => $_POST["email"]
        ))->fetch();
        //var_dump($user);
        //verify if the password exsists in the database
        if (password_verify($_POST["password"], $user["Password"])) {
                echo ("succes!");
                $_SESSION["UserId"] = $user["Id"];
        }
}

?>

<form method="post" class="container">
        <h2>Welkom op de login pagina</h2>
        <div class="form-group">
                <label>E-mailadres</label></br>
                <input type="text" name="email" placeholder="E-mailadres"/>
        </div>

        <div class="form-group">
                <label>Wachtwoord</label></br>
                <input type="password" name="password" placeholder="Wachtwoord"/>
        </div>

        <button class="btn btn-primary" type="submit" name="submit">LOGIN</button></br>
        
        <a href="?p=forgot_password">wachtwoord vergeten?</a>
        
</form>


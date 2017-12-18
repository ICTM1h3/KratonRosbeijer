<?php
// sets the page name to the correct name
setTitle("Inlog pagina");

// Create an array where we will echo errors
$errors = [];

//query to fetch the information form the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = base_query("SELECT * FROM User WHERE Email=:email", array(
                ":email" => $_POST["email"]
        ))->fetch();
        
        // Don't allow a user who hasn't activated the account to log in.
        if ($user['RegistrationCode'] != null) {
                $errors[] = "Dit account is nog niet geactiveerd.";
        }
        elseif (password_verify($_POST["password"], $user["Password"])) {
                $_SESSION["UserId"] = $user["Id"];
                header("Location: ?p=infopage");
                return;
        }
}

?>

<div class="container">
      <form method="post" class="form-signin">
        <?php
        if (!empty($errors)) { ?>
                <div style="color:red">
        <?php
                foreach ($errors as $error) {
                        ?><p><?= $error ?></p> <?php
                } ?>
                </div>
        <?php
        }
        ?>
        <h2 class="form-signin-heading">Login Pagina</h2>
        <label for="inputEmail">E-mailadres</label>
        <input type="text" name="email" id="inputEmail" class="form-control" placeholder="E-mailadres" required="" autofocus="">
        <label for="inputPassword">Wachtwoord</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Wachtwoord" required="">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div>
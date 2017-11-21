<?php
setTitle("Homepage <b>");

?>

<!-- This is the homepage, this is the standardpage-->

<h1>Welkom op de homepage</h1>

<?php $klant = base_query("SELECT * FROM klant")->fetchAll();
    var_dump($klant);
?> 
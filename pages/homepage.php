<?php
$pagetitle = "Karton";

?>

<!-- Dit is de homepage -->

<h1>Welkom op de homepage</h1>

<?php $klant = base_query("SELECT * FROM klant");
    echo $klant;
?> 
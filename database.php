<!-- Making connection with the database.

<?php

$db = "mysql:host=localhost; dbname=winkel; port=3306";
$user = "root";
$pass = "";
$db = new PDO($db, $user, $pass);

//Function for execute a query
function base_query($query, $params= NULL){
    global $db;
    //Met prepare zeg je. 'Dit is de query, ik geef je eventuele parameters later.'
    $stmt = $db->prepare($query);
    //Met execute zeg je daarna 'Voer deze query nu uit met de volgende parameters'
    $stmt->execute($params);
    return $stmt;

}


?>
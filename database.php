

<?php

$db = "mysql:host=localhost; dbname=winkel; port=3306";
$user = "root";
$pass = "";
$db = new PDO($db, $user, $pass);

//Function for execute a query
function base_query($query, $params= NULL){
    global $db;
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    return $stmt;

}


?>
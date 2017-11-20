<!-- Making connection with the database.

<?php

$db = "mysql:host=localhost; dbname=cursus; port=3306";
$user = "root";
$pass = "";
$pdo = new PDO($db, $user, $pass);

//Function for execute a query
function base_query(){
    global $db;
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt;

}


?>
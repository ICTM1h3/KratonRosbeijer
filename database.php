

<?php
$db = "mysql:host=localhost; dbname=kratonrosbeijer; port=3306";
$user = "root";
$pass = "";
$db = new PDO($db, $user, $pass);

//Function for execute a query
function base_query($query, $params = NULL) {
    // Grab the global $db object.
    global $db;
    // Tell PDO to prepare the query. We'll provide the parameters later on.
    $stmt = $db->prepare($query);

    // Execute the prepared query with the provided parameters.
    $stmt->execute($params);
    return $stmt;
}


?>
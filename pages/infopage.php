<?php
//sets the page name to the correct name//
setTitle("Info Pagina");
?>

<?php $info = base_query("SELECT * FROM setting WHERE name = 'Info' ")->fetch();
    echo($info['Value']);
    //used the function base_query to execute a query and echo's the result 
?> 


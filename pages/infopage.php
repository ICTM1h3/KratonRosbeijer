<?php
setTitle("Info Pagina");
?>

<?php $info = base_query("SELECT * FROM setting WHERE name = 'Info' ")->fetch();
    echo($info['Value']);
?> 


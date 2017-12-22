<?php
//set title to the correct name
setTitle("Beheer tafels");


if (isset($_POST['save_new'])) {
    // Create a new table with the specified capacity
    base_query("INSERT INTO `table` (Capacity) VALUES (:capacity)", [
        ':capacity' => $_POST['capacity']
    ]);
}
elseif (isset($_POST['save'])) {
    // Update the specified table with the new capacity
    base_query("UPDATE `table` SET Capacity = :capacity WHERE Id = :id", [
        ':capacity' => $_POST['capacity'],
        ':id' => $_POST['tableid']
    ]);
}
elseif (isset($_POST['switch_status'])) {
    // Deactivate the specified table
    base_query("UPDATE `table` SET Activated = NOT Activated WHERE Id = :id", [
        ':id' => $_POST['tableid']
    ]);
}
//Sending the right kitchen capacity to the database. 
if(isset($_POST['save_kitchen_capacity'])){
    base_query("UPDATE setting SET `Value` = :capacity WHERE `Name` = 'KitchenCapacity'", [
        ":capacity" => $_POST['kitchen_capacity']
    ]);   
}

//Getting the kitchen capacity from the database.
$capacity = base_query("SELECT `Value` FROM Setting WHERE `Name`= 'KitchenCapacity'")->fetchColumn();

//Getting the tables from the database.
$tables = base_query("SELECT * FROM `table` ORDER BY Activated DESC, Id DESC")->fetchAll();

?>

<h1>Beheer tafels</h1>

<script>
    // Function to enable or disable the button of a form if the the original value of the current element is different.
    function updateSaveButton(elem) {
        // Get the form of this button
        var form = elem.form;

        // Get the submit button of the form
        var submitBtn = form.querySelector("[type=submit]")

        // Disable the button if it's the same as it previously was.
        submitBtn.disabled = elem.getAttribute("data-original") == elem.value;
    }
</script>

<form method="POST">
    <table class="table">
        <tr>
            <th>Keukencapaciteit aanpassen</th>
        </tr>
        <tr>
            <td>
                <input class="form-control" type="number" value="<?= $capacity ?>" name="kitchen_capacity"/>
            </td>
            <td>
                <input class="btn btn-secondary" type="submit" name="save_kitchen_capacity" value="Opslaan"/>
            </td>
        </tr>
    </table>
</form>
<table class="table">
    <thead>
        <th>Capaciteit</th>
        <th>Opslaan</th>
        <th></th>
    </thead>
    <tbody>
        <tr>
            <td><input class="form-control" onkeyup="updateSaveButton(this)" onchange="updateSaveButton(this)" min="1" form="save_new" type="number" name="capacity" value="" data-original="" /></td>
            <td>
                <form id="save_new" method="POST">
                    <input class="btn btn-secondary" disabled type="submit" name="save_new" value="Maak nieuwe">
                </form>
            </td>
        </tr>
        <?php foreach ($tables as $table) {?> 
            <tr>
                <td><input class="form-control" onkeyup="updateSaveButton(this)" onchange="updateSaveButton(this)" min="1" form="save_<?= $table['Id'] ?>" type="number" name="capacity" value="<?= $table['Capacity'] ?>" data-original="<?= $table['Capacity'] ?>" /></td>
                <td>
                    <form id="save_<?= $table['Id'] ?>" method="POST">
                        <input type="hidden" name="tableid" value="<?= $table['Id'] ?>" />
                        <input class="btn btn-secondary" disabled type="submit" name="save" value="Opslaan">
                    </form>
                </td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="tableid" value="<?= $table['Id'] ?>" />
                        <input class="btn btn-secondary" type="submit" name="switch_status" value="<?= $table['Activated'] == 0 ? 'Activeer' : 'Deactiveer' ?>">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
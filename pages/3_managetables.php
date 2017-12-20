<?php
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

<table class="table">
    <thead>
        <th>Capaciteit</th>
        <th>Opslaan</th>
        <th></th>
    </thead>
    <tbody>
        <tr>
            <td><input onkeyup="updateSaveButton(this)" onchange="updateSaveButton(this)" min="1" form="save_new" type="number" name="capacity" value="" data-original="" /></td>
            <td>
                <form id="save_new" method="POST">
                    <input disabled type="submit" name="save_new" value="Maak nieuwe">
                </form>
            </td>
        </tr>
        <?php foreach ($tables as $table) {?> 
            <tr>
                <td><input onkeyup="updateSaveButton(this)" onchange="updateSaveButton(this)" min="1" form="save_<?= $table['Id'] ?>" type="number" name="capacity" value="<?= $table['Capacity'] ?>" data-original="<?= $table['Capacity'] ?>" /></td>
                <td>
                    <form id="save_<?= $table['Id'] ?>" method="POST">
                        <input type="hidden" name="tableid" value="<?= $table['Id'] ?>" />
                        <input disabled type="submit" name="save" value="Opslaan">
                    </form>
                </td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="tableid" value="<?= $table['Id'] ?>" />
                        <input type="submit" name="switch_status" value="<?= $table['Activated'] == 0 ? 'Activeer' : 'Deactiveer' ?>">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
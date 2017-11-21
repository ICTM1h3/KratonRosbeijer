<?php

// Check if the vacancy was provided. If not tell it to the user and return;
if (!isset($_GET['vacancy'])) {
    ?>
    Looks like something went wrong. You didn't provide the vacancy you want to edit.
    <?php
    return;
}

// Add an extra empty requirement for the admin to fill in case he wants to add a new one.
if (isset($_POST['new_requirement'])) {
    $_POST['Requirement'][] = '';
}

if (isset($_POST['save'])) {
    // Update the vacancy itself
    base_query("UPDATE Vacancy 
    SET Title = :title, 
        Description = :description, 
        Function = :function, 
        Employment = :employment
    WHERE Id = :vacancyId", [
            ':title' => $_POST['Title'],
            ':description' => $_POST['Description'],
            ':function' => $_POST['Function'],
            ':employment' => $_POST['Employment'],
            ':vacancyId' => $_GET['vacancy']
        ]);

    // Ugly: We delete the requirements first and then re-add them later. Can we do this better?
    base_query("DELETE FROM Requirement WHERE Vacancy = :vacancy", [':vacancy' => $_GET['vacancy']]);

    // Generate the query to insert all the requirements
    $params = [];
    $query = 'INSERT INTO Requirement (Requirement, Vacancy) VALUES ';
    foreach ($_POST['Requirement'] as $requirement) {
        $query .= '(?, ?),';
        $params = array_merge($params, [$requirement, $_GET['vacancy']]);
    }
    $query = substr($query, 0, -1);
    
    // Execute the generated query.
    base_query($query, $params);
}

// Get the values of the choosen vacancy
$vacancy = base_query("SELECT * FROM vacancy WHERE Id = :id", [
    ":id" => $_GET['vacancy'],
])->fetch();

// Get the requirements that are linked to this vacancy.
$requirements = base_query("SELECT * FROM Requirement WHERE Vacancy = :vacancy", [
    ':vacancy' => $_GET['vacancy'],
])->fetchAll();

// If the current request is a post request we get the values from $_POST.
// Otherwise we get it from the vacancy array that was retrieved from the database.
function getVacancyValue($vacancy, $name) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        return $_POST[$name];
    }
    else {
        return $vacancy[$name];
    }
}

// If the current request is a post request we get the requirements from the $_POST values.
// Otherwise we get it from the requirements array that was retrieved from the database.
function getRequirements($requirements) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return $_POST['Requirement'];
    }
    else {
        // Put all the values from the vacancy object into an array so we return the same layout as with with POST
        $result = [];
        foreach ($requirements as $requirement) {
            $result[] = $requirement['Requirement'];
        }
        return $result;
    }
}



?>

<form method="POST">
    <table>
        <tr>
            <td>Titel</td>
            <td><input type="text" name="Title" value="<?= getVacancyValue($vacancy, 'Title') ?>" /></td>
        </tr>
        <tr>
            <td>Functie</td>
            <td><input type="text" name="Function" value="<?= getVacancyValue($vacancy, 'Function') ?>" /></td>
        </tr>
        <tr>
            <td>Omschrijving</td>
            <td><textarea name="Description"><?= getVacancyValue($vacancy, 'Description') ?></textarea></td>
        </tr>
        <tr>
            <td>Dienstverband</td>
            <td><input type="text" name="Employment" value="<?= getVacancyValue($vacancy, 'Employment') ?>" /></td>
        </tr>
        <tr>
            <td rowspan="<?= count(getRequirements($requirements)) + 2 ?>">Eisen</td>
        </tr>
        <?php 
        foreach (getRequirements($requirements) as $requirement) { ?>
            <tr>
                <td>
                    <input type="text" name="Requirement[]" value="<?= $requirement ?>" />
                    <button onclick="delete_requirement(event)"/>Delete</button>
                </td>
            </tr>
            <?php 
        }
        ?>
        <tr>
            <td>
                <input type="submit" name="new_requirement" value="Voeg nieuwe eis toe" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input style="width:100%;" type="submit" name="save" value="Opslaan">
            </td>
        </tr>
    </table>
</form>

<script>
    function delete_requirement(event) {
        // Prevent the web page from refreshing.
        event.preventDefault();

        // Find the TR in which the current element is in.
        var elem = event.target;
        while (elem.nodeName != "TR") {
            elem = elem.parentNode;
        }

        // Remove the TR from the table.
        elem.parentNode.removeChild(elem);
    }
</script>

<?php
// Inserts requirements into the database and links it to the provided vacancy id.
function insert_requirements($vacancyId)
{
    // Genreate the query and params
    $params = [];
    $query = 'INSERT INTO Requirement (Requirement, Vacancy) VALUES ';
    foreach ($_POST['Requirement'] as $requirement) {
        $query .= '(?, ?),';
        $params = array_merge($params, [$requirement, $vacancyId]);
    }

    // Remove the trailing ',' at the end
    $query = substr($query, 0, -1);

    // Execute the generated query.
    base_query($query, $params);
}

// Update an existing vacancy based on the vacancy GET value
function update_vacancy()
{
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

    insert_requirements($_GET['vacancy']);
}

// Inserts a new vacancy into the database.
function insert_vacancy()
{
    // Get the PDO object to get the insert ID off
    global $db;

    // Insert the vacancy into the database
    base_query("INSERT INTO Vacancy (Title, Description, Function, Employment) VALUES (:title, :description, :function, :employment)", [
        ':title' => $_POST['Title'],
        ':description' => $_POST['Description'],
        ':function' => $_POST['Function'],
        ':employment' => $_POST['Employment'],
    ]);

    // Retrieve the ID of the new vacancy.
    $vacancyId = $db->lastInsertId();

    // Insert the requirements connecting it to the new vacancyId.
    insert_requirements($vacancyId);
}



// If the current request is a post request we get the values from $_POST.
// Otherwise we get it from the vacancy array that was retrieved from the database.
// If even that did not exist we return an empty value. 
// This can happen when the user is creating a new vacancy, not updating an existing one.
function getVacancyValue($vacancy, $name)
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        return $_POST[$name];
    } elseif (isset($vacancy[$name])) {
        return $vacancy[$name];
    } else {
        return '';
    }
}

// If the current request is a post request we get the requirements from the $_POST values.
// Otherwise we get it from the requirements array that was retrieved from the database.
function getRequirements($requirements)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return $_POST['Requirement'];
    } else {
        // Put all the values from the vacancy object into an array so we return the same layout as with with POST
        $result = [];
        foreach ($requirements as $requirement) {
            $result[] = $requirement['Requirement'];
        }

        // Add at least one requirement
        if (count($result) == 0) {
            $result[] = '';
        }
        return $result;
    }
}


// Checks if the filled in data is complete
function getFilledInDataErrors()
{
    $errors = [];
    if (empty($_POST['Title'])) {
        $errors[] = "Titel is leeg";
    } 
    
    if (empty($_POST['Function'])) {
        $errors[] = "Functie is leeg";
    } 
    
    if (empty($_POST['Description'])) {
        $errors[] = "Omschrijving is leeg";
    } 
    
    if (empty($_POST['Employment'])) {
        $errors[] = "Dienstverband is leeg";
    } 
    
    if (!array_filter($_POST['Requirement'])) {
        $errors[] = "U heeft geen eisen ingevult";
    }
    return $errors;
}


$vacancy = [];
$requirements = [];
$errors = [];

// Add an extra empty requirement for the admin to fill in case he wants to add a new one.
if (isset($_POST['new_requirement'])) {
    $_POST['Requirement'][] = '';
}


// Update or insert the vacancy.
if (isset($_POST['save'])) {
    $errors = getFilledInDataErrors();

    // Only actually insert/update if there are no problems with the filled in values.
    if (empty($errors)) {
        if (isset($_GET['vacancy'])) {
            update_vacancy();
        } else {
            insert_vacancy();
        }
        
        // Send the administrator to the vacancy overview page.
        header("Location: ?p=managevacancies");
    }

}


// If the user is trying to update a vacancy we extract those values from the database.
// We also update the title of the page
if (isset($_GET['vacancy'])) {
    // Get the values of the choosen vacancy
    $vacancy = base_query("SELECT * FROM vacancy WHERE Id = :id", [
        ":id" => $_GET['vacancy'],
    ])->fetch();

    // Get the requirements that are linked to this vacancy.
    $requirements = base_query("SELECT * FROM Requirement WHERE Vacancy = :vacancy", [
        ':vacancy' => $_GET['vacancy'],
    ])->fetchAll();

    setTitle("Aanpassen vacature: " . $vacancy['Title']);
} else {
    setTitle("Nieuwe vacature");
}


if (isset($_GET['vacancy'])) {
    ?><h2>Vacature wijzigen</h2><?php

} else {
    ?><h2>Vacature aanmaken</h2><?php

}

?>

    <?php foreach ($errors as $error) {
        ?><div class="alert alert-danger" role="alert">
        <p><?= $error ?></p> </div><?php
    }
    ?>

<div class="container">
      <form method="post" class="form-signin">
      <a class="btn btn-secondary" href="?p=managevacancies">Ga terug naar het overzicht</a>
        <table>
        <tr>
            <td>Titel</td>
            <td><input class="form-control" type="text" name="Title" value="<?= getVacancyValue($vacancy, 'Title') ?>" /></td>
        </tr>
        <tr>
            <td>Functie</td>
            <td><input class="form-control" type="text" name="Function" value="<?= getVacancyValue($vacancy, 'Function') ?>" /></td>
        </tr>
        <tr>
            <td>Omschrijving</td>
            <td><textarea class="form-control" name="Description"><?= getVacancyValue($vacancy, 'Description') ?></textarea></td>
        </tr>
        <tr>
            <td>Dienstverband</td>
            <td><input class="form-control" type="text" name="Employment" value="<?= getVacancyValue($vacancy, 'Employment') ?>" /></td>
        </tr>
        <tr>
            <td id="requirement-td" rowspan="<?= count(getRequirements($requirements)) + 2 ?>">Eisen</td>
        </tr>
        <?php 
        foreach (getRequirements($requirements) as $requirement) { ?>
            <tr>
                <td>
                    <input class="form-control" type="text" name="Requirement[]" value="<?= $requirement ?>" />
                    <input class="btn btn-secondary" onclick="delete_requirement(event)" value="Delete "/>
                </td>
            </tr>
            <?php 
        }
        ?>
        <tr>
            <td>
                <input class="btn btn-secondary" type="submit" name="new_requirement" value="Voeg nieuwe eis toe" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input class="btn btn-secondary" style="width:100%;" type="submit" name="save" value="Opslaan">
            </td>
        </tr>
    </table>
</form>
<i>Alle velden zijn verplicht.</i>

</div>

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

        // Update the rowspan of the id
        var td = document.getElementById("requirement-td");
        td.setAttribute("rowspan", td.getAttribute("rowspan") - 1)
    }
</script>

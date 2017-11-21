<?php

// Gives the page a title
setTitle("Vacancies");
// Fetching all data that is required for vacancies
$query = base_query('SELECT * FROM Vacancy')->fetchAll();
?>
<p>General information like how to contact the employer</p>
<?php
// Storing the data in variables
foreach ($query as $value) {
    $title = $value['Title'];
    $function = $value['Function'];
    $description = $value['Description'];
    $employment = $value['Employment'];
    // If the Id number is odd the element will float on the left
    if ($value['Id'] % 2 != 0) {
        $class = " style='float:left;'";
    } else {
        $class = " style='float:center;'";
    }
    ?>
    <table <?= $class ?>
        <tr>
            <td style="font-weight:bold;">
                 Titel: 
            </td>
            <td>
                <?= $title ?>
            </td>
        </tr>
        <tr>
            <td style="font-weight:bold;">
                 Functie categorie: 
            </td>
            <td> 
                <?= $function ?> 
            </td>
        </tr>
        <tr>
            <td style="font-weight:bold;">
                 Beschrijving: 
            </td>
            <td> 
                <?= $description ?> 
            </td>
        </tr>
        <tr>
            <td style="font-weight:bold;">
                 Dienstverband: 
            </td>
            <td> 
                <?= $employment ?>s 
            </td>
        </tr>
    </table>
    <br>
<?php
} 

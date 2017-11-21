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
    ?>
    <table>
        <tr>
            <td>
                <?= $title ?> 
            </td>
        </tr>
        <tr>
            <td> 
                <?= $function ?> 
            </td>
        </tr>
        <tr>
            <td> 
                <?= $description ?> 
            </td>
        </tr>
        <tr>
            <td> 
                <?= $employment ?>s 
            </td>
        </tr>
    </table>
<?php
}



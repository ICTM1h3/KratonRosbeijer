<?php

// Gives the page a title
setTitle("Vacatures");
// Fetching all data that is required for vacancies
$query = base_query('SELECT * FROM Vacancy')->fetchAll();

$VacancyInfo = base_query('SELECT Value FROM setting Where Name = "VacancyInfo"')->fetchColumn();

echo $VacancyInfo;
?>

<?php if (empty($query)) { ?>
    <i>Op dit moment zijn er geen vacatures</i>
    <?php
    return;
} ?>

<!-- Styling for 2 tables next to eachother -->
<style>
    .generalInfo {
        font:16px arial, sans-serif;
        width:75%;
    }
    .vacancy_container {
        display:flex;
        justify-content: space-between;
        flex-wrap:wrap;
    }

    .vacancy_container > div > table tr > td:first-child {
        font-weight:bold;
        padding-right:2px;
        width:100px;
    }

    .vacancy_container > div {
        width: 49%;
        max-width: 49%;
        margin: 2px;
        padding: 2px;
        overflow-wrap: break-word;
        border: 1px solid black;        
        border-radius: 15px;
    }
</style>


<!--Putting the data to the right place-->
<div class="vacancy_container">
<?php
// Storing the data in variables
foreach ($query as $value) {
    $title = $value['Title'];
    $function = $value['Function'];
    $description = $value['Description'];
    $employment = $value['Employment'];
    $requirements = base_query("SELECT * FROM Requirement WHERE Vacancy = :vacancy", [
        ':vacancy' => $value['Id']
        ]);   
    ?>
    <div>
        <table>
            <tr>
                <td>Titel: </td>
                <td><?= $title ?></td>
            </tr>
            <tr>
                <td>Functie:</td>
                <td><?= $function ?></td>
            </tr>
            <tr>
                <td>Beschrijving:</td>
                <td><?= $description ?></td>
            </tr>
            <tr>
                <td>Dienstverband:</td>
                <td><?= $employment ?></td>
            </tr>
            <tr>
                <td>Eisen:</td>
                <td>
                    <ul>
                    <?php foreach ($requirements as $requirement) {
                        ?> <li> <?= $requirement['Requirement'] ?> </li> <?php
                    }  ?>
                    </ul>
                </td>
        </table>
    </div>
<?php
} 
?> 
</div>
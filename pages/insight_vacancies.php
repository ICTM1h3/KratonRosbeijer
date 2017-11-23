<?php

// Gives the page a title
setTitle("Vacatures");
// Fetching all data that is required for vacancies
$query = base_query('SELECT * FROM Vacancy')->fetchAll();
?>
<p class="generalInfo">Wij zijn een leuk en gezellig team en op zoek naar jou. En wie zegt er nou nee tegen heerlijk Indonesisch eten. Dan ben je bij ons op het juiste adres.

<br>Heb je interesse neem dan per e-mail (info@kratonrosbeijer.nl) of per telefoon ( 033-8871111) contact met ons op.

<br>Welkom in onze Kraton familie (Selamat bergabung dengan keluarga Kraton).

<br>We zijn op zoek naar:</p>   
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

    .vacancy_container > table tr > td:first-child {
        font-weight:bold;
        width:100px;
    }

    .vacancy_container > table {
        width: 49%;
        height: 140px;
        padding: 2px;
        overflow-wrap: break-word;
        border: 1px solid black;
        
    }
</style>

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
    <table>
        <tr>
            <td>
                    Titel: 
            </td>
            <td>
                <?= $title ?>
            </td>
        </tr>
        <tr>
            <td>
                 Functie: 
            </td>
            <td> 
                <?= $function ?> 
            </td>
        </tr>
        <tr>
            <td>
                 Beschrijving: 
            </td>
            <td> 
                <?= $description ?> 
            </td>
        </tr>
        <tr>
            <td>
                 Dienstverband: 
            </td>
            <td> 
                <?= $employment ?>
            </td>
        </tr>
        <tr>
            <td>
                Eisen:
            </td>
            <td>
                <ul>
                <?php foreach ($requirements as $requirement) {
                    ?> <li> <?= $requirement['Requirement'] ?> </li> <?php
                }  ?>
                </ul>
            </td>
    </table>
    <br>
<?php
} 
?> 
</div>
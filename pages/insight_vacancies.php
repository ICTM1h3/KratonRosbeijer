<?php

// Gives the page a title
setTitle("Vacancies");
// Fetching all data that is required for vacancies
$query = base_query('SELECT * FROM Vacancy')->fetchAll();
?>
<p style="font:16px arial, sans-serif;width:75%;">Wij zijn een leuk en gezellig team en op zoek naar jou. En wie zegt er nou nee tegen heerlijk Indonesisch eten. Dan ben je bij ons op het juiste adres.

<br>Heb je interesse neem dan per e-mail (info@kratonrosbeijer.nl) of per telefoon ( 033-8871111) contact met ons op.

<br>Welkom in onze Kraton familie (Selamat bergabung dengan keluarga Kraton).

<br>We zijn op zoek naar:</p>   
<!-- Styling for 2 tables next to eachother -->
<style>
    .vacancy_container {
        display:flex;
        justify-content: space-between;
        flex-wrap:wrap;
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
    ?>
    <table>
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
?> 
</div>
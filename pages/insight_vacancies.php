<?php

// Gives the page a title
setTitle("Vacancies");
// Fetching all data that is required for vacancies
$query = base_query('SELECT * FROM Vacancy')->fetchAll();
?>
<p style="font:16px arial, sans-serif">Wij zijn een leuk en gezellig team en op zoek naar jou. En wie zegt er nou nee tegen heerlijk Indonesisch eten. Dan ben je bij ons op het juiste adres.

Heb je interesse neem dan per e-mail (info@kratonrosbeijer.nl) of per telefoon ( 033-8871111) contact met ons op.

Welkom in onze Kraton familie (Selamat bergabung dengan keluarga Kraton).

We zijn op zoek naar:</p>
<?php
// Storing the data in variables
foreach ($query as $value) {
    $title = $value['Title'];
    $function = $value['Function'];
    $description = $value['Description'];
    $employment = $value['Employment'];
    // If the Id number is odd the element will float on the left
    if ($value['Id'] % 2 != 0) {
        $class = " style='font-family:arial;float:left;border:1px solid grey;'";
    } else {
        $class = "style='font-family:arial;border:1px solid grey;'";
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

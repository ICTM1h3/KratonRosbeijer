
Beste <?=$parameters['name']?><br><br>

U heeft het volgende besteld bij Kraton Rosbeijer:<br><br>

<table>
    <tr>
        <th>Gerecht</th>
        <th>Prijs</th>
        <th>Aantal</th>
        <th>Subtotaal</th>
        <th>Cumulatief</th>
    </tr>
    <tr>
        <td><?php
if (isset($parameters['dishes'])) {
    foreach ($parameters['dishes'] as $value) {
        echo $value . "<br>";
    }
}
if (isset($parameters['categories'])) {
    foreach ($parameters['categories'] as $value) {
        echo $value . "<br>";
    }
}
        ?></td>
        <td><?php
if (isset($parameters['dishPrices'])) {
    foreach ($parameters['dishPrices'] as $value) {
        echo $value . "<br>";
    }
}
if (isset($parameters['categoryPrices'])) {
    foreach ($parameters['categoryPrices'] as $value) {
        echo $value . "<br>";
    }
}
        ?></td>
        <td><?php
if (isset($parameters['amountDishes'])) {
    foreach ($parameters['amountDishes'] as $value) {
        echo $value . "<br>";
    }
}
if (isset($parameters['amountCategories'])) {
    foreach ($parameters['amountCategories'] as $value) {
        echo $value . "<br>";
    }
}
        ?></td>
        <td><?php
if (isset($parameters['dishSubTotal'])) {
    foreach ($parameters['dishSubTotal'] as $value) {
        echo $value . "<br>";
    }
}
if (isset($parameters['categorySubTotal'])) {
    foreach ($parameters['categorySubTotal'] as $value) {
        echo $value . "<br>";
    }
}
        ?></td>
        <td><?php
if (isset($parameters['dishCumulative'])) {
    foreach ($parameters['dishCumulative'] as $value) {
        echo $value . "<br>";
    }
}
if (isset($parameters['categoryCumulative'])) {
    foreach ($parameters['categoryCumulative'] as $value) {
        echo $value . "<br>";
    }
}
        ?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <th>Totaal: </th>
        <td><?=$parameters['totalPrice']?></td>
</table>

Daarnaast heeft u de volgende gegevens opgegeven:<br><br>

Email: <?=$parameters['email']?><br>
Telefoonnummer: <?=$parameters['telNumber']?><br>
Datum van afhalen: <?=$parameters['date']?><br>
Tijdstip van afhalen: <?=$parameters['time']?><br><br>

Bedankt voor uw bestelling en tot snel!<br><br>

Met vriendelijk groet,<br><br>

Kraton Rosbeijer
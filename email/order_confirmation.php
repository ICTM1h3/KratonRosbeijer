
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
foreach ($parameters['dishes'] as $value) {
    echo $value . "<br>";
}
foreach ($parameters['categories'] as $value) {
    echo $value . "<br>";
}
        ?></td>
        <td><?php
foreach ($parameters['dishPrices'] as $value) {
    echo $value . "<br>";
}
foreach ($parameters['categoryPrices'] as $value) {
    echo $value . "<br>";
}
        ?></td>
        <td><?php
foreach ($parameters['amountDishes'] as $value) {
    echo $value . "<br>";
}
foreach ($parameters['amountCategories'] as $value) {
    echo $value . "<br>";
}
        ?></td>
        <td><?php
foreach ($parameters['dishSubTotal'] as $value) {
    echo $value . "<br>";
}
foreach ($parameters['categorySubTotal'] as $value) {
    echo $value . "<br>";
}
        ?></td>
        <td><?php
foreach ($parameters['dishCumulative'] as $value) {
    echo $value . "<br>";
}
foreach ($parameters['categoryCumulative'] as $value) {
    echo $value . "<br>";
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
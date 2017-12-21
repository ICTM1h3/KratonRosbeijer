
Beste <?=$parameters['name']?><br><br>

U heeft de volgende cadeaubon(nen) besteld:<br>

<table>
    <tr>
        <td><?php
            foreach ($parameters['couponCodes'] as $value) {
                echo $value . "<br>";
            }
        ?></td>
        <td><?php
            foreach ($parameters['couponPrizes'] as $value) {
                echo $value . "<br>";
            }
        ?></td>
    </tr>
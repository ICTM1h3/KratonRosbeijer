
Beste <?=$parameters['name']?><br><br>

U heeft de volgende cadeaubon(nen) besteld:<br>

<table>
    <tr>
        <th>Cadeaukaart code</th>
        <th>Startwaarde</th>
        </tr>
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
</table>

Uw email: <?php$parameters['email']?><br><br>

Met vriendelijke groet,<br><br>

Kraton Rosbeijer
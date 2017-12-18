<?php

$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
$currentDate = date("Y-m-d H:i:s");

$reservations = base_query("SELECT * 
FROM Reservation
WHERE date BETWEEN :startDate AND :endDate
ORDER BY date", [
    ':startDate' => $date . ' 00:00:00',
    ':endDate' => $date . ' 23:59:59'
])->fetchAll();

// var_dump($reservations);
?>

<form action="?p=managereservation">
    <input type="hidden" name="p" value="<?= $_GET['p'] ?>">
    <label for="date">Reserveringen voor</label>
    <input id="date" value="<?= $date ?>" type="date" name="date" onchange="this.form.submit()" />
</form>

<?php if (empty($reservations)) { ?>

<?php } else { ?>
<style>
    .outdated-reservation {
        background-color: #efefef;
    }

    .not-activated-reservation {
        background-color: #ffacac;
    }
</style>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Tijd</th>
                <th>Hoeveelheid personen</th>
                <th>Op naam van</th>
                <th>Email</th>
                <th>Telefoonnummer</th>
                <!-- <th></th> -->
                <!-- <th></th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation) {
                $class = '';
                if ($reservation['Activated'] == 0) {
                    $class = "not-activated-reservation";
                }
                elseif ($reservation['Date'] < $currentDate) {
                    $class = 'outdated-reservation';
                }

                $matches = [];
                preg_match('/(\d{2}:\d{2}):\d{2}$/', $reservation['Date'], $matches);
                ?>
                <tr class="<?= $class ?>">
                    <td><?= $matches[1] ?></td>
                    <td><?= htmlentities($reservation['AmountPersons']) ?></td>
                    <td><?= htmlentities($reservation['InNameOf']) ?></td>
                    <td><?= htmlentities($reservation['Email']) ?></td>
                    <td><?= htmlentities($reservation['TelephoneNumber']) ?></td>
                    <td><a href="?p=editreservation&reservationId=<?= $reservation['Id'] ?>">Edit</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>
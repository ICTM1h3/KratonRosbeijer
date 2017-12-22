<?php
//set title to the correct name
setTitle("Beheer reserveringen");

if (isset($_POST['IsNoShow'])) {
    base_query("UPDATE Reservation SET IsNoShow = :isNoShow WHERE Id = :id", [
        ':isNoShow' => $_POST['IsNoShow'],
        ':id' => $_POST['ReservationId']
    ]);
}


// Use the specified date or today's date if not specified.
$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

// Get the date and time which we can use to compare to dates in the database.
$currentDate = date("Y-m-d H:i:s");

// Get all the dates on the specified day.
$reservations = base_query("SELECT * 
FROM Reservation
WHERE date BETWEEN :startDate AND :endDate
ORDER BY date", [
    ':startDate' => $date . ' 00:00:00',
    ':endDate' => $date . ' 23:59:59'
])->fetchAll();



?>

<form action="?p=managereservation">
    <input type="hidden" name="p" value="<?= $_GET['p'] ?>">
    <label for="date">Reserveringen voor</label>
    <input class="form-control" id="date" value="<?= $date ?>" type="date" name="date" onchange="this.form.submit()" />
</form>

<?php if (empty($reservations)) { ?>
    Op deze datum zijn geen reserveringen.
<?php } else { ?>
<style>
    .outdated-reservation {
        background-color: #efefef;
    }

    .is-no-show {
        background-color: #ffd1d1;
    }
    
    .not-activated-reservation {
        opacity:0.4;
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
                <th>Kwam niet opdagen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation) {
                $class = '';
                if ($reservation['Activated'] == 0) {
                    $class = "not-activated-reservation";
                }
                elseif ($reservation['IsNoShow'] == 1) {
                    $class = "is-no-show";
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
                    <td>
                        <?php if ($reservation['UserId'] != null) { ?>
                            <a href="?p=userdetails&userId=<?= $reservation['UserId']?>">
                        <?php } ?>
                        <?= htmlentities($reservation['InNameOf']) ?>
                        <?php if ($reservation['UserId'] != null) { ?>
                            </a>
                        <?php } ?>
                    </td>
                    <td><a href="mailto:<?= htmlentities($reservation['Email']) ?>"><?= htmlentities($reservation['Email']) ?></a></td>
                    <td><?= htmlentities($reservation['TelephoneNumber']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="ReservationId" value="<?= $reservation["Id"] ?>">
                            <input type="hidden" name="IsNoShow" value="<?= $reservation['IsNoShow'] ?>">
                            <input type="checkbox" <?= $reservation['IsNoShow'] == 1 ? 'checked' : ''?> onclick="this.previousElementSibling.value=this.checked ? 1 : 0; this.form.submit();"/>
                        </form>
                    </td>
                    <td><a href="?p=editreservation&reservationId=<?= $reservation['Id'] ?>">Edit</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>
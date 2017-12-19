<?php

// If the request is a post it returns values from there. 
// Otherwise it returns it from the provided reservation (array).
function getValue($reservation, $key) {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }
    else {
        return $reservation[$key];
    }
}


// Checks if all posted data is correct. 
// Returns an array of errors which is empty if there were no errors.
function validateData() {
    $errors = [];
    if (empty($_POST['InNameOf'])) {
        $errors[] = "U heeft niet ingevult op wie zijn naam de reservering moet worden ingevuld.";
    }

    if (empty($_POST['Email'])) {
        $errors[] = "U moet een email opgeven.";
    }
    elseif (!is_email_valid($_POST['Email'])) {
        $errors[] = "U heeft geen geldig email opgegeven";
    }

    if (empty($_POST['TelephoneNumber'])) {
        $errors[] = "U moet een telefoonnummer opgeven.";
    }
    elseif (!is_valid_telephone_number($_POST['TelephoneNumber'])) {
        $errors[] = "U heeft geen geldig telefoon nummer ingevult";
    }

    if (empty($_POST['AmountPersons']) || !filter_var($_POST['AmountPersons'], FILTER_VALIDATE_INT)) {
        $errors[] = "U moet opgeven met hoeveel u komt.";
    }

    if (empty($_POST['Date'])) {
        $errors[] = "U moet een datum opgeven.";
    }
    elseif (!is_date_valid($_POST['Date'])) {
        $errors[] = "De datum is niet in het correcte format (yyyy-mm-dd)";
    }

    if (empty($_POST['Time'])) {
        $errors[] = "U moet een tijd opgeven.";
    }
    elseif (!is_time_valid($_POST['Time'])) {
        $errors[] = "De tijd is niet in het correcte format (hh:mm)";
    }

    return $errors;
}

// Returns a list of all free tables at the specified time.
function getFreeTables($date, $currentReservationId, $reservedHours) {
    // Get all free tables in a 2 hour span
    $tables = base_query("SELECT t.id, t.capacity FROM kratonrosbeijer.`table` t
    WHERE t.id NOT IN (
	    SELECT DISTINCT tr.tableid FROM kratonrosbeijer.table_reservation tr
        INNER JOIN kratonrosbeijer.Reservation r ON r.id = tr.reservationid
        WHERE r.date >= :date 
        AND r.date <= DATE_ADD(:date, INTERVAL :reservedHours HOUR)
        AND r.activated = 1
        AND r.Id <> :currentReservationId
    )
    ORDER BY t.capacity DESC;", [
        ':date' => $date,
        ':currentReservationId' => $currentReservationId
    ])->fetchAll();

    return $tables;
}

// Looks for available tables until it reaches enough capacity to hold the requested amount of people.
// Returns a boolean, true if successfull and false if not.
// Also returns a list of table ids to which together can hold the requested amount.
function tryGetFreeTablesForCapacity($requiredCapacity, $date, $currentReservationId) {
    // Look if the kitchen can handle the amount of persons.
    $capacity = base_query("SELECT SUM(r.AmountPersons) FROM kratonrosbeijer.Reservation r
    WHERE r.date >= :date 
    AND r.date <= DATE_ADD(:date, INTERVAL 2 HOUR)", [':date' => $date])->fetchColumn();
    $capacity = $capacity != null ? $capacity : 0;
     
    $maximumCapacity = base_query("SELECT Value FROM setting WHERE Name = \"KitchenCapacity\" ")->fetchColumn();
    if (($capacity + $requiredCapacity) > $maximumCapacity) {
        return [false, []];
    }

    $tables = getFreeTables($date, $currentReservationId, $requiredCapacity >= 12 ? 2.5 : 2);
    
    // Loop through all the tables which are free around the provided hours and add it to an array until we got enough capacity to hold all people.
    $tablesForReservation = [];
    $accumalatedCapacity = 0;
    $foundEnough = false;
    foreach ($tables as $table) {
        $accumalatedCapacity += $table['capacity'];
        $tablesForReservation[] = $table['id'];

        // If the capacity of tables which can hold all the people is high enough we can stop.
        if ($accumalatedCapacity >= $requiredCapacity) {
            $foundEnough = true;
            break;
        }
    }

    // ToDo: Currently the algorithm can be wasteful as it looks through the tables with the capacity descending.
    // This means for a reservation of 10 people this algorithm can link two tables with a capacity of 8.
    return [$foundEnough, $tablesForReservation];
}




// Updates the reservation.
function update_reservation() {
    // Combine the posted date and time to the same format as in the database
    $date = $_POST['Date'] . ' ' . $_POST['Time'];
    
    { 
        $oldData = base_query("SELECT * FROM Reservation WHERE Id = :id", [':id' => $_GET['reservationId']])->fetch();
        $reservationId = $_GET['reservationId'];
        // If the date has changed find new tables for this reservation.
        if ($oldData['Date'] != $date){
            list($foundEnough, $tables) = tryGetFreeTablesForCapacity($_POST['AmountPersons'], $date, $reservationId);
            if (!$foundEnough) {
                return [false, "Er zijn niet genoeg tafels beschikbaar op de gevraagde tijd en datum."];
            }
            else {
                // Remove the current tables from the reservation.
                base_query("DELETE FROM Table_Reservation WHERE ReservationId = :id", [':id' => $reservationId]);

                // Assign the new tables to this reservation.
                $query = "INSERT INTO table_reservation (TableId, ReservationId) VALUES ";
                $params = [];
                foreach ($tables as $tableId) {
                    $params = array_merge($params, [$tableId, $reservationId]);
                    $query .= '(?, ?),';
                }
                
                // Remove the trailing ',' at the end
                $query = substr($query, 0, -1);
                base_query($query, $params);
            }
        }
    }

    base_query("UPDATE Reservation 
    SET InNameOf = :InNameOf,
        Email = :Email,
        Date = :Date,
        TelephoneNumber = :TelephoneNumber,
        AmountPersons = :AmountPersons,
        Notes = :Notes,
        Activated = :Activated
    WHERE Id = :Id", [
        ':InNameOf' => $_POST['InNameOf'],
        ':Email' => $_POST['Email'],
        ':Date' => $date,
        ':TelephoneNumber' => $_POST['TelephoneNumber'],
        ':AmountPersons' => $_POST['AmountPersons'],
        ':Notes' => $_POST['Notes'],
        ':Activated' => $_POST['Activated'],
        ':Id' => $reservationId
    ]);

    // Send an email to notify the customer that the reservation has changed.
    $emailParameters = [
        'inNameOf' => $_POST['InNameOf'],
        'email' => $_POST['Email'],
        'telephone' => $_POST['TelephoneNumber'],
        'date' => $date,
        'amountPersons' => $_POST['AmountPersons'],
        'notes' => $_POST['Notes'],
    ];

    send_email_to($_POST['Email'], 'Uw reservering is aangepast', 'changed_reservation', $emailParameters);
    return [true];
}

// Redirect the user to a list of all reservations if no reservation was provided.
if (!isset($_GET['reservationId'])) {
    header("Location: ?p=managereservation");
    return;
}

$errors = [];

// Try to save the reservation if requested.
if (isset($_POST['Save'])) {
    $errors = validateData();
    if (empty($errors)) {
        list($success, $msg) = update_reservation();
        if ($success) {
            header("Location: ?p=managereservation");
        }
        else {
            $errors[] = $msg;
        }
    }
}

$reservation = base_query("SELECT * FROM Reservation WHERE Id = :id", [':id' => $_GET['reservationId']])->fetch();

?>

<form method="POST">
    <table class="table">
        <tr>
            <td><label for="InNameOf">Op naam van</label></td>
            <td><input id="InNameOf" type="text" name="InNameOf" value="<?= htmlentities(getValue($reservation, "InNameOf")) ?>" /></td>
        </tr>
        <tr>
            <td><label for="Email">Email<label></td>
            <td><input id="Email" type="email" name="Email" value="<?= htmlentities(getValue($reservation, "Email")) ?>" /></td>
        </tr>
        <tr>
            <td><label label="TelephoneNumber">Telefoonnummer</label></td>
            <td><input id="TelephoneNumber" type="text" name="TelephoneNumber" value="<?= htmlentities(getValue($reservation, "TelephoneNumber")) ?>" /></td>
        </tr>
        <tr>
            <td><label for="Activated">Geactiveerd</label></td>
            <td>
                <input type="hidden" name="Activated" value="<?= htmlentities(getValue($reservation, "Activated")) ?>">
                <input id="Activated" type="checkbox" <?= htmlentities(getValue($reservation, "Activated")) == 1 ? 'checked' : ''?> onclick="this.previousElementSibling.value=this.checked ? 1 : 0"/>
            </td>
        </tr>
        <tr>
            <td><label for="AmountPersons">Hoeveelheid personen</label></td>
            <td><input id="AmountPersons" type="number" name="AmountPersons" value="<?= htmlentities(getValue($reservation, "AmountPersons")) ?>" /></td>
        </tr>
        <tr>
            <td><label for="Date">Datum</label></td>
            <td><input id="Date" type="date" name="Date" value="<?= htmlentities(explode(' ', getValue($reservation, "Date"))[0]) ?>" /></td>
        </tr>
        <tr>
            <td><label for="Time">Tijd</label></td>
            <td><input id="Time" type="time" name="Time" value="<?= htmlentities(isset($_POST['Time']) ? $_POST['Time'] : explode(' ', getValue($reservation, "Date"))[1]) ?>" /></td>
        </tr>
        <tr>
            <td><label for="Notes">Bijzonderheden</label></td>
            <td><textarea id="Notes" name="Notes"><?= htmlentities(getValue($reservation, "Notes"))?></textarea></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" name="Save" value="Opslaan" /></td>
        </tr>
    </table>
</form>
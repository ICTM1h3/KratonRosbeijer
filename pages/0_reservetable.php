<?php
setTitle("Reserveren");

// Validates all posted data and returns a list of errors.
// Returns empty array of no errors occured.
function validateData()
{
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

    if (empty($_POST['Telephone'])) {
        $errors[] = "U moet een telefoonnummer opgeven.";
    }
    elseif (!is_valid_telephone_number($_POST['Telephone'])) {
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

// If the user is logged in save the data of the user in a global so we can use it to pre-fill fields later on.
if (isset($_SESSION['UserId'])) {
    $GLOBALS['userObj'] = base_query("SELECT Lastname, Email, TelephoneNumber FROM User WHERE Id = :id", [':id' => $_SESSION['UserId']])->fetch();
}


// If the current request method is a post it returns the posted value. If not it returns an empty string.
function getValue($key) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return $_POST[$key];
    }

    if (!isset($GLOBALS['userObj'])) {
        return '';
    }
    $userObj = $GLOBALS['userObj'];
    switch ($key) {
        case 'InNameOf':
            return $userObj['Lastname'];
        case 'Email':
            return $userObj['Email'];
        case 'Telephone':
            return $userObj['TelephoneNumber'];
        default:
            return '';
    }
}

// Returns a list of all free tables at the specified time.
function getFreeTables($date, $reservedHours) {
    // Get all free tables in a 2 hour span
    $tables = base_query("SELECT t.id, t.capacity FROM kratonrosbeijer.`table` t
    WHERE t.id NOT IN (
	    SELECT DISTINCT tr.tableid FROM kratonrosbeijer.table_reservation tr
        INNER JOIN kratonrosbeijer.Reservation r ON r.id = tr.reservationid
        WHERE r.date >= :date 
        AND r.date <= DATE_ADD(:date, INTERVAL :reservedHours HOUR)
        AND r.activated = 1
    )
    ORDER BY t.capacity DESC;", [':date' => $date])->fetchAll();

    return $tables;
}

// Looks for available tables until it reaches enough capacity to hold the requested amount of people.
// Returns a boolean, true if successfull and false if not.
// Also returns a list of table ids to which together can hold the requested amount.
function tryGetFreeTablesForCapacity($requiredCapacity, $date) {
    // Look if the kitchen can handle the amount of persons.
    $capacity = base_query("SELECT SUM(r.AmountPersons) FROM kratonrosbeijer.Reservation r
    WHERE r.date >= :date 
    AND r.date <= DATE_ADD(:date, INTERVAL 2 HOUR)", [':date' => $date])->fetchColumn();
    $capacity = $capacity != null ? $capacity : 0;
     
    $maximumCapacity = base_query("SELECT Value FROM setting WHERE Name = \"KitchenCapacity\" ")->fetchColumn();
    if (($capacity + $requiredCapacity) > $maximumCapacity) {
        return [false, []];
    }

    $tables = getFreeTables($date, $requiredCapacity >= 12 ? 2.5 : 2);
    
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


// Creates a reservation using the provided info. 
// If there are not enough tables it will return false with an error.
// If there are enough tables it links the tables to the reservation and returns true.
function createReservation($inNameOf, $email, $date, $telephoneNumber, $amountPersons, $notes, $userId = null) {
    // Get the PDO object to retrieve the last inserted id.
    global $db;
    
    list($foundEnough, $tablesForReservation) = tryGetFreeTablesForCapacity($amountPersons, $date);
    
    // In case there isn't enough capacity to serve the requested amount of people send an error to the user.
    if (!$foundEnough) {
        return [false, "Er is niet genoeg capaciteit om een reservering van " . htmlentities($amountPersons) . ' personen op te nemen'];
    }
    
    // Create the reservation
    base_query("INSERT INTO Reservation 
                (InNameOf, Email, Date, TelephoneNumber, AmountPersons, Notes, Activated, UserId) VALUES
                (:InNameOf, :Email, :Date, :TelephoneNumber, :AmountPersons, :Notes, :Activated, :UserId)", [
        ':InNameOf' => $inNameOf,
        ':Email' => $email,
        ':Date' => $date,
        ':TelephoneNumber' => $telephoneNumber,
        ':AmountPersons' => $amountPersons,
        ':Notes' => $notes,
        ':Activated' => true,
        ':UserId' => $userId // TODO: If logged in retrieve the user id.
    ]);

    $reservationId = $db->lastInsertId();

    // Generate the query to link the reservation to the tables.
    $query = "INSERT INTO table_reservation (TableId, ReservationId) VALUES ";
    $params = [];
    foreach ($tablesForReservation as $tableId) {
        $params = array_merge($params, [$tableId, $reservationId]);
        $query .= '(?, ?),';
    }
    
    // Remove the trailing ',' at the end
    $query = substr($query, 0, -1);
    base_query($query, $params);

    return [true, null];
}


function generateCSRFToken() {
    return sha1(rand());
}


function checkCSRFToken() {
    return $_SESSION['CSRFToken'] == $_POST['CSRFToken'];
}

function newCSRFToken() {
    $token = generateCSRFToken();
    $_SESSION['CSRFToken'] = $token;
    return $token;
}

$errors = [];
$successes = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Used to protect against Cross-site request forgery, but also to prevent the form to be posted twice.
    if (!checkCSRFToken()) {
        $errors[] = "U kunt niet een reservering twee keer aanvragen";
    }

    $errors = array_merge($errors, validateData());
    if (!empty($errors)) {

    } else {
        $date = format_date_and_time($_POST['Date'], $_POST['Time']);
        $amountPersons = $_POST['AmountPersons'];
        $inNameOf = $_POST['InNameOf'];
        $userId = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : null;
        list($success, $error) = createReservation($inNameOf, $_POST['Email'], $date, $_POST['Telephone'], $amountPersons, $_POST['Notes'], $userId);

        if (!$success) {
            $errors[] = $error;
        }
        else {
            
            $emailParameters = [
                'inNameOf' => $inNameOf,
                'email' => $_POST['Email'],
                'telephone' => $_POST['Telephone'],
                'date' => $date,
                'amountPersons' => $amountPersons,
                'notes' => $_POST['Notes'],
            ];
            
            // Send an email to the user.
            send_email_to($_POST['Email'], 'Uw reservering is aangemaakt', 'created_reservation', $emailParameters);
            
            if ($amountPersons >= 12) {
                // If the amount of persons for the reservation is 12 or higher send an email the the administrator.
                $websiteMail = $GLOBALS['config']['WebsiteEmail'];
                send_email_to($websiteMail, "Er is een groepsreservering aangemaakt", "group_reservation", $emailParameters);
            }
            
            $successes[] = "Uw reservering is aangemaakt.";
        }
    }
}


?>

<style>
    .error-box {
        color:red;
    }

    .success-box {
        color:green;
    }
</style>
<h1>Reserveren</h1>
<?php
    if (!empty($errors)) { ?>
        <div class="error-box"> <?php
            foreach ($errors as $error) {
                ?> <p><?= $error ?></p> <?php
            }
        ?> </div> 
    <?php }

    if (!empty($successes)) { ?>
        <div class="success-box"> <?php
            foreach ($successes as $msg) {
                ?> <p><?= $msg ?></p> <?php
            }
        ?> </div> 
        <?php
        newCSRFToken();
        return;
    }
    
?>
<form method="POST">
    <input type="hidden" value="<?= newCSRFToken()?>" name="CSRFToken" />
    <table>
        <tr>
            <td>Op naam van*</td>
            <td><input type="text" name="InNameOf" value="<?= getValue('InNameOf') ?>" /></td>
        </tr>
        <tr>
            <td>Email*</td>
            <td><input type="email" name="Email" value="<?= getValue('Email') ?>"/></td>
        </tr>
        <tr>
            <td>Telefoon*</td>
            <td><input type="text" name="Telephone" value="<?= getValue('Telephone') ?>" /></td>
        </tr>
        <tr>
            <td>Aantal personen*</td>
            <td><input type="number" name="AmountPersons" value="<?= getValue('AmountPersons') ?>" /></td>
        </tr>
        <tr>
            <td>Datum en tijdstip*</td>
            <td>
                <input type="date" name="Date" placeholder="YYYY-MM-DD" min="<?= date("Y-m-d") ?>" value="<?= getValue('Date') ?>" />
                <input type="time" name="Time" placeholder="HH:mm" value="<?= getValue('Time') ?>" />
            </td>
        </tr>
        <tr>
            <td>Bijzonderheden</td>
            <td><textarea name="Notes"><?= getValue('Notes') ?></textarea></td>
        </tr>
    </table>

    <i>Velden met een * zijn verplicht</i>

    <input type="submit" value="Reserveren" />
</form>
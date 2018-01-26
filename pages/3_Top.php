<?php

// function to set the title from the page
setTitle("Top 5");

/*
use for the function base_query to have easier use of the parameters with prepared sql statements and therefore prevent sql injections
base_query function used to select the needed information
*/

$top_users = base_query("SELECT count(r.Id), u.Firstname

-- tables needed for the information
FROM Reservation r
JOIN User u ON r.UserId = u.Id

-- used to show 1 user and show the amount of reservations from high to low
GROUP BY UserId
ORDER BY count(r.Id) DESC")->fetchAll();

// //check if the query works
// var_dump($top_users);

?>


<div class="container">
    <form method="post" class="form-signin">
    

        <h2>Top 5 Meeste Reserveringen </h2>
        <a class="btn btn-secondary" href="?p=managereservation">Ga terug naar het overzicht</a>

        <table class = "table">
                <tr>
                    <th>Plek</th>
                    <th>Naam</th>
                    <th>Aantal Reserveringen</th>
                </tr>
                <?php

                // if there are less then 5 unique users, count the results form the query
                if (count($top_users) < 5){
                    $max = count($top_users);

                    // else the max = 5 (top 5)
                } else {
                    $max = 5;
                }

                /* 
                $place is used for the place people have
                $previous is used to check if the previous place is the same as the current place
                */
                $place = 0;
                $previous = 0;
                for ($i = 0; $i < $max; $i++){
                    $user = $top_users[$i];

                    // checks if previous has the same amount of reservations as the current user, if not $place +1
                    if ($previous != $user['count(r.Id)']){
                        $place++;
                    }


                    ?>

                    <tr>
                        <td class="number"><?php print ($place) ?> </td>
                        <td><?php print (htmlentities(($user['Firstname']))); ?></td>
                        <td><?php print (htmlentities(($user['count(r.Id)']))); ?></td>
                    </tr>
                <?php
                
                // previous becomes the current user in the new loop
                $previous = $user['count(r.Id)'];
                }
                ?>
        </table>
    </form>
</div>

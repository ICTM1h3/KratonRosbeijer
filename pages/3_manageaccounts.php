<?php



function createOptionsFor($user) {
    $res = "";
    foreach (getAllRoles() as $id => $role) {
        $selected = ($user['Role'] == $id) ? 'selected="selected"' : '';
        $res .= "<option value='$id' $selected>$role</option>\r\n";
    }
    return $res;
}

function update_role($userId, $role) {
    base_query("UPDATE User SET Role = :role WHERE Id = :id", [':role' => $role, ':id' => $userId]);
}


function switch_user_activation_status($userId) {
    base_query("UPDATE User SET Activated = NOT Activated WHERE Id = :id", [':id' => $userId]);
}

if (isset($_POST['new_role'])) {
    update_role($_POST['user_id'], $_POST['new_role']);
}
elseif (isset($_POST['switch_user_status'])) {
    switch_user_activation_status($_POST['user_id']);
}




$users = base_query("SELECT * FROM User")->fetchAll();


?>
<table>
    <thead>
        <tr>
            <td>Gebruikersnaam</td>
            <td>Email</td>
            <td>Rol</td>
            <td>Verwijder</td>
            <td>Details</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) {
            $disabled = $user['Id'] == $_SESSION['UserId'] ? 'disabled' : '';
            ?>
            <tr>
                <td><?= $user['Firstname'] ?></td>
                <td><?= $user['Email'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="user_id" value="<?= $user['Id'] ?>" />
                        <select <?= $disabled ?> name="new_role"onchange="this.form.submit()">
                                <?= createOptionsFor($user); ?>
                        </select>
                    </form>
                </td>
                <td>
                    <?php $activated = $user['Activated'] == 0 ? 'Activeer' : 'Deactiveer'?>
                    <?php $activatedVerb = $user['Activated'] == 0 ? 'activeren' : 'deactiveren'?>
                    <form method="POST">
                        <input type="hidden" name="user_id" value="<?= $user['Id'] ?>" />
                        <input type="hidden" name="switch_user_status" value="<?= $user['Id'] ?>" />
                        <input <?= $disabled ?> type="submit" value="<?= $activated ?>" onclick="requestUserStatusChange(event, '<?= $user['Email'] ?>', '<?= $activatedVerb ?>')" />
                    </form>
                </td>
                <td>
                    <button onclick="location.search = '?p=userdetails&userId=<?= $user['Id'] ?>'">Details</button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    function requestUserStatusChange(event, email, newStatus) {
        event.preventDefault();
        if (!confirm("Weet u zeker dat u het account met email " + email + " wilt " + newStatus + "?")) {
            return;
        }
        event.target.form.submit();
    }
</script>
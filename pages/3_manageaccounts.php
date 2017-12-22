<?php

// Creates a href for the provided page number
function createPaginationLinkFor($page) {
    $query = $_GET;
    $query['page'] = $page;
    return '?' . http_build_query($query);
}

// Generates the option tags for all the roles. 
// Automatically selects the role which the provided user is part of.
function createOptionsFor($user) {
    $res = "";
    foreach (getAllRoles() as $id => $role) {
        $selected = ($user['Role'] == $id) ? 'selected="selected"' : '';
        $res .= "<option value='$id' $selected>$role</option>\r\n";
    }
    return $res;
}

// Updates the role of the provided user.
function update_role($userId, $role) {
    base_query("UPDATE User SET Role = :role WHERE Id = :id", [':role' => $role, ':id' => $userId]);
}

// Switches if the provided user is activated or not.
function switch_user_activation_status($userId) {
    base_query("UPDATE User SET Activated = NOT Activated WHERE Id = :id", [':id' => $userId]);
}

if (isset($_POST['new_role'])) {
    // Change the role of the specified user.
    update_role($_POST['user_id'], $_POST['new_role']);
}
elseif (isset($_POST['switch_user_status'])) {
    // Activate or deactivate the user.
    switch_user_activation_status($_POST['user_id']);
}

// Used for pagination
$pageSize = 15;
$currentPage = isset($_GET['page']) ? $_GET['page'] : 0;
$currentPage = filter_var($currentPage, FILTER_VALIDATE_INT) ? $currentPage : 0;
$currentRow = $currentPage * $pageSize;
$amountRows = base_query("SELECT COUNT(Id) FROM User")->fetchColumn();
$amountPages = ceil($amountRows / $pageSize);

// Get all the users but only the users from the specified page.
$users = base_query("SELECT * FROM User LIMIT $currentRow, $pageSize")->fetchAll();


?>

<style>
    .table td:nth-child(4) input {
        width:98px;
    }
</style>
<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>Gebruikersnaam</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Verwijder</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) {
            $disabled = $user['Id'] == $_SESSION['UserId'] ? 'disabled' : '';
            ?>
            <tr>
                <td><?= htmlentities($user['Firstname']) ?></td>
                <td><?= htmlentities($user['Email']) ?></td>
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
                        <input class="btn btn-secondary" <?= $disabled ?> type="submit" value="<?= $activated ?>" onclick="requestUserStatusChange(event, '<?= $user['Email'] ?>', '<?= $activatedVerb ?>')" />
                    </form>
                </td>
                <td>
                    <button class="btn btn-secondary" onclick="location.search = '?p=userdetails&userId=<?= $user['Id'] ?>'">Details</button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</div>

<div style="display: flex;justify-content: center;">
    <ul class="pagination">
        <li class="page-item <?= $currentPage <= 0 ? 'disabled' : '' ?>"><a class="page-link" href="<?= createPaginationLinkFor($currentPage - 1) ?>">Vorige</a></li>
        <?php for ($i = 0; $i < $amountPages; $i++) { ?>
            <li class="page-item <?= $i == $currentPage ? 'disabled' : '' ?>"><a class="page-link" href="<?= createPaginationLinkFor($i) ?>"><?= $i + 1 ?></a></li>
            
            <?php } ?>
        <li class="page-item <?= ($currentPage == $amountPages - 1) ? 'disabled' : '' ?>"><a class="page-link" href="<?= createPaginationLinkFor($currentPage + 1) ?>">Volgende</a></li>
    </ul> 
</div>
<script>
    function requestUserStatusChange(event, email, newStatus) {
        event.preventDefault();
        if (!confirm("Weet u zeker dat u het account met email " + email + " wilt " + newStatus + "?")) {
            return;
        }
        event.target.form.submit();
    }
</script>
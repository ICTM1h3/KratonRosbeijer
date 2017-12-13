<?php
setTitle("Beheer vacatures");

// If requested, remove the provided vacancies
if (isset($_POST['deleteVacancies']) && isset($_POST['vacanciesToDelete'])) {
    foreach ($_POST['vacanciesToDelete'] as $vacancyId) {
        base_query("DELETE FROM Requirement WHERE Vacancy = :vacancyId", [':vacancyId' => $vacancyId]);
        base_query("DELETE FROM Vacancy WHERE Id = :vacancyId", [':vacancyId' => $vacancyId]);
    }
}

// Retrieve all current vacancies
$vacancies = base_query("SELECT * FROM vacancy")->fetchAll();

// Boolean. true when the user is trying to delete vacancies, false otherwise.
$inDeleteMode = isset($_GET['deleteMode']) ? ($_GET['deleteMode'] == 'true') : false;
?>
<style>
    .vacancy_container {
        display:flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .vacancy_container > div {
        width:49%;
        padding:2px;
        overflow-wrap: break-word;
    }

    .vacancy_container > div:nth-child(n+3) > div:first-child {
        width:100%;
        border-bottom: solid black 1px;
    }

    .vacancy_container > div:nth-child(n+3) > div:first-child > span {
        font-weight:bold;
    }

    .vacancy_container > div:nth-child(n+3) > div:first-child > a, .vacancy_container > div:nth-child(n+3) > div:first-child > input {
        font-style: italic;
        float:right;
    }

    .vacancy_container > div:first-child, .vacancy_container > div:nth-child(2)  {
        text-align:center;
    }

    .vacancy_container > div:first-child div, .vacancy_container > div:nth-child(2)  div {
        border: 1px solid black;
        border-radius: 50%;
        width: 100px;
        height: 100px;
        margin-left: auto;
        margin-right: auto;
        font-size: 100px;
        line-height: 100px;
    }

    .vacancy_button a {
        color: inherit;
        text-decoration: none;
    }

    .delete_button {
        width:100%;
    }
</style>

<form method="POST">
    <div class="vacancy_container">
        <div class="vacancy_button">
            <a href="?p=editvacancy">
                Vacature aanmaken
                <div>+</div>
            </a>
        </div>
        <div class="vacancy_button">
            <?php if ($inDeleteMode) { ?>
                <a href="?p=managevacancies">
                    Terug
                    <div>↩</div>
                </a>
                <?php } else { ?>
                <a href="?p=managevacancies&deleteMode=true">
                    Vacature sluiten
                    <div>─</div>
                </a>
            <?php } ?>
        </div>
    <?php
        // Loop through each vacancy and echo the vacancy
        foreach ($vacancies as $vacancy) {
            ?>
            <div>
                <div>
                    <span><?= $vacancy['Title'] ?></span>
                    <?php if ($inDeleteMode) {
                        ?>
                        <input type="checkbox" value="<?= $vacancy['Id'] ?>" name="vacanciesToDelete[]" />
                        <?php
                    }
                    else {
                        ?>
                        <a href="?p=editvacancy&vacancy=<?=  $vacancy['Id'] ?>">Wijzig</a>
                        <?php
                    }
                    ?>
                </div>
                <div><?= $vacancy['Description'] ?></div>
            </div>
        <?php }
    ?>
    </div>

    <?php if ($inDeleteMode) { 
        // Show a delete button if we're in delete mode 
        ?><input class="delete_button" type="submit" name="deleteVacancies" value="Verwijder geselecteerde vacatures"/><?php
    } ?>
</form>
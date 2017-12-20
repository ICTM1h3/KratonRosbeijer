<?php
//Set title
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

<!-- Style of the page-->
<style>
    .vacancy_button{
    border-style: solid;
    color: black;
    padding: 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer; 
    border-radius: 50%; 
    width: 66%;
    }

    .vacancy_button:hover{
        background-color: #E8E7ED;
    }

    a {
        color: inherit; /* blue colors for links too */
        text-decoration: inherit; /* no underline */
    }

    a:hover{
        color: inherit; /* blue colors for links too */
        text-decoration: inherit; /* no underline */
    }

    .option_table{
        width: 100%;
        text-align: center;
    }
</style>

<h2>Beheren vacatures</h2>

<form method="POST">
    <table class="option_table">
        <tr>
            <td> 
            <div class="vacancy_button">
                <a href="?p=editvacancy">
                    Vacature aanmaken
                </a>
            </div>
            </td>   
            <td> 
            <div class="vacancy_button">
                <a href="?p=3_edit_vacancy_text">
                    Wijzig vacature tekst
                </a>
                </div>
            </td>
            <td> 
            <div class="vacancy_button">
            <?php if ($inDeleteMode) { ?>
                <a href="?p=admin_managevacancies">
                    Terug naar overzicht
                </a>
                <?php } else { ?>
                <a href="?p=managevacancies&deleteMode=true">
                    Vacature sluiten
                </a>
            <?php } ?>
                </div>
            </td>
        </tr>
</table>

<table>
    <?php
        // Loop through each vacancy and echo the vacancy
        foreach ($vacancies as $vacancy) {
            ?>
                <tr>
                    <td>Titel: <span><?= $vacancy['Title'] ?></span></td>
                <td>
                    <?php if ($inDeleteMode) {?>
                            <input type="checkbox" value="<?= $vacancy['Id'] ?>" name="vacanciesToDelete[]"/>

                        
                    <?php } else { ?>
                        <a href="?p=admin_editvacancy&vacancy=<?=  $vacancy['Id'] ?>">Wijzig</a>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td>Beschrijving: <?= $vacancy['Description'] ?></td>
            </tr>
    <?php } ?>            
    <?php if ($inDeleteMode && !empty($vacancies)) { 
        // Show a delete button if we're in delete mode 
        ?><tr><td><input class="delete_button" type="submit" name="deleteVacancies" value="Verwijder geselecteerde vacatures"/></td></tr><?php
    }elseif($inDeleteMode && empty($vacancies)){
        ?><tr><td>Er zijn geen vacatures om te verwijderen!</td></tr>
    <?php } ?>
    </tr>
</table>
</form>

<h3>Vacature tekst</h3>

<?php
$VacancyInfo = base_query('SELECT Value FROM setting Where Name = "VacancyInfo"')->fetchColumn();

echo $VacancyInfo;
?>


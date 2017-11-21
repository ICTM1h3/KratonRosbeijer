<?php

// Retrieve all current vacancies
$vacancies = base_query("SELECT * FROM vacancy")->fetchAll();
//var_dump($vacancies);

?>
<style>
    .vacancy_container {
        display:flex;
        justify-content: space-between;
        flex-wrap: wrap;
        #display:grid;
        #grid-template-columns: repeat(2, 1fr);
        #grid-gap: 10px;
        #grid-auto-rows: minmax(100px, auto);
    }

    .vacancy_container > div {
        width:49%;
        padding:2px;
    }

    .vacancy_container > div:nth-child(n+3) > div:first-child {
        width:100%;
        border-bottom: solid black 1px;
    }

    .vacancy_container > div:nth-child(n+3) > div:first-child > span {
        font-weight:bold;
    }

    .vacancy_container > div:nth-child(n+3) > div:first-child > a {
        font-style: italic;
        float:right;
    }

    .vacancy_container > div:first-child, .vacancy_container > div:nth-child(2)  {
        text-align:center;
    }

    .vacancy_container > div:first-child > div, .vacancy_container > div:nth-child(2) > div {
        border: 1px solid black;
        border-radius: 50%;
        width: 100px;
        height: 100px;
        margin-left: auto;
        margin-right: auto;
        font-size: 100px;
        line-height: 100px;
    }
</style>

<div class="vacancy_container">
    <div>
        Vacature aanmaken
        <div>+</div>
    </div>
    <div>
        Vacature sluiten
        <div>─</div>
    </div>
<?php
    // Loop through each vacancy and echo the vacancy
    foreach ($vacancies as $vacancy) {
        ?>
        <div>
            <div><span><?= $vacancy['Title'] ?></span><a href="?p=admin_editvacancy&vacancy=<?=  $vacancy['Id'] ?>">Wijzig</a></div>
            <div><?= $vacancy['Description'] ?></div>
        </div>
    <?php }
?>
</div>
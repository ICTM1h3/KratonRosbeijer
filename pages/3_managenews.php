<?php

if (isset($_POST['remove'])) {
    base_query("DELETE FROM News WHERE Id = :id", [':id' => $_POST['newsId']]);
}
// Get all the news items in the database with the newest first.
$news = base_query("SELECT * FROM news ORDER BY Id DESC");
?>

<h2>Beheer nieuws</h2>
<a href="?p=editnews">Nieuw nieuwtje</a>

<style>
    table tr:first-child td:last-child a {
        float:right;
    }
</style>

<? // Loop through every news item and echo a table containing the title, date, contant and an edit button. ?>
<table class="table">
    <?php foreach ($news as $newsItem) { ?>
        <tr style="height:10px;"></tr>
        <tr>
            <td>
                <b style="float:left"><?= $newsItem['Title'] ?></b>
                <span style="float:right"><?= $newsItem['Date'] ?></span>
            </td>
            <td>
                <input class="btn btn-secondary" type="submit" style="float:right;" onclick="location.search = '?p=editnews&newsId=<?= $newsItem['Id'] ?>'" value="Pas aan" />
                <form method="POST" style="float:right;">
                    <input type="hidden" name="newsId" value="<?= $newsItem['Id'] ?>" />
                    <input class="btn btn-secondary" style="margin-right: 2px;" type="submit" name="remove" value="Verwijder" />
                </form>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?= $newsItem['Content'] ?>
            </td>
        </tr>
    <?php } ?>
</table>
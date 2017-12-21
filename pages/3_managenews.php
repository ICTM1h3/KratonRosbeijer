<?php
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

<?php foreach ($news as $newsItem) { ?>
    <table class="table">
        <tr>
            <td>
                <b style="float:left"><?= $newsItem['Title'] ?></b>
                <span style="float:right"><?= $newsItem['Date'] ?></span>
            </td>
            <td>
                <a href="?p=editnews&newsId=<?= $newsItem['Id'] ?>">Edit</a>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?= $newsItem['Content'] ?>
            </td>
        </tr>
    </table>
<?php } ?>
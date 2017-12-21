<?php

$news = base_query("SELECT * FROM news ORDER BY Id DESC");

?>

<h2>Nieuws</h2>

<? // Loop through every news item and echo a table containing the title, date and content ?>
<?php foreach ($news as $newsItem) { ?>
    <table class="table">
        <tr>
            <td>
                <b style="float:left"><?= $newsItem['Title'] ?></b>
                <span style="float:right"><?= $newsItem['Date'] ?></span>
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="1">
                <?= $newsItem['Content'] ?>
            </td>
        </tr>
    </table>
<?php } ?>
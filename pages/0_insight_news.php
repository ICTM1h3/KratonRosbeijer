<?php
// function to set the tilte page
setTitle('Menukaart inzien');
$news = base_query("SELECT * FROM news ORDER BY Id DESC")->fetchAll();

?>

<h2>Nieuws</h2>

<? // Loop through every news item and echo a table containing the title, date and content ?>
<?php if (empty($news)) { ?>
    <p>Er zijn geen nieuwtjes</p>
<?php } else { ?>
    <table class="table">
    <?php foreach ($news as $newsItem) { ?>
            <tr style="height:10px;"></tr>
            <tr>
                <td>
                    <b style="float:left"><?= $newsItem['Title'] ?></b>
                    <span style="float:right"><?= $newsItem['Date'] ?></span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">
                    <?= $newsItem['Content'] ?>
                </td>
            </tr>
    <?php } ?>
    </table>
<?php } ?>
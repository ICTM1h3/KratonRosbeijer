<?php
setTitle("Aanpassen nieuws");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // If the news id is provided update the existing id, otherwise insert a new item.
    if (isset($_GET['newsId'])) {
        // Update the existing news item
        base_query("UPDATE News 
        SET Content = :content,
            Title = :title,
            Date = :date
        WHERE Id = :id", [
                ':content' => $_POST['content'],
                ':title' => $_POST['title'],
                ':date' => $_POST['date'] . ' ' . $_POST['time'],
                ':id' => $_GET['newsId'],
        ]);
    }
    else {
        // Insert a new news article
        base_query("INSERT INTO News (Title, Content, Date) 
                    VALUES (:title, :content, :date)", [
            ':content' => $_POST['content'],
            ':title' => $_POST['title'],
            ':date' => $_POST['date'] . ' ' . $_POST['time'],
        ]);
    }
}

// If the newsid is provided get the existing values. 
// Otherwise set it to a default to prevent errors.
if (isset($_GET['newsId'])) {
    // Get the requested news id
    $newsItem = base_query("SELECT * FROM news WHERE Id = :id", [
        ':id' => $_GET['newsId']
    ])->fetch();
    
    // The datetime type in mysql returns a string with the date and time separated by a space.
    $dateSplit = explode(' ', $newsItem['Date']);
    $date = $dateSplit[0];
    $time = $dateSplit[1];
    $title = $newsItem['Title'];
    $content = $newsItem['Content'];
}
else {
    // Set default values
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $content = '';
    $title = '';
}
?>

<table class="table">
    <tr>
        <td>
            <input name="title" class="form-control" style="float:left" value="<?= $title ?>">
        </td>
        <td>
            <input type="time" name="time" style="float:right" value="<?= $time ?>">
            <input type="date" name="date" style="float:right" value="<?= $date ?>">
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <textarea class="tinymce" id="texteditor" name="content"><?= $content ?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <button class="btn btn-secondary" style="width:100%;" onclick="submit()">Opslaan</button>
        </td>
    </tr>
</table>

<script>
function submit() {
    // Create a javascript object (similar to PHP arrays with custom keys)
    var obj = {};
    
    // Loop through all HTML elements with an attribute that has a name and set them in the javascript object.
    [].map.call(document.querySelectorAll("[name]"), function(item) {
        obj[item.name] = item.value;
    });
    // Override the content property with the values from the editor
    obj.content = tinyMCE.activeEditor.getContent();
    
    // Send the object to the server to change the database.
    $.ajax({
        url: location.search,
        method: "POST",
        data: obj
    }).done(function() {
        // If successfull redirect to the news management page.
        location.search = "?p=managenews";
    });
}
</script>

<!-- Loading javascript for the edit tool -->
<script type="text/javascript" src="editplugin/js/jquery.min.js"></script>
<script type="text/javascript" src="editplugin/plugin/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="editplugin/plugin/tinymce/js/tinymce/init-tinymce.js"></script>
<script type="text/javascript" src="editplugin/js/getdata.js"></script>
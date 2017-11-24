<?php
//Title with an if statement
?>
<style>
    textarea {
        resize: none;
    }
</style>
<form method="POST">
<table>
    <th>Set this to the title of the page</th>
    <tr>
        <td>Categorie</td>
        <td>
            <select name="category">
                <option>Selecteer de categorie</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Naam</td>
        <td>
            <input type="text" name="name" value="">
        </td>
    </tr>
    <tr>
        <td>Omschrijving</td>
        <td>
            <textarea value="description"></textarea>
        </td>
    </tr>
    <tr>
        <td>Prijs (&#8364;)</td>
        <td>
            <input type="number" step="0.01" value="">
        </td>
    </tr>
    <tr>
        <td>
            Foto invoegen
        </td>
        <td>
            <input type="text" name="image">
        </td>
    </tr>
    <tr>
        <td>
            <button value="save_dish">Gerecht opslaan</button
        </td>
    </tr>
</table>
</form>
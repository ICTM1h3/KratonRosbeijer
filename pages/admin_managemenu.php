<?php
setTitle("Beheren menu");
?>

<style>
    .menu_container {
        display:flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .menu_container > div {
        width:49%;
        padding:2px;
        overflow-wrap: break-word;
    }

    .menu_container > div:nth-child(n+3) > div:first-child {
        width:100%;
        border-bottom: solid black 1px;
    }

    .menu_container > div:nth-child(n+3) > div:first-child > span {
        font-weight:bold;
    }

    .menu_container > div:nth-child(n+3) > div:first-child > a, .menu_container > div:nth-child(n+3) > div:first-child > input {
        font-style: italic;
        float:right;
    }

    .menu_container > div:first-child, .menu_container > div:nth-child(2)  {
        text-align:center;
    }

    .menu_container > div:first-child div, .menu_container > div:nth-child(2)  div {
        border: 1px solid black;
        border-radius: 50%;
        width: 100px;
        height: 100px;
        margin-left: auto;
        margin-right: auto;
        font-size: 100px;
        line-height: 100px;
    }

    .menu_button a {
        color: inherit;
        text-decoration: none;
    }
</style>


<form method="POST">


<div class="menu_container">
    <div class="menu_button">
        <a href="?p=admin_editdish">
            Gerechten toevoegen
        <div>+</div>
        </a>
    </div>

    <div class="menu_button">
        <a href="?p=admin_editcategory">
            Categorie toevoegen
        <div>+</div>
        </a>


    
<table>
    <th>Menu</th>
    <tr>
        <td>
        <?php
            $category= base_query("SELECT * FROM dishcategory")->fetchAll(); 
            var_dump ($category);
        ?>
        </td>
    </tr>
    </table>

</form>
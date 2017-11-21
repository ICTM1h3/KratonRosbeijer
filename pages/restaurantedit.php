<?php
setTitle("Beheren restaurant informatie");
?>

<h1>Beheren restaurant informatie</h1>

Op deze pagina kan de informatie van de informatie pagina worden aangepast. Dit kan door middel van de edit plugin.  



        <form id="get-data-form" method="post">

			<textarea class="tinymce" id="texteditor">DE TEKST</textarea>
			<input type="submit" value="Get Data">

		</form>

		<div id="data-container">
		</div>

		<!-- javascript -->
		<script type="text/javascript" src="editplugin/js/jquery.min.js"></script>
		<script type="text/javascript" src="editplugin/plugin/tinymce/js/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="editplugin/plugin/tinymce/js/tinymce/init-tinymce.js"></script>
        <script type="text/javascript" src="editplugin/js/getdata.js"></script>
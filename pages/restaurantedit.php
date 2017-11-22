<?php
setTitle("Beheren restaurant informatie");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$text = file_get_contents('php://input');
	base_query("UPDATE setting SET value = :value WHERE name = 'Info'", array(':value' => $text));
}
?>

Beheren restaurant informatie

Op deze pagina kan de informatie van de informatie pagina worden aangepast. Dit kan door middel van de edit plugin.  


		<!--Making form for the edit-plugin-->


			<textarea class="tinymce" id="texteditor">

			<!-- Loading the info page into the editor -->
			<?php $info = base_query("SELECT * FROM setting WHERE name = 'Info' ")->fetch();
      		echo($info['Value']);
   			 //used the function base_query to execute a query and echo's the result 
 			?> 

			</textarea>
			<input onclick="submit()" type="submit" value="Get Data">

			<script>

			//Submit the data
			function submit(){

				$.ajax({
					url: '?p=restaurantedit', // Url witch deals the request
					data: tinyMCE.activeEditor.getContent(),
					method: "POST"
				});
				document.getElementById("data-container").innerHTML=tinyMCE.activeEditor.getContent();
			}
			</script>

		<!-- Put the data from Get Data here, temporarily-->
		<div id="data-container">
		</div>

		<!-- Loading javascript for the edit tool -->
		<script type="text/javascript" src="editplugin/js/jquery.min.js"></script>
		<script type="text/javascript" src="editplugin/plugin/tinymce/js/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="editplugin/plugin/tinymce/js/tinymce/init-tinymce.js"></script>
        <script type="text/javascript" src="editplugin/js/getdata.js"></script>
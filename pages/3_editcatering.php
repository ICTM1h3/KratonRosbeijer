<?php

// set the correct title name.
setTitle("Beheren Catering Pagina");

//get the correct info from the database
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$info = file_get_contents('php://input');
	base_query("UPDATE setting SET value = :value WHERE name = 'CateringText'", array(':value' => $info));
}
?>


<h6>Beheren Catering Pagina.</h6>

<p>Op deze pagina kan de informatie van de Catering Pagina worden aangepast. Dit kan door middel van de edit plugin.</P>


		<!--Making form for the edit-plugin-->

		
			<!-- Loading the info page into the editor -->
			<textarea class="tinymce" id="texteditor">

			<?php $text = base_query("SELECT * FROM setting WHERE name = 'CateringText' ")->fetch();
      			echo($text['Value']);
   			//used the function base_query to execute a query and echo's the result 
 			?> 

			</textarea>

			<!-- Submit data by a submit button-->
			<input class="btn btn-secondary" style="width:100%;" onclick="submit()" type="submit" value="Opslaan">

			<script>
			//Submit the data
			function submit(){

				$.ajax({
					url: '?p=editcatering', // Url witch deals the request
					data: tinyMCE.activeEditor.getContent(),
					method: "POST"
				}).done(function(){
					location.search="?p=cateringpage"
				});
			}
			</script>

		<!-- Loading javascript for the edit tool -->
		<script type="text/javascript" src="editplugin/js/jquery.min.js"></script>
		<script type="text/javascript" src="editplugin/plugin/tinymce/js/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="editplugin/plugin/tinymce/js/tinymce/init-tinymce.js"></script>
		<script type="text/javascript" src="editplugin/js/getdata.js"></script>
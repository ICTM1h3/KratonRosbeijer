<?php
//Set title
setTitle("Aanpassen vacature text");

//Update the text of the vacancy when requested. 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$text = file_get_contents('php://input');
	base_query("UPDATE setting SET value = :value WHERE name = 'VacancyInfo'", array(':value' => $text));
}
?>


<!-- Option to go back to manage vacancies.-->
<a href="?p=admin_managevacancies"><button>Ga terug naar het overzicht van de vacatures</button></a>

<p>Beheren vacature informatie. Wijzig hieronder de vacature informatie.</p>

			<!-- Loading the vacancy info into the editor -->
			<textarea class="tinymce" id="texteditor">

			<?php $info = base_query("SELECT * FROM setting WHERE name = 'VacancyInfo' ")->fetch();
      			echo($info['Value']);
   			 	//used the function base_query to execute a query and echo's the result 
 			?> 

			</textarea>

			<!-- Submit data by a submit button-->
			<input onclick="submit()" type="submit" value="Opslaan">

			<script>

			//Submit the data
			function submit(){

				$.ajax({
					url: '?p=3_edit_vacancy_text', // Url witch deals the request
					data: tinyMCE.activeEditor.getContent(),
					method: "POST"
				}).done(function() {
					location.search="?p=admin_managevacancies"
				});
				
			}
			</script>


		<!-- Loading javascript for the edit tool -->
		<script type="text/javascript" src="editplugin/js/jquery.min.js"></script>
		<script type="text/javascript" src="editplugin/plugin/tinymce/js/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="editplugin/plugin/tinymce/js/tinymce/init-tinymce.js"></script>
        <script type="text/javascript" src="editplugin/js/getdata.js"></script>
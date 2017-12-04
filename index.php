<?php
session_start();
// Load the file which handles loading the actual webpage.
include 'rendering.php';
include 'database.php';
include 'email.php';
include 'validators.php';
//Set default title and setup body
$pagetitle = "Kraton Rosbeijer";
$body = renderPage();
?>

<!-- PAGE SETUP -->

<!DOCTYPE html>
<html>
<head>
<!-- css files, etc -->
<!-- Bootstrap connection link -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<link rel="stylesheet" href="css-style-sheet.css" type="text/css">
<title><?php echo htmlentities($pagetitle); ?> </title>
</head>
	<body>
		<ul>
			<li><a href="?p=admin_managevacancies">Beheer vacatures</a></li>
			<li><a href="?p=insight_vacancies">Inzien vacatures</a></li>
			<li><a href="?p=infopage">Restaurant info pagina</a></li>
			<li><a href="?p=restaurantedit">Verander restaurant info</a></li>
			<li><a href="?p=inlogpage">Login</a></li>
		</ul>
	<?php
	
	echo $body;
	
	?>

	</body>

</html>
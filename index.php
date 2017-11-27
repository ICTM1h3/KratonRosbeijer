<?php
// Load the file which handles loading the actual webpage.
include 'rendering.php';
include 'database.php';
//Set default title and setup body
$pagetitle = "Kraton Rosbeijer";
$body = renderPage();
?>

<!-- PAGE SETUP -->

<!DOCTYPE html>
<html>
<head>
<!-- css files, etc -->
<title><?php echo htmlentities($pagetitle); ?> </title>
</head>

	<body>
		<ul>
			<li><a href="?p=insight_vacancies">Inzien vacatures</a></li>
			<li><a href="?p=admin_managevacancies">Beheer vacatures</a></li>
			<li><a href="?p=infopage">Restaurant info pagina</a></li>
			<li><a href="?p=restaurantedit">Verander restaurant info</a></li>
			<li><a href="?p=">Menu inzien</a></li>
			<li><a href="?p=admin_managemenu">Beheer het menu</a></li>
		</ul>
	<?php
	
	echo $body;
	
	?>

	</body>

</html>
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
		
	<?php
	
	echo $body;
	
	?>

	</body>

</html>
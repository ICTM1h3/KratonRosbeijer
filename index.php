<?php
// Load the file which handles loading the actual webpage.
include 'rendering.php';
$body = renderPage();
?>

<!DOCTYPE html>
<html>
<head>
<!-- css files, etc -->
<title><?php echo $pagetitle ?> </title>
</head>

	<body>
		
		<?php
		echo  $body;
		?>

	</body>
</html>

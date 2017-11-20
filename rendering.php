<?php
function renderPage() {
	$page = "index";
	if (isset($_GET['p'])) {
		$page = $_GET['p'];
	}
	
	$path = 'pages/' . $page . '.php';
	if (!file_exists($path)) {
		$path = 'pages/index.php';
	}
	
	executePage($path);
}

?>
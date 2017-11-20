<?php
//Function looks if page excists and put it in the index.php
function renderPage() {
	$page = "index";
	if (isset($_GET['p'])) {
		$page = $_GET['p'];
	}
    
    //Return to homepage.php if there is no page found.
	$path = 'pages/' . $page . '.php';
	if (!file_exists($path)) {
		$path = 'pages/homepage.php';
    }
    
	ob_start();
	include($path);
    return ob_get_clean();
}

?>
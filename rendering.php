<?php

function setTitle($title) {
	global $pagetitle;
	$pagetitle = $title;
}

//Function looks if page excists and put it in the index.php
function renderPage() {
	$page = "index";
	if (isset($_GET['p'])) {
		$page = $_GET['p'];
	}
	
	//Check for the pad
	if (!preg_match('/[^A-Za-z0-9]/', $page)){
	  // string contains only english letters & digits
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
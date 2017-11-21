<?php

// Changes the title of the page.
function setTitle($title)
{
	// Grab the $pagetitle variable.
	global $pagetitle;

	// Change the title to the newly provided title.
	$pagetitle = $title;
}

//Function looks if page excists and put it in the index.php
function renderPage()
{
	$page = "index";
	if (isset($_GET['p'])) {
		$page = $_GET['p'];
	}
	
	// Check if the path doesn't contain any malicious path
	if (!preg_match('/[^A-Za-z0-9]/', $page)) {
	  // string contains only english letters & digits
	}

    // Return to homepage.php if there is no page found.
	$path = 'pages/' . $page . '.php';
	if (!file_exists($path)) {
		$path = 'pages/homepage.php';
	}
	
	// Capture all things that are echo'ed from now on. We'll get it later on as a string.
	ob_start();

	// Load the page. Everything that would be echo'ed is captured
	include($path);

	// Return the output of the page.
	return ob_get_clean();
}

?>
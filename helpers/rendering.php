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
	if (preg_match('/[^A-Za-z0-9_]/', $page)) {
		return "Certain characters were used which are not allowed";
	}

	// Find the file for the requested page.
	$path = getPathForPage($page);
	
	// Capture all things that are echo'ed from now on. We'll get it later on as a string.
	ob_start();
	
	// Load the page. Everything that would be echo'ed is captured
	include($path);
	
	// Return the output of the page.
	return ob_get_clean();
}


// Searches inside the pages folder for the requested page.
// Looks if the current user has enough permissions
function getPathForPage($page) {
	$files = glob("pages/*_$page.php");
	if (empty($files)) {
		// Return to a 404 page if there is no page found.
		return 'pages/errors/404.php';
	}

	$currentRole = getCurrentRole();

	// Loop through all the files until we find a match.
	foreach ($files as $file) {
		$matches = [];
		preg_match("/pages\/(\d)(?:_(\d))?_/", $file, $matches);
		$minRole = $matches[1];

		// If the max role is not defined use the administrator role instead.
		$maxRole = isset($matches[2]) ? $matches[2] : MAX_ROLE_NUMBER;
		if ($currentRole >= $minRole && $currentRole <= $maxRole) {
			return $file;
		}
	}

	// Return an unauthorized message if the user is not logged in. 
	// Otherwise return a 403 - forbidden.
	if ($currentRole == ROLE_VISITOR) {
		return 'pages/errors/401.php';
	}
	return 'pages/errors/403.php';
}

?>
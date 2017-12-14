<?php

// Create constants for each role.
define("ROLE_VISITOR", 0);
define("ROLE_USER", 1);
define("ROLE_VIP_USER", 2);
define("ROLE_ADMINISTRATOR", 3);
define("MAX_ROLE_NUMBER", ROLE_ADMINISTRATOR);

function getAllRoles() {
	return [
		ROLE_VISITOR => "Bezoeker",
		ROLE_USER => "Gebruiker",
		ROLE_VIP_USER => "VIP Gebruiker",
		ROLE_ADMINISTRATOR => "Administrator",
	];
}


// If the user is not logged in it returns the visitor role.
function getCurrentRole() {
	if (!isset($_SESSION['UserId'])) {
		return ROLE_VISITOR;
	}

	// Get the user role from the user.
	$role = base_query("SELECT Role FROM User WHERE Id = :id", [':id' => $_SESSION['UserId']])->fetchColumn();
	return $role;
}
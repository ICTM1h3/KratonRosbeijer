<?php

$tabs = [
    ROLE_VISITOR => [
        ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
        ['title' => 'Menu', 'href' => '?p=insight_menu'],
        ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],
        ['title' => 'Registreren', 'href' => '?p=register'],
        ['title' => 'Inloggen', 'href' => '?p=inlogpage'],
        ['title' => 'Cadeaubon', 'href' => '?p=cadeaubon'],
    ],
    
    ROLE_USER => [
        ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
        ['title' => 'Menu', 'href' => '?p=insight_menu'],
        ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],
        // ['title' => 'Registreren', 'href' => '?p=register'],
        // ['title' => 'Inloggen', 'href' => '?p=inlogpage'],
        ['title' => 'Cadeaubon', 'href' => '?p=cadeaubon'],
        ['title'=> 'Gegevens bewerken', 'href' => '?p=changedetails'],
    ],

    ROLE_VIP_USER => [
        ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
        ['title' => 'Menu', 'href' => '?p=insight_menu'],
        ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],
        // ['title' => 'Registreren', 'href' => '?p=register'],
        // ['title' => 'Inloggen', 'href' => '?p=inlogpage'],
        ['title' => 'Cadeaubon', 'href' => '?p=cadeaubon'],
        ['title'=> 'Gegevens bewerken', 'href' => '?p=changedetails'],
    ],
    
    ROLE_ADMINISTRATOR => [
        // ['title' => 'Registreren', 'href' => '?p=register'],
        // ['title' => 'Inloggen', 'href' => '?p=inlogpage'],
        ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
        ['title' => 'Menu', 'href' => '?p=insight_menu'],
        ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],
        ['title' => 'Beheer menu', 'href' => '?p=managemenu'],
        ['title' => 'Beheer vacatures', 'href' => '?p=managevacancies'],
        ['title' => 'Beheer restaurant info', 'href' => '?p=restaurantedit'],
        ['title' => 'Beheer gebruikers', 'href' => '?p=manageaccounts'],
        ['title' => 'Beheer reserveringen', 'href' => '?p=managereservation'],
        ['title' => 'Beheer cadeaubonnen', 'href' => '?p=manage_giftcard'],
        ['title' => 'Beheer tafels', 'href' => '?p=managetables'],
        ['title'=> 'Gegevens bewerken', 'href' => '?p=changedetails'],
    ]
];

// Quick fix: Users and VIP Users get the same tabs as the visitor.
// $tabs[ROLE_VIP_USER] = $tabs[ROLE_USER] = $tabs[ROLE_VISITOR];

// Returns the tabs for the requested role.
function getTabsFor($role) {
    global $tabs;
    return $tabs[$role];
}

// Returns the tabs for the current user by looking at it's current role.
function getTabsForCurrentUser() {
    $role = getCurrentRole();
    return getTabsFor($role);
}
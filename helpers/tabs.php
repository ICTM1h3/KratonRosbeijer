<?php

$tabs = [
    ROLE_VISITOR => [
        ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
        ['title' => 'Menu', 'href' => '?p=insight_menu'],
        ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],
        // ['title' => 'Registreren', 'href' => '?p=register'],
        // ['title' => 'Inloggen', 'href' => '?p=inlogpage'],
        ['title' => 'Cadeaubon', 'href' => '?p=cadeaubon'],
    ],
    
    // ROLE_USER => [
    //     ['title' => 'Registreren', 'href' => '?p=register'],
    //     ['title' => 'Inloggen', 'href' => '?p=inlogpage'],
    //     ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
    //     ['title' => 'Menu', 'href' => '?p=insight_menu'],
    //     ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],
    // ],
    
    ROLE_ADMINISTRATOR => [
        // ['title' => 'Registreren', 'href' => '?p=register'],
        // ['title' => 'Inloggen', 'href' => '?p=inlogpage'],
        ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
        ['title' => 'Menu', 'href' => '?p=insight_menu'],
        ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],
        [
            'header' => "Beheer",
            'pages' => [
                ['title' => 'Menu', 'href' => '?p=managemenu'],
                ['title' => 'Vacatures', 'href' => '?p=managevacancies'],
                ['title' => 'Restaurant info', 'href' => '?p=restaurantedit'],
                ['title' => 'Gebruikers', 'href' => '?p=manageaccounts'],
                ['title' => 'Reserveringen', 'href' => '?p=managereservation'],
                ['title' => 'Cadeaubonnen', 'href' => '?p=manage_giftcard'],
                ['title' => 'Tafels', 'href' => '?p=managetables']
            ]
        ],
    ]
];

// Quick fix: Users and VIP Users get the same tabs as the visitor.
$tabs[ROLE_VIP_USER] = $tabs[ROLE_USER] = $tabs[ROLE_VISITOR];

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
<?php

$tabs = [
    ROLE_VISITOR => [
        ['title' => 'Nieuws', 'href' => '?p=insight_news'],
        ['title' => 'Menu', 'href' => '?p=insight_menu'],
        ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
        ['title' => 'Bestellen', 'href' => '?p=Order_menu'],
        ['title' => 'Cadeaubon', 'href' => '?p=cadeaubon'],
        ['title' => 'Catering', 'href' => '?p=cateringpage'],
        ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],

    ],
    
    ROLE_USER => [
        ['title' => 'Nieuws', 'href' => '?p=insight_news'],
        ['title' => 'Menu', 'href' => '?p=insight_menu'],
        ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
        ['title' => 'Bestellen', 'href' => '?p=Order_menu'],
        ['title' => 'Cadeaubon', 'href' => '?p=cadeaubon'],
        ['title' => 'Catering', 'href' => '?p=cateringpage'],
        ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],
        ['title' => 'Gegevens bewerken', 'href' => '?p=changedetails'],
    ],

    ROLE_VIP_USER => [
        ['title' => 'Nieuws', 'href' => '?p=insight_news'],
        ['title' => 'Menu', 'href' => '?p=insight_menu'],
        ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
        ['title' => 'Bestellen', 'href' => '?p=Order_menu'],
        ['title' => 'Cadeaubon', 'href' => '?p=cadeaubon'],
        ['title' => 'Catering', 'href' => '?p=cateringpage'],
        ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],
        ['title' => 'Gegevens bewerken', 'href' => '?p=changedetails'],
    ],
    
    ROLE_ADMINISTRATOR => [
        ['title' => 'Nieuws', 'href' => '?p=insight_news'],
        ['title' => 'Menu', 'href' => '?p=insight_menu'],
        ['title' => 'Reserveer tafel', 'href' => '?p=reservetable'],
        ['title' => 'Bestellen', 'href' => '?p=Order_menu'],
        ['title' => 'Cadeaubon', 'href' => '?p=cadeaubon'],
        ['title' => 'Catering', 'href' => '?p=cateringpage'],
        ['title' => 'Vacatures', 'href' => '?p=insight_vacancies'],
        ['title' => 'Gegevens bewerken', 'href' => '?p=changedetails'],
        [
            'header' => "Beheer",
            'pages' => [
                ['title' => 'Menu', 'href' => '?p=managemenu'],
                ['title' => 'Vacatures', 'href' => '?p=managevacancies'],
                ['title' => 'Restaurant info', 'href' => '?p=restaurantedit'],
                ['title' => 'Gebruikers', 'href' => '?p=manageaccounts'],
                ['title' => 'Reserveringen', 'href' => '?p=managereservation'],
                ['title' => 'Cadeaubonnen', 'href' => '?p=manage_giftcard'],
                ['title' => 'Tafels', 'href' => '?p=managetables'],
                ['title' => 'Nieuws', 'href' => '?p=managenews'],
                ['title' => 'Catering', 'href' => '?p=editcatering']
            ]
        ],
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
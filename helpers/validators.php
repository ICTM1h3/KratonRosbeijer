<?php
// Returns true if the provided variable is an email.
function is_email_valid($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


// Checks if the provided phonenumber has the correct format.
function is_valid_telephone_number($phoneNumber) {
    // Regex from https://stackoverflow.com/a/17949938
    return preg_match("/(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)/", $phoneNumber) !== 0;
}


// Checks if the provided date format is valid. (YYYY-mm-dd)
// Doesn't check if the provided date is impossible (2017-13-32)
function is_date_valid($date) {
    return preg_match("/^\d{4}-\d{2}-\d{2}$/", $date) !== 0;
}


// Checks if the provided time is valid. (HH:mm).
// Doesn't check if the values are impossible (61:61)
function is_time_valid($time) {
    return preg_match("/^\d{2}:\d{2}(?::\d{2})?$/", $time) !== 0;
}

// Combines a date and time.
function format_date_and_time($date, $time) {
    return $date . ' ' . $time;
}

// Makes sure the password is strong enough.
// A password is at least 8 characters long.
// A password must contain a letter
// A password must contain a number
// A password must contain a symbol.
function is_password_strong($password) {
    if (strlen($password) < 8) {
        return [false, "Een wachtwoord moet minimaal 8 karakters lang zijn."];
    }

    if (!preg_match("/[a-z]/i", $password)) {
        return [false, "Een wachtwoord moet minimaal één letter hebben."];
    }

    if (!preg_match("/\d/", $password)) {
        return [false, "Een wachtwoord moet minimaal één getal hebben."];
    }

    if (!preg_match("/[!@#$%^&*()_+\-=,\.<>\/\\;':\"]/", $password)) {
        return [false, "Een wachtwoord moet minimaal één symbool bevatten."];
    }
    return [true, null];
    // if (strpos($password, ))
}
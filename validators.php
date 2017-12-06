<?php
// Returns true if the provided variable is an email.
function is_email_valid($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function is_valid_telephone_number($phoneNumber) {
    // Regex from https://stackoverflow.com/a/17949938
    return preg_match("/(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)/", $phoneNumber) !== 0;
}

function is_date_valid($date) {
    return preg_match("/^\d{4}-\d{2}-\d{2}$/", $date) !== 0;
}

function is_time_valid($time) {
    return preg_match("/^\d{2}:\d{2}$/", $time) !== 0;
}

// function is_date_time_valid($date, $time) {
//     return preg_match("/^\d{4}-\d{2}-\d{2}$/", $date) && preg_match("/^\d{2}:\d{2}$/", $time);
//     // if (!preg_match("/\d{4}-\d{2}-\d{2}/", $date) || !preg_match("/\d{2}:\d{2}/", $time)) {
//     //     return false
//     // }
// }

function format_date_and_time($date, $time) {
    return $date . ' ' . $time;
}
<?php
// Sends an email to the target email based on an email template. This template gets filled with the provided parameters.
function send_email_to($to, $subject, $templateName, $parameters) {
    // From the current request generate a default parameter for the URL.
    // This is useful as the developers aren't all developing in a directory with the same path.
    $parameters['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']);

    $content = file_get_contents("email/$templateName.txt");

    // Replace everything between two braces with the provided parameters
    $content = preg_replace_callback("/\{\{(.*?)\}\}/", function($matched) use($parameters) {
        return $parameters[$matched[1]];
    }, $content);

    // Send the email to the user.
    mail($to, $subject, $content);
}
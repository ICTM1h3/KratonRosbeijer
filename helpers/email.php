<?php
// Sends an email to the target email based on an email template. This template gets filled with the provided parameters.
function send_email_to($to, $subject, $templateName, $parameters, $bcc = null) {
    // From the current request generate a default parameter for the URL.
    // This is useful as the developers aren't all developing in a directory with the same path.
    $parameters['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']);

    $content = null;
    $contentType = "text/plain";
    if (file_exists("email/$templateName.txt")) {
        $content = file_get_contents("email/$templateName.txt");
    }
    elseif (file_exists("email/$templateName.html")) {
        // Assume the requested template is an html template.
        $content = file_get_contents("email/$templateName.html");
        $contentType = "text/html";
    }
    elseif (file_exists("email/$templateName.php")) {
        $content = get_email_content("email/$templateName.php", $parameters);
        $contentType = "text/html";
    }

    // Replace everything between two {{braces}} with the provided parameters
    $content = preg_replace_callback("/\{\{(.*?)\}\}/", function($matched) use($parameters) {
        return $parameters[$matched[1]];
    }, $content);

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: $contentType; charset=ISO-8859-1\r\n";

    if ($bcc != null) {
        if (is_array($bcc)) {
            $bcc = implode(",", $bcc);
        }
        $headers .= "Bcc: $bcc\r\n";
    }

    // Send the email to the user.
    mail($to, $subject, $content, $headers);
}


// Executes a PHP file for an email. The php code can access the parameter by using the $parameters variable.
function get_email_content($path, $parameters) {
    // Start the output buffer
    ob_start();

    // Include the PHP email.
    include $path;

    // Return the email result.
    return ob_get_clean();
}
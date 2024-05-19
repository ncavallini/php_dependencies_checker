<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

const PATH = __DIR__ . "/../admin";
const FROM_EMAIL = "notifications@example.com"; // CHANGE THIS
const TO_EMAIL = "admin@example.com"; // CHANGE THIS
const PROJECT_NAME = "My Awesome PHP Project"; // CHANGE THIS
const PHP_PATH = "/usr/local/php8.3/bin/php" ; // CHANGE THIS (with the results of 'which php')

function checkOutdatedDependencies($path) {    
putenv("COMPOSER_HOME=$path") ;
$output = [];
    $return_var = 0;
    $command = PHP_PATH . " $path/composer.phar outdated --working-dir $path";

    exec($command . ' 2>&1', $output, $return_var);
    

    
    if(countUpToDateLines($output) >= 2) {return null;}

    return implode("\n", $output);
}

function sendEmail($subject, $body, $toEmail, $fromEmail) {
    $headers = "From: $fromEmail\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (mail($toEmail, $subject, $body, $headers)) {
        echo "Email sent successfully\n";
    } else {
        echo "Failed to send email\n";
    }
}


function countUpToDateLines(array $output) {

  $count = 0;
  foreach($output as $line) {
  
    if($line == "Everything up to date") $count++;
    
  }
  return $count;

}

function main($argv) {
    $path = PATH;
    $toEmail = TO_EMAIL;
    $fromEmail = FROM_EMAIL;

    $outdatedDependencies = checkOutdatedDependencies($path);
    $body = "<pre>$outdatedDependencies</pre>";

    if (!empty($outdatedDependencies)) {
        $subject = "[" . PROJECT_NAME . "] - Composer Dependencies Update Notification";
        sendEmail($subject, $body, $toEmail, $fromEmail);
    } else {
        echo "No outdated dependencies found.\n";
    }
}

main([]);

<?php
$to      = 'gowtham25alaguraj@gmail.com';
$subject = 'REG: Test mail from travis';
$message = 'This is a test mail';
$headers = 'From: travis@travis-ci.com' . "\r\n" .
    'Reply-To: builds@travis-ci.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

var_dump(mail($to, $subject, $message, $headers));
?>
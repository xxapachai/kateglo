<?php
$to = 'ubuntu@localhost';
$subject = 'Test email using PHP';
$message = 'This is a test email message';
$headers = 'From: webmaster@localhost' . "\r\n" .
    'Reply-To: webmaster@localhost' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers, '-fwebmaster@localhost')) {
    echo "Mail sent successfully, check your mail!!!!";
} else {
    echo "Mail send failed! Check your configuration.";
}
?> 
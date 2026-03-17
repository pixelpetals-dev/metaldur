<?php
$config = require __DIR__ . '/../config/db.php'; 
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');
if (!empty($_POST['website_url'])) {
    echo json_encode(['status' => 'success', 'message' => 'Subscribed']);
    exit;
}

$submitTime = isset($_POST['form_timestamp']) ? (int)$_POST['form_timestamp'] : 0;
if (time() - $submitTime < 2) {
    echo json_encode(['status' => 'error', 'message' => 'Submission too fast. Please try again.']);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();                                            
    $mail->Host       = $config['mail_host'];
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = $config['mail_username'];
    $mail->Password   = $config['mail_password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = $config['mail_port'];                                    

    // Recipients
    $mail->setFrom($config['mail_from'], 'Metaldur Newsletter');
    $mail->addAddress('sales@carbiforce.com');

    // Content
    $mail->isHTML(true);                                  
    $mail->Subject = "New Newsletter Subscriber: $email";
    $mail->Body    = "<strong>New Subscriber:</strong> $email<br>Date: " . date('Y-m-d H:i:s');
    $mail->AltBody = "New Subscriber: $email";

    $mail->send();
    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    error_log("Subscribe Error: " . $mail->ErrorInfo);
    echo json_encode(['status' => 'error', 'message' => 'Could not subscribe. Try again later.']);
}
?>
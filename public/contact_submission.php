<?php

$config = require __DIR__ . '/../config/db.php'; 
require_once __DIR__ . '/../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if (!empty($_POST['website_url'])) {

    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully.']);
    exit; 
}

$submitTime = isset($_POST['form_timestamp']) ? (int)$_POST['form_timestamp'] : 0;
if (time() - $submitTime < 2) {

    echo json_encode(['status' => 'error', 'message' => 'Submission too fast. Please wait a moment.']);
    exit;
}

$name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

if (!$name || !$email || !$message) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input details.']);
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

    $mail->setFrom($config['mail_from'], $config['mail_from_name']);
    $mail->addAddress('sales@carbiforce.com'); 

    $mail->addReplyTo($email, $name);           

    $mail->isHTML(true);                                  
    $mail->Subject = "New Inquiry: $name";

    $emailBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            body { margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
            .wrapper { width: 100%; table-layout: fixed; background-color: #f1f5f9; padding-bottom: 40px; }
            .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

            .header { background-color: #050a10; padding: 30px 0; text-align: center; border-bottom: 3px solid 

            .brand { color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: 3px; text-transform: uppercase; margin: 0; }

            .content { padding: 40px; color: #334155; }
            .intro-title { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 8px 0; }
            .intro-text { font-size: 14px; color: #64748b; margin: 0 0 30px 0; }

            .field-row { margin-bottom: 20px; border-bottom: 1px solid 

            .field-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 4px; display: block; }
            .field-value { font-size: 16px; font-weight: 500; color: #0f172a; margin: 0; }
            .field-value a { color: #3b82f6; text-decoration: none; }

            .message-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-top: 30px; margin-bottom: 8px; display: block; }
            .message-box { background-color: #f8fafc; border-left: 4px solid 

            .footer { background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }
        </style>
    </head>
    <body>
        <div class='wrapper'>
            <br>
            <div class='container'>
                <div class='header'>
                    <h1 class='brand'>METALDUR</h1>
                </div>

                <div class='content'>
                    <h2 class='intro-title'>New Website Inquiry</h2>
                    <p class='intro-text'>You have received a new lead via the contact form.</p>

                    <div class='field-row'>
                        <span class='field-label'>Name</span>
                        <p class='field-value'>$name</p>
                    </div>

                    <div class='field-row'>
                        <span class='field-label'>Email</span>
                        <p class='field-value'><a href='mailto:$email'>$email</a></p>
                    </div>

                    <div class='field-row'>
                        <span class='field-label'>Phone</span>
                        <p class='field-value'><a href='tel:$phone'>$phone</a></p>
                    </div>

                    <span class='message-label'>Message / Requirement</span>
                    <div class='message-box'>
                        " . nl2br($message) . "
                    </div>
                </div>
            </div>

            <div class='footer'>
                &copy; " . date('Y') . " Metaldur Cutting Tools.<br>
                Powered by Carbiforce Pvt. Ltd.
            </div>
        </div>
    </body>
    </html>
    ";

    $mail->Body    = $emailBody;
    $mail->AltBody = "Name: $name\nPhone: $phone\nEmail: $email\nMessage: $message";

    $mail->send();
    echo json_encode(['status' => 'success']);

} catch (Exception $e) {

    error_log("Mailer Error: " . $mail->ErrorInfo);
    echo json_encode(['status' => 'error', 'message' => 'Message could not be sent. Please try again later.']);
}
?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require 'vendor/autoload.php';

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $zip = $_POST['zip'];
    $message = $_POST['message'];

    // Admin email address
    $adminEmail = $_ENV['ADMIN_EMAIL'];

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.sendgrid.net';    // Set the SMTP server to send through
        $mail->SMTPAuth = true;                   // Enab   le SMTP authentication
        $mail->Username = 'apikey';               // SendGrid API key as the username
        $mail->Password = $_ENV['SENDGRID_API_KEY'];  // SendGrid API key
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587;                    // TCP port to connect to

        //Recipients
        $mail->setFrom($_ENV['SENDGRID_FROM_EMAIL'], $_ENV['SENDGRID_FROM_NAME']);
        $mail->addAddress('kjaroli@gmail.com'); // Add a recipient
        $mail->addAddress('info@kamleshminerals.com');  // Add a recipient
        $mail->addAddress('support@kamleshminerals.com');  // Add a recipient
        // $mail->addAddress('kuldeep.phppoets@gmail.com'); // Add a recipient           

        // Content
        $mail->isHTML(true);                        // Set email format to HTML
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body = "
            <h2>Contact Form Submission</h2>
            <p><b>Name:</b> $name</p>
            <p><b>Email:</b> $email</p>
            <p><b>Phone:</b> $phone</p>
            <p><b>Zip Code:</b> $zip</p>
            <p><b>Message:</b> $message</p>
        ";
        $mail->AltBody = "
            Name: $name\n
            Email: $email\n
            Phone: $phone\n
            Zip Code: $zip\n
            Message: $message
        ";

        $mail->send();

        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "There was a problem with your submission. Please try again.";
}
?>
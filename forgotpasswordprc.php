<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include Composer's autoloader

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "sms_db"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you have the user's email after they requested a password reset
$email = $_POST['email'];

// Generate a unique token
$token = bin2hex(random_bytes(16));

// Store the token in the user's table (for example, teacher)
$update_sql = "UPDATE teacher SET reset_token = ? WHERE email = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("ss", $token, $email);
$stmt->execute();

// Prepare the reset link
$reset_link = "http://yourdomain.com/reset_password.php?token=" . $token;

// Send the email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rasesrom0@gmail.com';       // SMTP username
    $mail->Password = 'cdbhkiurykowykqw';   // Replace with your email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('no-reply@yourdomain.com', 'Your School Name');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Reset Your Password';
    $mail->Body = <<<END
    
    Click <a href="http://example.com/resetpassword.php?token=$token">here</a> to reset your password.;
    
    END;

    $mail->send();
    echo "Password reset link has been sent to your email.";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

$stmt->close();
$conn->close();
?>

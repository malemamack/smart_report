<?php

session_start();

require 'vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Send OTP via email
function sendOtpEmail($to, $otp) {
$mail = new PHPMailer(true);
try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'malemamahlatse70@gmail.com';       // SMTP username
    $mail->Password = 'cdbhkiurykowykqw';     // Your email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('no-reply@yourdomain.com', 'DIOPONG PRIMARY SCHOOL');
    $mail->addAddress($to); // User's email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Your OTP code is: <strong>$otp</strong>";
    $mail->AltBody = "Your OTP code is: $otp"; // Non-HTML version

    $mail->send();

} catch (Exception $e) { echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; }
}
 
// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
 
// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);



// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['otp'])) {
    $enteredOtp = $_POST['otp'];

    // Ensure OTP is set in the session
    if (isset($_SESSION['otp']) && $enteredOtp == $_SESSION['otp']) {
        $role = $_SESSION['role'];
        $id = $_SESSION['temp_user_id'];
    // Ensure OTP is set in the session
    if (isset($_SESSION['otp']) && $enteredOtp == $_SESSION['otp']) {
        $role = $_SESSION['role'];
        $id = $_SESSION['temp_user_id'];

        // Set the appropriate session variables based on the role
        if ($role == 'Admin') {
            // Set admin session variable
            $_SESSION['admin_id'] = $id;
            header("Location: admin/index.php");
            exit;
        } elseif ($role == 'Parent') {
            // Set parent session variable
            $_SESSION['parent_id'] = $id;   // Ensure r_user_id is set correctly
            header("Location: parent/index.php"); // Redirect to parent dashboard
            exit;
        } elseif ($role == 'Teacher') {
            // Set teacher session variable
            $_SESSION['teacher_id'] = $id;
            header("Location: teacher/index.php");
            exit;
        }

        // Clear OTP and temporary user ID after successful verification
        unset($_SESSION['otp']);
        unset($_SESSION['temp_user_id']);
    } else {
        $error = "Invalid OTP. Please try again.";
    }
        // Set the appropriate session variables based on the role
        if ($role == 'Admin') {
            // Set admin session variable
            $_SESSION['admin_id'] = $id;
            header("Location: admin/index.php");
            exit;
        } elseif ($role == 'Parent') {
            // Set parent session variable
            $_SESSION['parent_id'] = $id;   // Ensure r_user_id is set correctly
            header("Location: parent/index.php"); // Redirect to parent dashboard
            exit;
        } elseif ($role == 'Teacher') {
            // Set teacher session variable
            $_SESSION['teacher_id'] = $id;
            header("Location: teacher/index.php");
            exit;
        }

        // Clear OTP and temporary user ID after successful verification
        unset($_SESSION['otp']);
        unset($_SESSION['temp_user_id']);
    } else {
        $error = "Invalid OTP. Please try again.";
    }
} elseif (isset($_POST['resend'])) {
    // Resend OTP functionality
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Check for a cooldown period (e.g., 30 seconds)
        $currentTime = time();
        $lastOtpTime = $_SESSION['otp_time'] ?? 0;
        if ($currentTime - $lastOtpTime < 30) {
            $error = "Please wait before resending the OTP.";
        } else {
            // Generate a new OTP and store it in the session
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_time'] = $currentTime;

            // Send the OTP via PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'your_email@gmail.com'; // Replace with your email
                $mail->Password   = 'your_password'; // Replace with your password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('your_email@gmail.com', 'Your App Name');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body    = "Your OTP code is <b>$otp</b>. It expires in 5 minutes.";

                $mail->send();
                $success = "A new OTP has been sent to your email.";
            } catch (Exception $e) {
                $error = "Error sending OTP: {$mail->ErrorInfo}";
            }
        }
    } else {
        $error = "Email not found in session. Please try logging in again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification page</title>
</head>
<body>
<div class="d-flex justify-content-center align-items-center flex-column">
<div class="text-center">
    
    			<img src="1.jpg" style="border-radius: 40%;"
    			     width="100" >
    		</div>
    <h2>Enter the OTP sent to your email</h2>
    <form method="post" action="">
        <input type="text" name="otp" required placeholder="Enter OTP">
        <button type="submit" class="btn btn-secondary">Verify OTP</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </div>
    <form method="post" action="" style="margin-top: 10px;">
        <button type="submit" name="resend" class="btn btn-secondary">Resend OTP</button>
    </form>
    <?php
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    if (isset($success)) {
        echo "<p style='color:green;'>$success</p>";
    }
    ?>
</body>
</html>

<?php
session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Function to send OTP email
// Function to send OTP email
function sendOtpEmail($to, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'malemamahlatse70@gmail.com';       // SMTP username
        $mail->Password = 'cdbhkiurykowykqw';     // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

       
        $mail->setFrom('no-reply@yourdomain.com', 'DIOPONG PRIMARY SCHOOL');
        $mail->addAddress($to); // Send OTP to this email address

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: <strong>$otp</strong>";
        $mail->AltBody = "Your OTP code is: $otp";

        if ($mail->send()) {
            return true;
        } else {
            // Log SMTP error
            error_log("Error: {$mail->ErrorInfo}");
            return false;
        }
    } catch (Exception $e) {
        // Catch and log any other errors
        error_log("Error sending OTP: {$e->getMessage()}");
        return false;
    }
}


// Ensure the email exists in the session
$email = $_SESSION['email'] ?? null;
if (!$email) {
    $error = "Email not found in session. Please log in first.";
}

// Handle OTP resend
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend'])) {
    $currentTime = time();
    $lastOtpTime = $_SESSION['otp_time'] ?? 0; // Last OTP time in session
    $cooldownPeriod = 10; // Cooldown in seconds

    if ($currentTime - $lastOtpTime < $cooldownPeriod) {
        // If cooldown period hasn't passed
        $waitTime = $cooldownPeriod - ($currentTime - $lastOtpTime);
        $error = "Please wait {$waitTime} seconds before requesting a new OTP.";
    } else {
        // If cooldown period is over, send a new OTP
        $otp = random_int(100000, 999999); // Generate a new OTP
        $_SESSION['otp'] = $otp; // Store OTP in session
        $_SESSION['otp_time'] = $currentTime; // Store OTP time in session

        if (sendOtpEmail($email, $otp)) {
            $success = "A new OTP has been sent to " . htmlspecialchars($email);
        } else {
            $error = "Failed to send OTP. Please try again.";
        }
    }
}

// Handle OTP verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $enteredOtp = htmlspecialchars($_POST['otp'], ENT_QUOTES, 'UTF-8');

    if (isset($_SESSION['otp']) && $enteredOtp == $_SESSION['otp']) {
        // ... (your existing OTP verification logic)
        $role = $_SESSION['role'] ?? '';
        $id = $_SESSION['temp_user_id'] ?? '';

        switch ($role) {
            case 'Admin':
                $_SESSION['admin_id'] = $id;
                header("Location: admin/index.php");
                break;
            case 'Parent':
                $_SESSION['parent_id'] = $id;
                header("Location: parent/index.php");
                break;
            case 'Teacher':
                $_SESSION['teacher_id'] = $id;
                header("Location: teacher/index.php");
                break;
            default:
                $error = "Invalid role specified.";
                break;
        }

        if (!isset($error)) {
            unset($_SESSION['otp'], $_SESSION['temp_user_id'], $_SESSION['otp_time']);
            exit;
        }
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="1.jpg">

</head>

<body class="body-home">
    <div class="black-fill"><br /> <br />
    	<div class="container" style="width:40%; border-radius: 100px; overflow : hidden;">
    	<nav class="navbar navbar-expand-lg bg-light"
    	     id="homeNav">
		  <div class="container-fluid" st>
     <div class="container d-flex justify-content-center align-items-center flex-column" style="height: 80vh;">
        <div class="text-center mb-4">
            <img src="1.jpg" alt="School Logo" style="border-radius: 40%;" width="100">
        </div>
        <h2>Enter the OTP sent to your email</h2>
        <form method="post" action="" class="mb-3">
            <div class="form-group">
                <input type="text" name="otp" required placeholder="Enter OTP" class="form-control mb-3" pattern="[0-9]{6}" maxlength="6">
            </div>
            <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="" class="mt-3">
            <button type="submit" name="resend" class="btn btn-primary w-100" id="resendButton">
                Resend OTP
            </button>
        </form>
        
     </div>
    </div>
    </div>
    </div>
    
</body>
</html>

<?php
session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOtpEmail($to, $otp) {
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_EMAIL');
        $mail->Password = getenv('SMTP_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->setFrom('no-reply@yourdomain.com', 'DIOPONG PRIMARY SCHOOL');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: <strong>$otp</strong>";
        $mail->AltBody = "Your OTP code is: $otp";
        
        return $mail->send();
    } catch (Exception $e) {
        error_log("Error sending OTP: {$mail->ErrorInfo}");
        return false;
    }
}

$email = $_SESSION['email'] ?? null;
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle OTP verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $enteredOtp = htmlspecialchars($_POST['otp'], ENT_QUOTES, 'UTF-8');
    
    if (isset($_SESSION['otp']) && $enteredOtp == $_SESSION['otp']) {
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

// Handle OTP resend
if (isset($_POST['resend'])) {
    if ($email) {
        $currentTime = time();
        $lastOtpTime = $_SESSION['otp_time'] ?? 0;
        $cooldownPeriod = 30; // Cooldown in seconds
        
        if ($currentTime - $lastOtpTime < $cooldownPeriod) {
            $waitTime = $cooldownPeriod - ($currentTime - $lastOtpTime);
            $error = "Please wait {$waitTime} seconds before requesting a new OTP.";
        } else {
            $otp = random_int(100000, 999999); // More secure than rand()
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_time'] = $currentTime;
            
            if (sendOtpEmail($email, $otp)) {
                $success = "A new OTP has been sent to " . htmlspecialchars($email);
            } else {
                $error = "Failed to send OTP. Please try again.";
            }
        }
    } else {
        $error = "Email not found in session. Please try logging in again.";
    }
}
?>
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="1.jpg">
</head>
<body class="body-OTP">
    <div class="black-fill"><br /> <br />
    	<div class="d-flex justify-content-center align-items-center flex-column">

    		<div class="text-center">
    			<img src="1.jpg" style="border-radius: 40%;"
    			     width="100" >
        <h3>Enter the OTP sent to your email</h3>
    <form method="post" action="">
        <input type="text" name="otp" required placeholder="Enter OTP">
        <button type="submit">Verify OTP</button>
    </form>
    </div>
       </div>
           </div>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="1.jpg">
    <style>
        /* Set background color to black */
        body.otp-body {
            background-color: rgba(255,255,255, 0.5);
            height: 100vh;
            margin: 0;
        }

        /* Center the container in the middle of the screen */
        .otp-black-fill {
            background-color: rgba(10, 10, 10, 0.5); /* black background */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Create a white box/container for the OTP content */
        .otp-container {
            background-color: white; /* white background for container */
            padding: 40px;
            border-radius: 10px; /* rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* subtle shadow effect */
            width: 100%;
            max-width: 400px; /* max width of the container */
        }

        /* Style the OTP input field */
        .otp-input {
            font-size: 1.2rem;
            padding: 10px;
        }

        /* Style the OTP title */
        .otp-title {
            font-size: 1.6rem;
            font-weight: bold;
        }

        /* Style the OTP buttons */
        .otp-btn {
            padding: 8px 8px;
        }

        /* Style for OTP error message */
        .otp-error {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body class="otp-body">
    <div class="otp-black-fill">
        <div class="otp-container text-center">
            <div class="otp-logo mb-4">
                <img src="1.jpg" style="border-radius: 40%;" width="100">
            </div>
            <h3 class="otp-title">Enter the OTP sent to your email</h3>
            <form method="post" action="" class="otp-form">
                <div class="mb-3">
                    <input type="text" name="otp" required class="otp-input form-control" placeholder="Enter OTP" maxlength="6">
                </div>
                <div class="d-flex justify-content-center gap-3 otp-buttons">
                    <button type="submit" class="otp-btn btn btn-primary">Verify OTP</button>
                    <button type="button" class="otp-btn btn btn-secondary" onclick="resendOTP()">Resend OTP</button>
                     <a href="login.php" class="otp-btn btn btn-secondary">Back</a>
                </div>
            </form>
            <?php if (isset($error)) echo "<p class='otp-error'>$error</p>"; ?>
        </div>
    </div>
    
    <script>
        function resendOTP() {
            // Add your resend OTP logic here
            alert("Resend OTP clicked");
        }
    </script>
</body>
</html>


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

        $result = $mail->send();
        error_log("Email sent successfully to: $to");
        return $result;
    } catch (Exception $e) {
        error_log("Error sending OTP: {$mail->ErrorInfo}");
        return false;
    }
}

$email = $_SESSION['email'] ?? null;
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['resend'])) {
        // Handle OTP resend request
        $currentTime = time();
        $lastOtpTime = $_SESSION['otp_time'] ?? 0;
        $cooldownPeriod = 10; // Cooldown in seconds

        if ($currentTime - $lastOtpTime < $cooldownPeriod) {
            $waitTime = $cooldownPeriod - ($currentTime - $lastOtpTime);
            $error = "Please wait {$waitTime} seconds before requesting a new OTP.";
        } else {
            $otp = random_int(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_time'] = $currentTime;

            if (sendOtpEmail($email, $otp)) {
                $success = "A new OTP has been sent to " . htmlspecialchars($email);
            } else {
                $error = "Failed to send OTP. Please try again.";
            }
        }
    } else if (isset($_POST['otp'])) {
        // Handle OTP verification
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
<body>
<div class="container d-flex justify-content-center align-items-center flex-column" style="height: 100vh;">
    <div class="text-center mb-4">
        <img src="1.jpg" alt="School Logo" style="border-radius: 40%;" width="100">
    </div>
    <h2>Enter the OTP sent to your email</h2>
    <form method="post" action="" class="mb-3">
        <div class="form-group">
            <input type="text"
                   name="otp"
                   required
                   placeholder="Enter OTP"
                   class="form-control mb-3"
                   pattern="[0-9]{6}"
                   maxlength="6">
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
        <button type="submit"
                name="resend"
                class="btn btn-primary w-100"
                id="resendButton">
            Resend OTP
        </button>
    </form>
</div>

<script>
// Add countdown timer for resend button
document.addEventListener('DOMContentLoaded', function() {
    const resendButton = document.getElementById('resendButton');
    let cooldown = <?php echo isset($_SESSION['otp_time']) ?
    max(0, 10 - (time() - $_SESSION['otp_time'])) : 0; ?>;

    function updateButton() {
        if (cooldown > 0) {
            resendButton.disabled = true;
            resendButton.textContent = `Wait ${cooldown} seconds`;
            cooldown--;
            setTimeout(updateButton, 1000);
        } else {
            resendButton.disabled = false;
            resendButton.textContent = 'Resend OTP';
        }
    }

    if (cooldown > 0) {
        updateButton();
    }

    // Add click handler
    resendButton.addEventListener('click', function() {
        if (!this.disabled) {
            cooldown = 10;
            updateButton();
        }
    });
});
</script>
</body>
</html>

<?php 
session_start();
require "../vendor/autoload.php"; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send email
function sendEmail($recipientEmail, $subject, $body) {
    // Check if environment variables are set
    $emailUser = getenv('EMAIL_USER');
    $emailPass = getenv('EMAIL_PASS');

    if (empty($emailUser) || empty($emailPass)) {
        echo 'Error: EMAIL_USER or EMAIL_PASS not set in environment variables.';
        return;
    }

    if (empty($recipientEmail) || empty($subject) || empty($body)) {
        echo 'Error: Recipient email, subject, or body cannot be empty.';
        return;
    }

    // Sanitize email to avoid invalid characters or formats
    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        echo 'Error: Invalid recipient email address.';
        return;
    }



    $mail = new PHPMailer(true); // true enables exceptions
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('EMAIL_USER'); // Use environment variables
        $mail->Password = getenv('EMAIL_PASS');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        //Recipients
        $mail->setFrom($emailUser, 'Mailer');  // Set your sender email and name
        $mail->addAddress($recipientEmail);  // Add the recipient email



        // Content
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send the email
           // Send the email
           $mail->send();
           echo 'Message has been sent';
       } catch (Exception $e) {
           echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
       }
   }


if (isset($_POST['uname']) && isset($_POST['pass']) && isset($_POST['role'])) {
    include "../DB_connection.php";

    $uname = $_POST['uname'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    // Validate inputs
    if (empty($uname)) {
        $em  = "Username is required";
        header("Location: ../login.php?error=$em");
        exit;
    } else if (empty($pass)) {
        $em  = "Password is required";
        header("Location: ../login.php?error=$em");
        exit;
    } else if (empty($role)) {
        $em  = "Role is required";
        header("Location: ../login.php?error=$em");
        exit;
    }

    // Determine the correct table and role
    switch ($role) {
        case '1':
            $sql = "SELECT * FROM admin WHERE username = ?";
            $roleName = "Admin";
            break;
        case '2':
            $sql = "SELECT * FROM teachers WHERE username = ?";
            $roleName = "Teacher";
            break;
        case '3':
            $sql = "SELECT * FROM students WHERE username = ?";
            $roleName = "Student";
            break;
        case '4':
            $sql = "SELECT * FROM parent WHERE username = ?";
            $roleName = "Parent";
            break;
        default:
            $em  = "Invalid role";
            header("Location: ../login.php?error=$em");
            exit;
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute([$uname]);

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();
        $username = $user['username'];
        $password = $user['password'];
        $email = $user['email_address'];  // Assuming email_address exists in the database

        if ($username === $uname && password_verify($pass, $password)) {
            // User authenticated, generate OTP
            $otp = rand(100000, 999999); // 6-digit OTP
            $_SESSION['otp'] = $otp; // Store OTP in session
            $_SESSION['role'] = $roleName;
            $_SESSION['username'] = $username;

            // Send OTP via email
            $subject = 'Your OTP for Diopong Primary School Login';
            $body = "Dear $username,<br><br>Your OTP for Diopong Primary School login is: <strong>$otp</strong><br><br>Thank you,<br>Y School";
            sendEmail($user['email_address'], $subject, $body);

            // Redirect to OTP verification page
            header("Location: ../verify_otp.php");
            exit;
        } else {
            $em  = "Incorrect Username or Password";
            header("Location: ../login.php?error=$em");
            exit;
        }
    } else {
        $em  = "Incorrect Username or Password";
        header("Location: ../login.php?error=$em");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>

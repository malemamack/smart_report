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
        $mail->Host = 'smtp.example.com';  // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = $emailUser; // Use environment variables
        $mail->Password = $emailPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($emailUser, 'Mailer');  // Set your sender email and name
        $mail->addAddress($recipientEmail);  // Add the recipient email

        // Content
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if (isset($_POST['uname']) && isset($_POST['pass']) && isset($_POST['role'])) {
    include "../DB_connection.php";  // Include your DB connection file

    $uname = $_POST['uname'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    // Validate inputs
    if (empty($uname)) {
        $em = "Username is required";
        header("Location: ../login.php?error=$em");
        exit;
    } else if (empty($pass)) {
        $em = "Password is required";
        header("Location: ../login.php?error=$em");
        exit;
    } else if (empty($role)) {
        $em = "Role is required";
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
            $em = "Invalid role";
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

        // Verify password
        if ($username === $uname && password_verify($pass, $password)) {
            $_SESSION['role'] = $roleName;  // Store role in session
            session_regenerate_id(true);  // Regenerate session ID to prevent session fixation

            // Generate and send OTP for Parent and Teacher roles
            if ($roleName == 'Parent' || $roleName == 'Teacher') {
                $otp = rand(100000, 999999);  // Generate a 6-digit OTP
                $_SESSION['otp'] = $otp;  // Store OTP in session
                $_SESSION['temp_user_id'] = $user['id'];  // Store user ID temporarily

                // Send OTP via the sendEmail function
                $subject = 'Your Login OTP';
                $body = "Your OTP is <b>$otp</b>. It expires in 5 minutes.";
                sendEmail($email, $subject, $body);

                // Redirect to OTP verification page
                header("Location: ../verify_otp.php");
                exit;
            } else {
                // For Admin and Student roles, login directly
                $id = ($roleName == 'Admin') ? $user['admin_id'] : $user['student_id'];
                $_SESSION[strtolower($roleName) . '_id'] = $id;
                header("Location: ../" . strtolower($roleName) . "/index.php");
                exit;
            }
        } else {
            $em = "Incorrect Username or Password";
            header("Location: ../login.php?error=$em");
            exit;
        }
    } else {
        $em = "Incorrect Username or Password";
        header("Location: ../login.php?error=$em");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}

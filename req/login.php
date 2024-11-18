<?php 
session_start();
require "../vendor/autoload.php"; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send email
function sendEmail($recipientEmail, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'malemamahlatse70@gmail.com'; // SMTP username
        $mail->Password   = 'cdbhkiurykowykqw'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('your_email@example.com', 'DIOPONG PRIMARY SCHOOL');
        $mail->addAddress($recipientEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}";
    }
}

include "../DB_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = $_POST['uname'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $role = $_POST['role'] ?? '';

    // Input validation
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

    // Determine the correct SQL query based on the role
    if ($role == '1') {
        $sql = "SELECT * FROM admin WHERE username = ?";
        $roleName = "Admin";
    } else if ($role == '2') {
        $sql = "SELECT * FROM teachers WHERE username = ?";
        $roleName = "Teacher";
    } else if ($role == '4') {
        $sql = "SELECT * FROM parent WHERE username = ?";
        $roleName = "Parent";
    } else {
        $em = "Invalid role selected";
        header("Location: ../login.php?error=$em");
        exit;
    }

    // Prepare and execute query
    $stmt = $conn->prepare($sql);
    $stmt->execute([$uname]);

    // Check if user exists
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();
        $username = $user['username'];
        $password = $user['password'];
        $email = $user['email_address'] ?? null; // Assuming email_address exists in the database

        // Verify password
        if ($username === $uname && password_verify($pass, $password)) {
            $_SESSION['role'] = $roleName;

            // Generate and send OTP for Admin, Parent, and Teacher roles
            if (in_array($roleName, ['Admin', 'Parent', 'Teacher'])) {
                $otp = rand(100000, 999999); // Generate a 6-digit OTP
                $_SESSION['otp'] = $otp; // Store OTP in session
                $_SESSION['temp_user_id'] = $user[strtolower($roleName) . '_id'] ?? null; // Ensure role_id exists

                // Send OTP via PHPMailer
                $emailResult = sendEmail($email, 'Your Login OTP', "Your OTP is <b>$otp</b>. It expires in 5 minutes.");

                if ($emailResult === true) {
                    header("Location: ../verify_otp.php"); // Redirect to OTP verification page
                    exit;
                } else {
                    $em = "OTP could not be sent. $emailResult";
                    header("Location: ../login.php?error=$em");
                    exit;
                }
            } else {
                // For Admin, Parent, and Teacher roles, login directly
                $_SESSION[strtolower($roleName) . '_id'] = $user[strtolower($roleName) . '_id']; // Store the role-specific ID
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
?>

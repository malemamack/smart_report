<?php
session_start();
require "../vendor/autoload.php"; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['uname']) && isset($_POST['pass']) && isset($_POST['role'])) {
    include "../DB_connection.php";

    $uname = $_POST['uname'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    if (empty($uname) || empty($pass) || empty($role)) {
        $em = "All fields are required.";
        header("Location: ../login.php?error=$em");
        exit;
    }

    // Validate role and query database
    if ($role == '1') {
        $sql = "SELECT * FROM admin WHERE username = ?";
        $role = "Admin";
    } elseif ($role == '2') {
        $sql = "SELECT * FROM teachers WHERE username = ?";
        $role = "Teacher";
    } elseif ($role == '4') {
        $sql = "SELECT * FROM parent WHERE username = ?";
        $role = "Parent";
    } else {
        $em = "Invalid role selected.";
        header("Location: ../login.php?error=$em");
        exit;
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute([$uname]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch();
        $username = $user['username'];
        $password = $user['password'];
        $email = $user['email_address'] ?? null;

        if ($username === $uname && password_verify($pass, $password)) {
            $_SESSION['role'] = $role;

            if (in_array($role, ['Admin', 'Teacher', 'Parent'])) {
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['otp_time'] = time();
                $_SESSION['temp_user_id'] = $user[strtolower($role) . '_id'] ?? null;

                // Send OTP via PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'malemamahlatse70@gmail.com';
                    $mail->Password   = 'cdbhkiurykowykqw';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('your_email@example.com', 'DIOPONG PRIMARY SCHOOL');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Your Login OTP';
                    $mail->Body    = "Your OTP is <b>$otp</b>. It expires in 5 minutes.";

                    $mail->send();
                    header("Location: ../verify_otp.php");
                    exit;
                } catch (Exception $e) {
                    error_log("Mailer Error: {$mail->ErrorInfo}");
                    $em = "OTP could not be sent. Please try again.";
                    header("Location: ../login.php?error=$em");
                    exit;
                }
            } else {
                $_SESSION[strtolower($role) . '_id'] = $user[strtolower($role) . '_id'];
                header("Location: ../" . strtolower($role) . "/index.php");
                exit;
            }
        } else {
            $em = "Incorrect Username or Password.";
            header("Location: ../login.php?error=$em");
            exit;
        }
    } else {
        $em = "User not found.";
        header("Location: ../login.php?error=$em");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}

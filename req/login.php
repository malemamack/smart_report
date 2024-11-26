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

    if (empty($uname)) {
        $em  = "Username is required";
        header("Location: ../login.php?error=$em");
        exit;
    } else if (empty($pass)) {
        $em  = "Password is required";
        header("Location: ../login.php?error=$em");
        exit;
    } else if (empty($role)) {
        $em  = "An error occurred";
        header("Location: ../login.php?error=$em");
        exit;
    } else {
        // Determine the correct SQL query based on the role
        if ($role == '1') {
            $sql = "SELECT * FROM admin WHERE username = ?";
            $role = "Admin";
        } else if ($role == '2') {
            $sql = "SELECT * FROM teachers WHERE username = ?";
            $role = "Teacher";
        } else if ($role == '4') {
            $sql = "SELECT * FROM parent WHERE username = ?";
            $role = "Parent";
        }

        // Prepare and execute query
        $stmt = $conn->prepare($sql);
        $stmt->execute([$uname]);

        // Check if user exists
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch();
            $username = $user['username'];
            $password = $user['password'];
            $email = $user['email_address']; // Assuming email_address exists in the database

            // Verify password
            if ($username === $uname && password_verify($pass, $password)) {
                $_SESSION['role'] = $role;
                $_SESSION['email'] = $email; // Store email in session for OTP sending

                // Generate and send OTP for Admin, Parent, and Teacher roles
                if ($role == 'Admin' || $role == 'Parent' || $role == 'Teacher') {
                    $otp = rand(100000, 999999); // Generate a 6-digit OTP
                    $_SESSION['otp'] = $otp; // Store OTP in session
                    $_SESSION['otp_time'] = time(); // Store OTP generation time
                    $_SESSION['temp_user_id'] = $user[strtolower($role) . '_id'] ?? null; // Ensure role_id exists
                    
                    // Send OTP via PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'malemamahlatse70@gmail.com'; // SMTP username
                        $mail->Password   = 'cdbhkiurykowykqw'; // SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;

                        $mail->setFrom('your_email@example.com', 'DIOPONG PRIMARY SCHOOL');
                        $mail->addAddress($email);

                        $mail->isHTML(true);
                        $mail->Subject = 'Your Login OTP';
                        $mail->Body    = "Your OTP is <b>$otp</b>. It expires in 5 minutes.";

                        $mail->send();
                        header("Location: ../verify_otp.php"); // Redirect to OTP verification page
                        exit;
                    } catch (Exception $e) {
                        $em = "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        header("Location: ../login.php?error=$em");
                        exit;
                    }
                } else {
                    // For Admin, Parent, and Teacher roles, login directly
                    $_SESSION[strtolower($role) . '_id'] = $user[strtolower($role) . '_id']; // Store the role-specific ID
                    header("Location: ../" . strtolower($role) . "/index.php");
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
    }
} else {
    header("Location: ../login.php");
    exit;
}

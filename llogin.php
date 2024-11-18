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

// Collect and sanitize input
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

// Check admin table first
$sql_admin = "SELECT * FROM admin WHERE username = ?";
$stmt = $conn->prepare($sql_admin);
$stmt->bind_param("s", $username);
$stmt->execute();
$result_admin = $stmt->get_result();
$user = $result_admin->fetch_assoc();

if (!$user) {
    // Check teacher table
    $sql_teacher = "SELECT * FROM teacher WHERE username = ?";
    $stmt = $conn->prepare($sql_teacher);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result_teacher = $stmt->get_result();
    $user = $result_teacher->fetch_assoc();
}

if (!$user) {
    // Check parent table
    $sql_parent = "SELECT * FROM parent WHERE username = ?";
    $stmt = $conn->prepare($sql_parent);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result_parent = $stmt->get_result();
    $user = $result_parent->fetch_assoc();
}

if ($user) {
    if (password_verify($password, $user['password'])) {
        // Password is correct
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

         // Determine the correct user ID based on the user type
        if ($_SESSION['user_type'] == 'admin') {
            $_SESSION['user_id'] = $user['Admin_ID']; // Admin ID
        } elseif ($_SESSION['user_type'] == 'teacher') {
            $_SESSION['user_id'] = $user['Teacher_ID']; // Teacher ID
        } elseif ($_SESSION['user_type'] == 'parent') {
            $_SESSION['user_id'] = $user['Parent_ID']; // Parent ID
        }

        // If user is admin, skip OTP and redirect to admin dashboard
        if ($_SESSION['user_type'] == 'admin') {
            // Redirect to admin dashboard directly
            header("Location: admind.html");
            exit();
        }

        // Otherwise, for teachers and parents, send OTP via email
        // Check if email is set
        if (isset($user['Email'])) { // Change 'email' to 'Email'
            $to = $user['Email']; // Use the correct key for email
        } else {
            echo "Email is not set for the user.";
            exit();
        }

        // Generate OTP
        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $_SESSION['otp'] = $otp; // Store OTP in session

        // Send OTP via email
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

            // Redirect to OTP verification page
            header("Location: verityotp.php");
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with that username.";
}

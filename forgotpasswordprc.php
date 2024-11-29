<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer's autoloader (if you're using Composer)

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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];  // The email the user provided for password reset

    // Generate a secure random token
    $token = bin2hex(random_bytes(16));  // Generates a 32-character random token
    $expiry_time = date("Y-m-d H:i:s", strtotime("+2 hour"));  // Token expires in 2 hours

    // Arrays of role-based tables
    $role_tables = ['admin', 'teachers', 'parent'];

    // Loop through each role table to check for the email and set the reset token
    foreach ($role_tables as $role_table) {
        $sql = "SELECT * FROM $role_table WHERE email_address = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User found, update the token and expiry time
            $update_sql = "UPDATE $role_table SET reset_token = ?, reset_token_expiry = ? WHERE email_address = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sss", $token, $expiry_time, $email);

            if ($update_stmt->execute()) {
                // Send the reset link to the user's email address using PHPMailer
                $reset_url = "http://localhost/smart_report/forgotpswreset.php?token=" . $token;

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; 
                    $mail->SMTPAuth = true;
                    $mail->Username = 'malemamahlatse70@gmail.com'; // Your email
                    $mail->Password = 'cdbhkiurykowykqw'; // Your email password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('no-reply@yourdomain.com', 'Diopong Primary School Admin Team');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Reset Your Password';
                    $mail->Body = <<<END
                        <p>We received a request to reset your password. Click the link below to reset your password:</p>
                        <p><a href="$reset_url">Reset Password</a></p>
                        <p>If you did not request a password reset, please ignore this email.</p>
                    END;

                    $mail->send();

                    // Display success message and redirect
                    echo "<script>
                        alert('Password reset email has been sent successfully! You will be redirected to the home page.');
                        window.location.href = 'index.php'; // Redirect to the home page
                    </script>";
                    exit;

                } catch (Exception $e) {
                    echo "<script>
                        alert('Error sending email: " . addslashes($mail->ErrorInfo) . "');
                        window.location.href = 'index.php'; // Redirect to the home page
                    </script>";
                    exit;
                }
            } else {
                echo "Error updating reset token for $email.<br>";
            }

            $update_stmt->close();
            break;  // Exit the loop since we've found the matching email
        }

        $stmt->close();
    }
}

$conn->close();
?>
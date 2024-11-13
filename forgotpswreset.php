<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include Composer's autoloader

// Check if token is present
if (!isset($_GET['token'])) {
    die("Invalid token.");
}

$token = $_GET['token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>

<body>
    <h2>Reset Your Password</h2>
    <form action="forgotpswupdate.php" method="post">
        <input type="hidden" name="token"
         value="<?php echo htmlspecialchars($token); ?>">

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

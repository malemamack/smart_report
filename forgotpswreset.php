<?php
session_start();  // Only call session_start() once

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include Composer's autoloader

// Check if token is present
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Invalid or missing token.");
}

// Sanitize token to prevent XSS
$token = htmlspecialchars($_GET['token'], ENT_QUOTES, 'UTF-8');

// If you want to debug, uncomment the next line (you can var_dump the token to inspect it)
var_dump($token);  // Example debugging output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Reset Your Password</h2>
        <form action="forgotpswupdate.php" method="post" id="resetForm">
            <!-- Hidden token input -->
            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <!-- New Password Field -->
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password:</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="new_password" 
                    name="new_password" 
                    required 
                    minlength="8" 
                    maxlength="20"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}"
                    autocomplete="new-password"
                    placeholder="Enter a strong password">
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="confirm_password" 
                    name="confirm_password" 
                    required 
                    autocomplete="new-password"
                    placeholder="Re-enter your password">
            </div>

            <!-- Password Match Validation Message -->
            <div id="passwordMatchMessage" class="text-danger mb-3"></div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

    <!-- JavaScript for Password Match Validation -->
    <script>
        const resetForm = document.getElementById('resetForm');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        const passwordMatchMessage = document.getElementById('passwordMatchMessage');

        // Check if passwords match
        confirmPassword.addEventListener('input', () => {
            if (newPassword.value !== confirmPassword.value) {
                passwordMatchMessage.textContent = 'Passwords do not match.';
            } else {
                passwordMatchMessage.textContent = '';
            }
        });

        // Prevent form submission if passwords don't match
        resetForm.addEventListener('submit', (e) => {
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                passwordMatchMessage.textContent = 'Passwords do not match. Please correct it.';
            }
        });
    </script>
</body>
</html>

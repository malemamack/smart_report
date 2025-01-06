<?php
session_start();

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

// Initialize a success flag
$reset_successful = false;

// Check if the token is in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $user = null;

    // Check for the token in each table
    $tables = ['admin', 'teachers', 'parent'];
    foreach ($tables as $table) {
        $sql = "SELECT * FROM $table WHERE reset_token = ? AND reset_token_expiry > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user['table'] = $table;
            break;
        }
    }

    if (!$user) {
        header("Location: token_error.php");
        exit;
    }
} else {
    echo "No token provided.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $password_pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/';

    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    } elseif (!preg_match($password_pattern, $new_password)) {
        echo "<script>alert('Password does not meet complexity requirements.');</script>";
    } else {
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $sql = "UPDATE {$user['table']} SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_password_hashed, $token);

        if ($stmt->execute()) {
            $reset_successful = true;
        } else {
            echo "<script>alert('Error resetting password. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="1.png">
    <style>
        .small{
            color:#0056b3;
        }

        .d-flex{
            
        }

        .form-label{
            font-size: 20px;
        }

        .black-fill{
            padding-top: 100px;
        }
    </style>
</head>
<body class="body-login" style="background-image: url(IMG_3108.jpg);">
    <div class="black-fill">
    <br /><br />
    <div class="d-flex justify-content-center align-items-center flex-column">
    <div class="login" >
        <h1 class="text-center">Reset Password</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="new_password" class="form-label">Enter New Password</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
                <small class="small" id="form-text">
              
                   Password must be at least 8 characters long,
                    include an uppercase letter,
                    a lowercase letter,
                    a number,
                    and a special character.
                
                </small>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Password Reset Successful</h5>
                </div>
                <div class="modal-body">
                    Your password has been updated successfully. You will be redirected to the login page shortly.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Check if reset was successful
        <?php if ($reset_successful): ?>
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>
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

// Check if the token is in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $user = null;

    // Check if the token exists in the admin table
    $sql = "SELECT admin_id, email_address, reset_token, reset_token_expiry FROM admin WHERE reset_token = ? AND reset_token_expiry > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user['table'] = 'admin';  // Store the table name for later use
    }

    // If not found in admin, check the teachers table
    if (!$user) {
        $sql = "SELECT teacher_id, email_address, reset_token, reset_token_expiry FROM teachers WHERE reset_token = ? AND reset_token_expiry > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user['table'] = 'teachers';  // Store the table name for later use
        }
    }

    // If not found in teachers, check the parent table
    if (!$user) {
        $sql = "SELECT parent_id, email_address, reset_token, reset_token_expiry FROM parent WHERE reset_token = ? AND reset_token_expiry > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user['table'] = 'parent';  // Store the table name for later use
        }
    }

    // If user not found, display error and exit
    if (!$user) {
        header("Location: token_error.php");
        exit;  // Stop further execution
    }
    
} else {
    echo "No token provided.";
    exit;
}

// Handle password reset form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize user inputs
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
    } else {
        // Hash the new password
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $table = $user['table'];  // Get the user's table
        $primary_key_column = '';  // Variable to hold the correct primary key column name

        // Determine the primary key column for the respective table
        if ($table === 'admin') {
            $primary_key_column = 'admin_id';
        } elseif ($table === 'teachers') {
            $primary_key_column = 'teacher_id';
        } elseif ($table === 'parent') {
            $primary_key_column = 'parent_id';
        }

        // Update the password in the corresponding table and reset the token
        $update_sql = "UPDATE $table SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $new_password_hashed, $token);

        if ($update_stmt->execute()) {
            echo '<div style="color: white; background-color: #0056b0; padding: 10px; text-align: center; border-radius: 5px;">Your new password has been updated successfully.</div>';

        } else {
            echo "Error resetting password. Please try again.";
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
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }
        .form-container label {
            font-weight: 600;
            color: #495057;
        }
        .form-container button {
            width: 100%;
            font-size: 16px;
        }
        .form-container a {
            text-decoration: none;
            color: #fff;
        }
        .form-container a:hover {
            text-decoration: underline;
        }
        .form-footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body class="body-home" style="background-color: #343a40;">
    <div class="form-container">
        <h1>Reset Password</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="new_password" class="form-label">Enter new password:</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required placeholder="Enter new password">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm new password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required placeholder="Confirm new password">
            </div>
            <button type="submit" class="btn btn-primary mb-3"><a href="login.php">Reset Password</a></button>
        </form>
        <div class="form-footer">
            <button class="btn btn-secondary">
                <a href="login.php">Login</a>
            </button>
        </div>
    </div>
</body>
</html>

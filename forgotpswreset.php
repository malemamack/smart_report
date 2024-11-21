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
        echo "No matching token found or token has expired.";
        exit;  // Stop further execution if token is invalid or expired
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
            echo "Your new password has been updated successfully.";
        } else {
            echo "Error resetting password. Please try again.";
        }
    }
}
?>


<form method="POST">
    <label for="new_password">Enter new password:</label><br>
    <input type="password" name="new_password" id="new_password" required placeholder="Enter new password"/><br><br>
    
    <label for="confirm_password">Confirm new password:</label><br>
    <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm new password"/><br><br>
    
    <button type="submit">Reset Password</button>
    <button><a href="login.php">login</a></button>
</form>

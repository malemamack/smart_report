<?php
session_start();
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "smart_report"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database and clear the reset token
    $update_sql = "UPDATE teacher SET password = ?, reset_token = NULL WHERE reset_token = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $hashed_password, $token);

    if ($stmt->execute()) {
        echo "Password has been reset successfully!";
    } else {
        echo "Error updating password.";
    }

    $stmt->close();
}

$conn->close();
?>

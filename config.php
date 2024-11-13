<?php
// Database configuration settings
$host = "localhost";        // Database host (usually "localhost")
$db_name = "sms_db";        // Database name
$username = "root";         // Database username
$password = "";             // Database password

// Optional settings
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enables exception mode for error handling
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Sets default fetch mode to associative array
    PDO::ATTR_EMULATE_PREPARES => false,         // Ensures use of real prepared statements
];

try {
    // Establish a connection to the database
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password, $options);
    // Uncomment the line below if you want to verify successful connection
    // echo "Connected successfully";
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
    exit; // Stop further execution if connection fails
}
?>

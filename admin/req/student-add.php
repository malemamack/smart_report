<?php
session_start();

// Check if the admin is logged in and has the right role
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
    
    // Include necessary files for DB connection
    include "../../DB_connection.php";  // Adjust path if necessary
    
    // Collect and sanitize form data
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    
    $contact = $_POST['contact'] ?? '';
    $address = $_POST['address'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $email_address = $_POST['email_address'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $id_number = $_POST['id_number'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $section = $_POST['section'] ?? '';
    
    $parent_id = $_POST['parent_id'];

    // Error check: if any required fields are missing
    if (empty($fname) || empty($lname) || empty($contact) || empty($address) || empty($gender) || empty($email_address) || empty($date_of_birth) || empty($id_number) || empty($grade) || empty($section) || empty($parent_id)) {
        $em = "Please fill in all fields.";
        header("Location: ../student-add.php?error=$em");
        exit;
    }

    // Prepare SQL query to insert data into the database
    $sql = "INSERT INTO students (contact, fname, lname, grade, section, address, gender, email_address, date_of_birth, id_number,parent_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([ $contact, $fname, $lname, $grade, $section, $address, $gender, $email_address, $date_of_birth, $parent_id, $id_number]);

        // Redirect with success message
        $sm = "New student registered successfully!";
        header("Location: ../student-add.php?success=$sm");
        exit;
    } catch (PDOException $e) {
        // If an error occurs during the insertion, show an error message
        $error_message = "Error: " . $e->getMessage();
        header("Location: ../student-add.php?error=$error_message");
        exit;
    }
} else {
    // Redirect if the user is not logged in or doesn't have admin role
    header("Location: ../login.php");
    exit;
}
?>

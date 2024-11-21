<?php
session_start();

// Verify admin session and role
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
    if (
        isset($_POST['student_id']) &&
        isset($_POST['fname']) &&
        isset($_POST['lname']) &&
        
        isset($_POST['contact']) &&
        isset($_POST['address']) &&
        isset($_POST['email_address']) &&
        isset($_POST['gender']) &&
        isset($_POST['date_of_birth']) &&
        isset($_POST['section']) &&
        isset($_POST['id_number']) &&
        isset($_POST['parent_id']) &&
        isset($_POST['grade'])
    ) {
        // Include database connection
        include '../../DB_connection.php';

        // Sanitize and assign input values
        $student_id = trim($_POST['student_id']);
        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);

        $contact = trim($_POST['contact']);
        $address = trim($_POST['address']);
        $email_address = trim($_POST['email_address']);
        $gender = trim($_POST['gender']);
        $date_of_birth = trim($_POST['date_of_birth']);
        $section = trim($_POST['section']);
        $id_number = trim($_POST['id_number']);
        $parent_id = trim($_POST['parent_id']);
        $grade = trim($_POST['grade']);

        $data = "contact=$contact"; // Retain contact for redirection purposes

        // Validate required fields
        if (empty($fname)) {
            $em = "First name is required.";
            header("Location: ../student-edit.php?error=$em&$data");
            exit;
        }
        if (empty($lname)) {
            $em = "Last name is required.";
            header("Location: ../student-edit.php?error=$em&$data");
            exit;
        }
        if (empty($email_address) || !filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $em = "Valid email address is required.";
            header("Location: ../student-edit.php?error=$em&$data");
            exit;
        }

        // Add more validation as needed...

        // Update record in the database
        try {
            $sql = "UPDATE students 
                    SET  fname = ?, lname = ?, grade = ?, address = ?, gender = ?, section = ?, 
                        email_address = ?, date_of_birth = ?, parent_id = ?, id_number = ?, contact = ? 
                    WHERE student_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
    
                $fname,
                $lname,
                $grade,
                $address,
                $gender,
                $section,
                $email_address,
                $date_of_birth,
                $parent_id,
                $id_number,
                $contact,
                $student_id
            ]);

            // Redirect with success message
            $sm = "Successfully updated!";
            header("Location: ../student.php?success=$sm&$data");
            exit;
        } catch (PDOException $e) {
            // Handle errors during the update
            $em = "Error updating record: " . $e->getMessage();
            header("Location: ../student-edit.php?error=$em&$data");
            exit;
        }
    } else {
        // Redirect if required POST parameters are missing
        $em = "An error occurred. Please try again.";
        header("Location: ../student.php?error=$em");
        exit;
    }
} else {
    // Redirect to logout if not logged in or role mismatch
    header("Location: ../../logout.php");
    exit;
}
?>

<?php
session_start();

// Include PHPMailer classes at the top
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Adjust the path if necessary

if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {

        if (isset($_POST['fname']) &&
            isset($_POST['lname']) &&
            isset($_POST['username']) &&
            isset($_POST['pass']) &&
            isset($_POST['address']) &&
            isset($_POST['employee_number']) &&
            isset($_POST['phone_number']) &&
            isset($_POST['qualification']) &&
            isset($_POST['email_address']) &&
            isset($_POST['classes']) &&
            isset($_POST['date_of_birth']) &&
            isset($_POST['subjects'])) {

            include '../../DB_connection.php';
            include "../data/teacher.php";

            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $uname = $_POST['username'];
            $pass = $_POST['pass'];
            $address = $_POST['address'];
            $employee_number = $_POST['employee_number'];
            $phone_number = $_POST['phone_number'];
            $qualification = $_POST['qualification'];
            $email_address = $_POST['email_address'];
            $gender = $_POST['gender'];
            $date_of_birth = $_POST['date_of_birth'];

            $classes = "";
            foreach ($_POST['classes'] as $class) {
                $classes .= $class;
            }

            $subjects = "";
            foreach ($_POST['subjects'] as $subject) {
                $subjects .= $subject;
            }

            $data = 'uname=' . $uname . '&fname=' . $fname . '&lname=' . $lname . '&address=' . $address . '&en=' . $employee_number . '&pn=' . $phone_number . '&qf=' . $qualification . '&email=' . $email_address;

            if (empty($fname)) {
                $em = "First name is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($lname)) {
                $em = "Last name is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($uname)) {
                $em = "Username is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (!unameIsUnique($uname, $conn)) {
                $em = "Username is taken! try another";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($pass)) {
                $em = "Password is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($address)) {
                $em = "Address is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($employee_number)) {
                $em = "Employee number is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($phone_number)) {
                $em = "Phone number is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($qualification)) {
                $em = "Qualification is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($email_address)) {
                $em = "Email address is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($gender)) {
                $em = "Gender address is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($date_of_birth)) {
                $em = "Date of birth address is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else {
                // hashing the password
                $pass = password_hash($pass, PASSWORD_DEFAULT);

                // Insert the teacher data
                $sql  = "INSERT INTO teachers(username, password, class, fname, lname, subjects, address, employee_number, date_of_birth, phone_number, qualification, gender, email_address) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$uname, $pass, $classes, $fname, $lname, $subjects, $address, $employee_number, $date_of_birth, $phone_number, $qualification, $gender, $email_address]);

                // Prepare email content
                $to = $email_address;
                $subject = "Your Account Details";
                $message = "Hello $fname $lname,\n\n";
                $message .= "Your account has been created successfully. Here are your login details:\n";
                $message .= "Username: $uname\n";
                $message .= "Password: $pass\n\n";
                $message .= "Regards,\n";
                $message .= "Your School Admin";

                // Initialize PHPMailer
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'malemamahlatse70@gmail.com'; // Your email
                    $mail->Password = 'cdbhkiurykowykqw'; // Your SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    //Recipients
                    $mail->setFrom('malemamahlatse70@gmail.com', 'School Admin');
                    $mail->addAddress($to, "$fname $lname $pass");

                    // Content
                    $mail->isHTML(false);
                    $mail->Subject = $subject;
                    $mail->Body = $message;

                    // Send email
                    if ($mail->send()) {
                        echo 'Message has been sent';
                    } else {
                        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
                    }
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                // Success message
                $sm = "New teacher registered successfully";
                header("Location: ../teacher-add.php?success=$sm");
                exit;
            }
        } else {
            $em = "An error occurred";
            header("Location: ../teacher-add.php?error=$em");
            exit;
        }
    } else {
        header("Location: ../../logout.php");
        exit;
    }
} else {
    header("Location: ../../logout.php");
    exit;
}

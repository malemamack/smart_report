<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require '../../vendor/autoload.php'; // Update the path if necessary

session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
    	
        if (isset($_POST['fname']) &&
            isset($_POST['lname']) &&
            isset($_POST['username']) &&
            isset($_POST['pass'])     &&
            isset($_POST['address'])  &&
            
            isset($_POST['phone_number'])  &&

            isset($_POST['email_address']) &&
            isset($_POST['date_of_birth'])) {
            isset($_POST['id_number'])  &&
            include '../../DB_connection.php';
            include "../data/parent.php";

            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $uname = $_POST['username'];
            $pass = $_POST['pass'];

            $address = $_POST['address'];
            
            $phone_number = $_POST['phone_number'];
            $id_number = $_POST['id_number'];
            $email_address = $_POST['email_address'];
            $gender = $_POST['gender'];
            $date_of_birth = $_POST['date_of_birth'];

            $data = 'uname='.$uname.'&fname='.$fname.'&lname='.$lname.'&address='.$address.'&id_number='.$id_number.'&pn='.$phone_number.'&email='.$email_address;

            if (empty($fname) || empty($lname) || empty($uname) || empty($pass) || 
                empty($address)  || empty($phone_number) || empty($email_address) || empty($gender) ||  empty($id_number) ||
                empty($date_of_birth)) {
                $em = "All fields are required!";
                header("Location: ../parent-add.php?error=$em&$data");
                exit;
            }

            if (!unameIsUnique($uname, $conn)) {
                $em = "Username is taken! Try another.";
                header("Location: ../parent-add.php?error=$em&$data");
                exit;
            }

            // Hash the password
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // Insert the new parent
            $sql = "INSERT INTO parent (username, password, fname, lname, address, date_of_birth, phone_number, id_number,gender, email_address)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$uname, $hashed_pass, $fname, $lname, $address, $date_of_birth ,$id_number, $phone_number, $gender, $email_address]);

            // Send email with login details
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'malemamahlatse70@gmail.com'; // Replace with your email
                $mail->Password = 'cdbhkiurykowykqw'; // Replace with your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('malemamahlatse70@gmail.com', 'School Admin'); // Replace with your email and name
                $mail->addAddress($email_address, $fname . ' ' . $lname);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your Login Details';
                $mail->Body = "<h4>Dear $fname $lname,</h4>
                               <p>Your account has been created successfully. Here are your login details:</p>
                               <p><strong>Username:</strong> $uname</p>
                               <p><strong>Password:</strong> $pass</p>
                               <p>Please log in and update your password at your earliest convenience.</p>
                               <p>Best Regards,<br>School Admin Team</p>";

                $mail->send();
                $sm = "New parent registered successfully, and login details have been sent!";
                header("Location: ../parent.php?success=$sm");
                exit;
            } catch (Exception $e) {
                $em = "Parent registered, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                header("Location: ../parent-add.php?error=$em");
                exit;
            }
        } else {
            $em = "An error occurred. Please fill in all fields.";
            header("Location: ../parent-add.php?error=$em");
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

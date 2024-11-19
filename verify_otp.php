<?php

session_start();
 
// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
 
// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['otp'])) {
    $enteredOtp = $_POST['otp'];

    // Ensure OTP is set in the session
    if (isset($_SESSION['otp']) && $enteredOtp == $_SESSION['otp']) {
        $role = $_SESSION['role'];
        $id = $_SESSION['temp_user_id'];
    // Ensure OTP is set in the session
    if (isset($_SESSION['otp']) && $enteredOtp == $_SESSION['otp']) {
        $role = $_SESSION['role'];
        $id = $_SESSION['temp_user_id'];

        // Set the appropriate session variables based on the role
        if ($role == 'Admin') {
            // Set admin session variable
            $_SESSION['admin_id'] = $id;
            header("Location: admin/index.php");
            exit;
        } elseif ($role == 'Parent') {
            // Set parent session variable
            $_SESSION['parent_id'] = $id;   // Ensure r_user_id is set correctly
            header("Location: parent/index.php"); // Redirect to parent dashboard
            exit;
        } elseif ($role == 'Teacher') {
            // Set teacher session variable
            $_SESSION['teacher_id'] = $id;
            header("Location: teacher/index.php");
            exit;
        }

        // Clear OTP and temporary user ID after successful verification
        unset($_SESSION['otp']);
        unset($_SESSION['temp_user_id']);
    } else {
        $error = "Invalid OTP. Please try again.";
    }
        // Set the appropriate session variables based on the role
        if ($role == 'Admin') {
            // Set admin session variable
            $_SESSION['admin_id'] = $id;
            header("Location: admin/index.php");
            exit;
        } elseif ($role == 'Parent') {
            // Set parent session variable
            $_SESSION['parent_id'] = $id;   // Ensure r_user_id is set correctly
            header("Location: parent/index.php"); // Redirect to parent dashboard
            exit;
        } elseif ($role == 'Teacher') {
            // Set teacher session variable
            $_SESSION['teacher_id'] = $id;
            header("Location: teacher/index.php");
            exit;
        }

        // Clear OTP and temporary user ID after successful verification
        unset($_SESSION['otp']);
        unset($_SESSION['temp_user_id']);
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="1.jpg">
</head>
<body>
<div class="d-flex justify-content-center align-items-center flex-column">
<div class="text-center">
    
    			<img src="1.jpg" style="border-radius: 40%;"
    			     width="100" >
    		</div>
    <h2>Enter the OTP sent to your email</h2>
    <form method="post" action="">
        <input type="text" name="otp" required placeholder="Enter OTP">
        <button type="submit" class="btn btn-secondary">Verify OTP</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </div>
    <title>OTP Verification page</title>
</head>
<body>
    <h2>Enter the OTP sent to your email</h2>
    <form method="post" action="">
        <input type="text" name="otp" required placeholder="Enter OTP">
        <button type="submit">Verify OTP</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
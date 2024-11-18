<?php
session_start();
 
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_otp = $_POST['otp'];
    $stored_otp = $_SESSION['otp'];

    // Get role and username from session
    $role = $_SESSION['role'];
    $username = $_SESSION['username'];

    print_r($role); // This will print the role for debugging

    if ($input_otp == $stored_otp) {
        // OTP is correct, redirect to the user’s role-based dashboard
        if ($role == 'Admin') {
            header("Location: admin/index.php");
        } elseif ($role == 'Teacher') {
            header("Location: Teacher/index.php");
        } elseif ($role == 'parent') {
            header("Location: parent/index.php");
        } else {
            // Default redirect if role is not recognized
            header("Location: ../login.php");
        }
        exit;
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - Diopong Primary School</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="1.jpg">
</head>
<body class="body-login">
    <div class="black-fill"><br /> <br />
    	<div class="d-flex justify-content-center align-items-center flex-column">
    	

    		<div class="text-center">
    			<img src="1.jpg"
    			     width="100" style="border-radius: 50%;">
    		</div>
    		<div class="container">
        <h3 class="text-center">Verify OTP</h3>
        
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php } ?>

        <div class="d-flex justify-content-center align-items-center flex-column"><form method="post" class="login" >
            <div class="mb-3">
                <label class="form-label">OTP</label>
                <input type="text" class="form-control" name="otp"
                
                    required 
                    maxlength="6" 
                    minlength="6"
                    pattern="\d{6}" 
                    autocomplete="off"
                    placeholder="Enter 6-digit OTP"
                    inputmode="numeric">
                    
            </div>
            <button type="submit" class="btn btn-primary">Verify OTP</button>
        </form></div>
    </div>
		
        
        <br /><br />
        <div class="text-center text-light">
        	Diopong Primary School
        </div>

    	</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <meta charset="UTF-8">
    <title>OTP Verification</title>
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
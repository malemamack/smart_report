<?php
session_start();

// If OTP or username is not set in the session, redirect to login
if (!isset($_SESSION['otp']) || !isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
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
    <title>OTP Verification - Y School</title>
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
                <input type="text" class="form-control" name="otp" required>
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
</body>
</html>
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
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="1.jpg">
</head>
<body class="body-OTP">
    <div class="black-fill"><br /> <br />
    	<div class="d-flex justify-content-center align-items-center flex-column">

    		<div class="text-center">
    			<img src="1.jpg" style="border-radius: 40%;"
    			     width="100" >
        <h3>Enter the OTP sent to your email</h3>
    <form method="post" action="">
        <input type="text" name="otp" required placeholder="Enter OTP">
        <button type="submit">Verify OTP</button>
    </form>
    </div>
       </div>
           </div>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="1.jpg">
    <style>
        /* Set background color to black */
        body.otp-body {
            background-color: rgba(255,255,255, 0.5);
            height: 100vh;
            margin: 0;
        }

        /* Center the container in the middle of the screen */
        .otp-black-fill {
            background-color: rgba(10, 10, 10, 0.5); /* black background */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Create a white box/container for the OTP content */
        .otp-container {
            background-color: white; /* white background for container */
            padding: 40px;
            border-radius: 10px; /* rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* subtle shadow effect */
            width: 100%;
            max-width: 400px; /* max width of the container */
        }

        /* Style the OTP input field */
        .otp-input {
            font-size: 1.2rem;
            padding: 10px;
        }

        /* Style the OTP title */
        .otp-title {
            font-size: 1.6rem;
            font-weight: bold;
        }

        /* Style the OTP buttons */
        .otp-btn {
            padding: 8px 8px;
        }

        /* Style for OTP error message */
        .otp-error {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body class="otp-body">
    <div class="otp-black-fill">
        <div class="otp-container text-center">
            <div class="otp-logo mb-4">
                <img src="1.jpg" style="border-radius: 40%;" width="100">
            </div>
            <h3 class="otp-title">Enter the OTP sent to your email</h3>
            <form method="post" action="" class="otp-form">
                <div class="mb-3">
                    <input type="text" name="otp" required class="otp-input form-control" placeholder="Enter OTP" maxlength="6">
                </div>
                <div class="d-flex justify-content-center gap-3 otp-buttons">
                    <button type="submit" class="otp-btn btn btn-primary">Verify OTP</button>
                    <button type="button" class="otp-btn btn btn-secondary" onclick="resendOTP()">Resend OTP</button>
                     <a href="login.php" class="otp-btn btn btn-secondary">Back</a>
                </div>
            </form>
            <?php if (isset($error)) echo "<p class='otp-error'>$error</p>"; ?>
        </div>
    </div>
    
    <script>
        function resendOTP() {
            // Add your resend OTP logic here
            alert("Resend OTP clicked");
        }
    </script>
</body>
</html>


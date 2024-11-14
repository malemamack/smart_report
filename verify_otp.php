<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['otp'])) {
	$enteredOtp = $_POST['otp'];

	if ($enteredOtp == $_SESSION['otp']) {
		$role = $_SESSION['role'];
		$id = $_SESSION['temp_user_id'];

		// Set the appropriate session variable based on role
		if ($role == 'Parent') {
			$_SESSION['r_user_id'] = $id;
			header("Location: parent/index.php");
		} elseif ($role == 'Teacher') {
			$_SESSION['teacher_id'] = $id;
			header("Location: teacher/index.php");
		}
		unset($_SESSION['otp']); // Clear OTP after verification
		unset($_SESSION['temp_user_id']);
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
	<title>OTP Verification</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="1.jpg">
</head>
<body class="body-login">
    <div class="black-fill"><br /> <br />
    	<div class="d-flex justify-content-center align-items-center flex-column">
    	
	<h2 style="color: white;">Enter the OTP sent to your email</h2>
	<form method="post" action="">
		<input type="text" name="otp" required placeholder="Enter OTP">
		<button type="submit" >Verify OTP</button>
	</form>
	<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        
        <br /><br />
        <div class="text-center text-light">
        	Diopong Primary School
        </div>

    	</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
</body>
</html>

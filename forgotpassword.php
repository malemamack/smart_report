<?php
if (isset($_GET['error'])) {
    echo "<p style='color:red;'>".htmlspecialchars($_GET['error'])."</p>";
}

if (isset($_GET['success'])) {
    echo "<p style='color:green;'>".htmlspecialchars($_GET['success'])."</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<<<<<<< HEAD
<body class="body-home">
    <div class="black-fill"><br /> <br />
    	<div class="container">
    	<nav class="navbar navbar-expand-lg bg-light"
    	     id="homeNav">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="#">
		    	<img src="1.jpg" width="50" height="50" >
		    </a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		        <li class="nav-item">
		          <a class="nav-link active" aria-current="page" href="#">Home</a>
		        </li>
		      </ul>
		      <ul class="navbar-nav me-right mb-2 mb-lg-0">
		      	<li class="nav-item">
		          <a class="nav-link" href="login.php">Login</a>
		        </li>
		      </ul>
		  </div>
		    </div>
		</nav>
		<div class="contai">
        <section class="welcome-text d-flex justify-content-center align-items-center flex-column">
		<img src="1.jpg" style="border-radius: 30%;" >
			<form action="forgotpasswordprc.php" method="post">
    <!-- <div class="form-contai"> -->
        <h2>Forgot Password</h2>
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Send">
</form>
</div>
<section class="footer">
    <div class="text-center text-light">
        Copyright &copy; <?=$setting['current_year']?> <?=$setting['school_name']?>. All rights reserved.
    </div>
</section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
=======
<body>
    <h1>Forgot Password</h1>
    <form action="process_forgot_password.php" method="post">
        <label for="email_address">Email Address:</label>
        <input type="email" name="email_address" id="email_address" required>
        <button type="submit">Send Reset Link</button>
    </form>
>>>>>>> 515eecc55affe2af4f2bf8c710a1db370b79f8c3
</body>
</html>

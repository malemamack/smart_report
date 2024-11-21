<?php 
include "DB_connection.php";
include "data/setting.php";
$setting = getSetting($conn);


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to <?=$setting['school_name']?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="logo.png">
	<style>
		.form-contai {
	background-color: grey;
	padding: 30px;
	border-radius: 8px;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	width: 100%;
	max-width: 400px;
}

h2 {
	text-align: center;
	font-size: 24px;
	margin-bottom: 20px;
	color: #333;
}

label {
	font-size: 14px;
	color: #555;
	margin-bottom: 8px;
	display: block;
}

input[type="email"], input[type="submit"] {
	width: 100%;
	padding: 12px;
	margin-bottom: 15px;
	border-radius: 5px;
	border: 1px solid #ddd;
	font-size: 16px;
	color: #333;
	transition: all 0.3s ease-in-out;
}

input[type="email"]:focus {
	border-color: #007bff;
	outline: none;
}

input[type="submit"] {
	background-color: #007bff;
	color: white;
	font-size: 16px;
	border: none;
	cursor: pointer;
	transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
	background-color: #0056b3;
}
	</style>
</head>
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
        <section class="welcome-text d-flex justify-content-center align-items-center flex-column">
        	<div class="contai">
        
			<form action="forgotpasswordprc.php" method="post">
    <div class="form-contai text-center">
	<div class="logo mb-4">
                <img src="1.jpg" style="border-radius: 40%;" width="100">
            </div>
        <h2>Forgot Password</h2>
        
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        
        <input type="submit" value="Send">
    </div>
</form>

<section class="footer">
    <div class="text-center text-light">
        Copyright &copy; <?=$setting['current_year']?> <?=$setting['school_name']?>. All rights reserved.
    </div>
</section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
</body>
</html>





      

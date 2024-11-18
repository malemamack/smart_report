<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
	<title>Login to Diopong Primary School</title>
=======
	<title>Login - Diopong Primary School</title>
>>>>>>> 45c7f0e9355c7e940dca0f49d376fde64cc6073f
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="1.jpg">
</head>
<body class="body-login">
    <div class="black-fill"><br /> <br />
    	<div class="d-flex justify-content-center align-items-center flex-column">
    	<form class="login" 
    	      method="post"
    	      action="req/login.php">

    		<div class="text-center">
    			<img src="1.jpg" style="border-radius: 40%;"
    			     width="100" >
    		</div>
    		<h3>LOGIN</h3>
    		<?php if (isset($_GET['error'])) { ?>
    		<div class="alert alert-danger" role="alert">
			  <?=$_GET['error']?>
			</div>
			<?php } ?>
		  <div class="mb-3">
		    <label class="form-label">Username</label>
		    <input type="text" 
		           class="form-control"
		           name="uname"
				   required
           		minlength="3"
           maxlength="20"
           pattern="[A-Za-z0-9_]{3,20}"
           autocomplete="username">
		  </div>
		  
		  <div class="mb-3">
		    <label class="form-label">Password</label>
		    <input type="password" 
		           class="form-control"
		           name="pass"
				    required
               minlength="3"
               maxlength="20">
		  </div>

		  <div class="mb-3">
		    <label class="form-label">Login As</label>
		    <select class="form-control"
		            name="role">
		    	<option value="1">Admin</option>
		    	<option value="2">Teacher</option>
		    	<option value="4">parent</option>
		    </select>
		  </div>

		  <button type="submit" class="btn btn-primary">Login</button>
		  <a href="forgotpassword.php" class="btn btn-secondary">Forgot Password</a>
		  <a href="index.php" class="btn btn-secondary">Home</a>
		  
		</form>
        
        <br /><br />
        <div class="text-center text-light">
<<<<<<< HEAD
        	  Diopong Primary School.
=======
<<<<<<< HEAD
        	Copyright &copy; 2024 Diopong Primary School. All rights reserved.
=======
        	 2024 Diopong Primary School.
>>>>>>> 45c7f0e9355c7e940dca0f49d376fde64cc6073f
>>>>>>> 2e83ba525b619ca3f1d60e1542002cd6c514bbe7
        </div>

    	</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
</body>
</html>
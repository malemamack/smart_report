<?php 
include "DB_connection.php";
include "data/setting.php";
$setting = getSetting($conn);

$email_address='';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email_address = $_POST['email_address'] ?? '';
  
    // Trim whitespace
    $email_address = trim($email_address);
  
    // Validate the email address
    if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
    } else {
        // Sanitize and process the email address
        $email_address = htmlspecialchars($email_address, ENT_QUOTES, 'UTF-8');
        echo "Email address is valid!";
        // Save to the database or proceed further
    }
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to <?=$setting['school_name']?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="1.png">
    <style>
        .contai {
            max-width: 400px;
	width: 90%;
	background: rgba(255,255,255, 0.5);
	padding: 10px;
	border-radius: 10px;
        }
        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: black;
        }
        label {
            font-size: 14px;
            color: black;
            margin-bottom: 8px;
            display: block;
        }
        input[type="email"] {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
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
       
        #loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .nav-link{
	font-size: 20px;
	font-weight: 500;
	color: white;
	}
    </style>
</head>
<body class="body-home" style="background-image: url(IMG_3108.jpg);">
    <div id="loader" class="d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="black-fill"><br /><br />
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-light" id="homeNav">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="1.jpg" width="50" height="50">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="index.php">Home</a>
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
                            <input type="email" id="email" name="email"
                            value="<?= htmlspecialchars($email_address) ?>"                 
                             pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                             title="Please enter a valid email address (e.g., example@example.com)" required>
                            <input type="submit" value="Send">
                        </div>
                    </form>
                </div>
            </section>
            <section class="footer">
                <div class="text-center text-light">
                    Copyright &copy; <?=$setting['current_year']?> <?=$setting['school_name']?>. All rights reserved.
                </div>
            </section>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            document.getElementById('loader').classList.remove('d-none');
        });
    </script>
</body>
</html>
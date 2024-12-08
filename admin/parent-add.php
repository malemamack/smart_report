<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
      
       include "../DB_connection.php";

       $fname = '';
       $lname = '';
       $uname = '';
       $address = '';
       $pn = '';
       $email = '';

       if (isset($_GET['fname'])) $fname = $_GET['fname'];
       if (isset($_GET['lname'])) $lname = $_GET['lname'];
       if (isset($_GET['uname'])) $uname = $_GET['uname'];
       if (isset($_GET['address'])) $address = $_GET['address'];
       if (isset($_GET['pn'])) $pn = $_GET['pn'];
       if (isset($_GET['email'])) $email = $_GET['email'];


       if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $fname = $_POST['fname'] ?? '';
    
        // Validate to allow only letters and spaces
        if (!preg_match('/^[A-Za-z\s]+$/', $fname)) {
            echo "Invalid input. Only letters and spaces are allowed.";
            // Handle the error (e.g., show a form with an error message)
        } else {
            // Proceed with the sanitized value
            $fname = htmlspecialchars($fname, ENT_QUOTES, 'UTF-8');
            // Save to database or process further
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $lname = $_POST['lname'] ?? '';
  
      // Validate to allow only letters and spaces
      if (!preg_match('/^[A-Za-z\s]+$/', $lname)) {
          echo "Invalid input. Only letters and spaces are allowed.";
          // Handle the error (e.g., show a form with an error message)
      } else {
          // Proceed with the sanitized value
          $fname = htmlspecialchars($lname, ENT_QUOTES, 'UTF-8');
          // Save to database or process further
      }
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';

    // Validate username: only letters, numbers, and underscores, 3-20 characters
    if (!preg_match('/^[A-Za-z0-9_]{5,20}$/@#&', $username)) {
        echo "Invalid username. It must be 5-20 characters long and contain only letters, numbers, and underscores.";
        // Handle the error appropriately
    } else {
        // Sanitize the input before using it
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        // Save to database or process further
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $address = $_POST['address'] ?? '';

  // Trim whitespace
  $address = trim($address);

  // Check if the address is empty
  if (empty($address)) {
      echo "Address is required.";
  } elseif (strlen($address) > 255) {
      echo "Address must be 255 characters or less.";
  } else {
      // Sanitize the input before using or saving it
      $address = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');

      // Save to database or process further
      echo "Address is valid!";
  }
}



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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_number = $_POST['id_number'];
  if (!preg_match("/^\d{13}$/", $id_number)) {
      $error = "ID Number should be exactly 13 digits.";
  } else {
      // Process the form data
  }
}






?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Parent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../1.jpg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
 /* Form Container Styling */
.form-w {
    background: rgba(255, 255, 255, 0.9);
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    max-width: 600px;
    margin: 0 auto;
}

.navbar {
    position: relative; /* or fixed/sticky if you want it to stay at the top */
    z-index: 3; /* Higher than the overlay (z-index: 1) */
}

/* Form Labels and Inputs */
.form-label {
    font-weight: bold;
    font-size: 0.9rem;
    color: #333;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
    padding: 10px;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 5px rgba(13, 110, 253, 0.3);
}

/* Buttons */
.btn {
    font-size: 0.9rem;
    padding: 10px 20px;
    border-radius: 4px;
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    transition: background-color 0.3s, border-color 0.3s;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #00408d;
}

.btn-dark {
    background-color: #343a40;
    border-color: #343a40;
}

.btn-dark:hover {
    background-color: #23272b;
    border-color: #1d2124;
}

/* Background Overlay */
.background-image-container {
    position: relative;
    background-image: url(../admin/3.jpg);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    min-height: 100vh; /* Use min-height instead of height */
    width: 100%;
    overflow-y: auto; /* Allow scrolling */
}

.background-image-container::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 210%;
    background-color: rgba(0, 0, 0, 0.7); /* Overlay color */
    z-index: 1;
}


.content {
    position: relative; /* Ensures it is above the overlay */
    z-index: 2; /* Above the overlay but below the navbar if applicable */
    padding: 20px; /* Add some padding for spacing */
}


/* Input Group Styling */
.input-group {
    display: flex;
    align-items: center;
}

.input-group .form-control {
    flex: 1;
}

.input-group .btn {
    margin-left: 5px;
}

/* Checkbox Groups */
.row-cols-5 .col {
    display: flex;
    align-items: center;
    padding: 5px;
}

.row-cols-5 .col input[type="checkbox"] {
    margin-right: 8px;
}

/* Loader */
#loader {
    display: none;
    text-align: center;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-w {
        padding: 1.5rem;
    }

    .row-cols-5 {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
}

body, html {
    height: 100%;
    overflow: auto; /* Allow scrolling */
}

  </style>
</head>
<body>
<div class="background-image-container">
<div class="content">
    <?php 
        include "inc/navbar.php";
    ?>
    <div class="container mt-5">
        <a href="parent.php" class="btn btn-light">Go Back</a>

        <form method="post" class="shadow p-3 mt-5 form-w" action="req/parent-add.php">
            <h3>Add New Parent User</h3><hr>
            <?php if (isset($_GET['error'])) { ?>
              <div class="alert alert-danger" role="alert">
               <?=$_GET['error']?>
              </div>
            <?php } ?>
            <?php if (isset($_GET['success'])) { ?>
              <div class="alert alert-success" role="alert">
               <?=$_GET['success']?>
              </div>
            <?php } ?>
            <div class="mb-3">
              <label class="form-label">First name</label>
              <input type="text" 
                 class="form-control" 
                 value="<?= htmlspecialchars($parent['fname'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 name="fname" 
                 required pattern="[A-Za-z\s'-]" 
                 maxlength="50" 
                 title="First name can only contain letters & spaces."
                 required>       
            </div>
            <div class="mb-3">
              <label class="form-label">Last name</label>
              <input type="text" 
              class="form-control" 
             value="<?= htmlspecialchars($parent['lname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              name="lname" 
              required pattern="[A-Za-z\s'-]" 
              maxlength="50" 
              title="Last name can only contain letters & spaces."
              required>
            </div>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" 
              class="form-control" 
              value="<?= htmlspecialchars($parent['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              name="username" 
              pattern="[A-Za-z0-9._@$%!-()&]{5,20}" 
              maxlength="20" 
              title="Username must be 5-20 characters long and can only contain letters, numbers, underscores (_), and periods (.)"
              required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group mb-3">
                   <input type="text" 
                   class="form-control" 
                   name="pass" 
                   id="passInput"
                   required>

                  <button class="btn btn-secondary" id="gBtn">Random</button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Address</label>
              <input type="text" 
              class="form-control" 
                 name="address"
                 value="<?= htmlspecialchars($parent['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 name="address"
                 maxlength="255" 
                 required>
            </div>
            <div class="mb-3">
              <label class="form-label">ID Number</label>
              <input type="text" 
                class="form-control" 
                
                name="id_number"
                value="<?= htmlspecialchars($parent['$id_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 pattern="\d{13}" 
                 title="ID Number should be exactly 13 digits."
                 required>
             
            </div>
            <div class="mb-3">
              <label class="form-label">Phone Number</label>
              <input type="text" 
               class="form-control" 
               value="<?= htmlspecialchars($parent['$phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
               name="phone_number"
               required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="text" 
                class="form-control" 
                value="<?=$email?>" 
                name="email_address"
                value="<?= htmlspecialchars($student['$email_parent'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                 title="Please enter a valid email address (e.g., example@example.com)" 
                 required>
            </div>
            <div class="mb-3">
              <label class="form-label">Gender</label><br>
              <input type="radio" value="Male" checked name="gender"> Male
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input type="radio" value="Female" name="gender"> Female
            </div>
            <div class="mb-3">
              <label class="form-label">Date of Birth</label>
              <input type="date" class="form-control" value="" name="date_of_birth">
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
            <div id="loader" class="d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </form>
    </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(7) a").addClass('active');
        });

        function makePass(length) {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+[]{}|;:,.<>?';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++ ) {
              result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            var passInput = document.getElementById('passInput');
            passInput.value = result;
        }

        document.querySelector('form').addEventListener('submit', function(event) {
            document.getElementById('loader').classList.remove('d-none');
        });

        var gBtn = document.getElementById('gBtn');
        gBtn.addEventListener('click', function(e){
            e.preventDefault();
            makePass(12);
        });
    </script>
</body>
</html>
<?php 
  }else {
    header("Location: ../login.php");
    exit;
  } 
}else {
    header("Location: ../login.php");
    exit;
} 
?>

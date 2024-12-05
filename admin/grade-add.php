<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
      
       $grade_code = '';
       $grade = '';

       if (isset($_GET['grade_code'])) $grade_code = $_GET['grade_code'];
       if (isset($_GET['grade'])) $grade = $_GET['grade'];


      // verifying the form in the grade-add //

       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $grade_code = $_POST['grade_code'] ?? '';
    
        // Check if the field is empty
        if (empty($grade_code)) {
            echo "Error: Grade Code is required.";
            exit;
        }
    
        // Validate pattern (alphanumeric only)
        if (!preg_match('/^[A-Za-z0-9]+$/', $grade_code)) {
            echo "Error: Invalid Grade Code. Only letters and numbers are allowed.";
            exit;
        }
    
        echo "Success: Grade Code is valid: " . htmlspecialchars($grade_code);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $grade = $_POST['grade'] ?? '';
      
          // Trim and sanitize the input
          $grade = htmlspecialchars(trim($grade));
      
          // Check if the field is empty
          if (empty($grade)) {
              echo "Error: Grade is required.";
              exit;
          }
      
          // Validate the input format
          if (!preg_match('/^[A-Za-z0-9]+$/', $grade)) {
              echo "Error: Invalid Grade. Only letters, numbers, and spaces are allowed.";
              exit;
          }
      
          echo "Success: Grade is valid: $grade";
      }







    }

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Add Grade</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
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
<div class="content">    <?php 
        include "inc/navbar.php";
     ?>
     <div class="container mt-5">
        <a href="grade.php"
           class="btn btn-light">Go Back</a>

        <form method="post"
              class="shadow p-3 mt-5 form-w" 
              action="req/grade-add.php">
        <h3>Add New Grade</h3><hr>
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
          <label class="form-label">Grade Code</label>
          <input type="text" 
                 class="form-control"
                 value="<?= htmlspecialchars($grade_code) ?>"
                 id="grade_code" 
                 name="grade_code"
                 required 
                 pattern="[A-Za-z0-9]+"
                title="Grade Code must contain only letters and numbers.">
        </div>
        <div class="mb-3">
          <label class="form-label">Grade</label>
          <input type="text" 
                  id="grade"
                 class="form-control"
                 value="<?= htmlspecialchars($grade) ?>" 
                 name="grade"
                 required 
                  pattern="[A-Za-z0-9\s]+" 
                  title="Grade must contain only letters, numbers, and spaces.">
        </div>
      <button type="submit" class="btn btn-primary">Create</button>
     </form>
     </div>
     </div>
     </div>
     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(4) a").addClass('active');
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
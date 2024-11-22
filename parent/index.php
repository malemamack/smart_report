<?php 
session_start();

// Ensure the session variable is checked correctly for the parent user
if (isset($_SESSION['parent_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Parent') { 
      $parent_id = $_SESSION['parent_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../1.jpg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background for better contrast */
        }
        .btn-custom {
            background-color: #0056b3; /* Primary blue */
            color: white; /* White text */
            border: none; /* Remove border */
        }
        .btn-custom:hover {
            background-color: #003d80; /* Darker shade for hover */
            color: #f8f9fa; /* Light text on hover */
        }
        .btn-custom i {
            color: #f8f9fa; /* Icon color */
        }
    </style>
</head>
<body>
    <?php 
        include "inc/navbar.php";
    ?>
    <div class="container mt-5 d-flex justify-content-center">
        <div class="row text-center">
            <a href="#" class="col btn btn-custom m-3 py-3">
                <i class="fa fa-user-plus fs-1" aria-hidden="true"></i><br>
                Dashboard
            </a> 
            <a href="learners.php" class="col btn btn-custom m-3 py-3">
                <i class="fa fa-user fs-1" aria-hidden="true"></i><br>
                Your Learners
            </a> 
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(1) a").addClass('active');
        });
    </script>
</body>
</html>
<?php 
    } else {
        header("Location: ../login.php");
        exit;
    } 
} else {
    header("Location: ../login.php");
    exit;
} 
?>

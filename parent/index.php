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
</head>
<body>
    <?php 
        include "inc/navbar.php";
     ?>
     <div class="container mt-5">
         <div class="container text-center">
             <div class="row row-cols-5">
               <a href="#" 
                  class="col btn btn-dark m-2 py-3">
                 <i class="fa fa-user-plus fs-1" aria-hidden="true"></i><br>
                  Dashboard
               </a> 

               <a href="learners.php" class="col btn btn-dark m-2 py-3">
                 <i class="fa fa-user fs-1" aria-hidden="true"></i><br>
                  Your Learners
               </a> 
               <br> <br> <br>
               <!-- <a href="../logout.php" class="col btn btn-warning m-2 py-3 col-5">
                 <i class="fa fa-sign-out fs-1" aria-hidden="true"></i><br>
                  Logout
               </a>  -->
             </div>
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

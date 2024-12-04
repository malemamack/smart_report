<?php 
      session_start();
      
      // Ensure the session variable is checked correctly for the parent user
      if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) 
          if ($_SESSION['role'] == 'Admin') 
            $parent_id = $_SESSION['admin_id'];
      ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Home</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../1.jpg" >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    .background-image-container {
    position: relative;
    background-image: url(../admin/3.jpg);
    background-size: cover; 
    background-repeat: no-repeat;
    background-position: center; 
    height: 100vh; 
    width: 100%; 
    overflow-y:auto;
}

.background-image-container::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 115%;
    background-color: rgba(0, 0, 0, 0.7); /* Overlay color */
    z-index: 1; /* Ensures the overlay is above the image */
}
.content {
     /* Needed to make it appear above the overlay */
    z-index: 2; /* Places content above the overlay */
   
}

  </style>
</head>
<body>
<div class="background-image-container">
<div class="content">
    <?php 
        include "inc/navbar.php"; // Include navigation bar
    ?>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;margin-right: -80px; ">
        <div class="container  text-center">
            <div class="row row-cols-md-3 row-cols-sm-2 row-cols-1 g-3">
                <a href="teacher.php" class="col btn btn-primary m-2 py-3">
                    <i class="fa fa-user-md fs-1" aria-hidden="true"></i><br>Teachers
                </a> 
                <a href="student.php" class="col btn btn-primary m-2 py-3">
                    <i class="fa fa-graduation-cap fs-1" aria-hidden="true"></i><br>Learners
                </a> 
                <a href="parent.php" class="col btn btn-primary m-2 py-3">
                    <i class="fa fa-home fs-1" aria-hidden="true"></i><br>Parent
                </a> 
                <a href="class.php" class="col btn btn-primary m-2 py-3">
                    <i class="fa fa-cubes fs-1" aria-hidden="true"></i><br>Class
                </a> 
                <a href="grade.php" class="col btn btn-primary m-2 py-3">
                    <i class="fa fa-level-up fs-1" aria-hidden="true"></i><br>Grade
                </a> 
                <a href="course.php" class="col btn btn-primary m-2 py-3">
                    <i class="fa fa-book fs-1" aria-hidden="true"></i><br>Subjects
                </a>
                
                <a href="settings.php" class="col btn btn-primary m-2 py-3">
                    <i class="fa fa-gear fs-1" aria-hidden="true"></i><br>Settings
                </a> 

                <a href="admin-change-password.php" class="col btn btn-primary m-2 py-3">
                    <i class="fa fa-lock fs-1" aria-hidden="true"></i><br>change-password
                </a> 
                
               
            </div>
        </div>
    </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        // Highlight the active link in the navigation bar
        $(document).ready(function() {
            $("#navLinks li:nth-child(1) a").addClass('active');
        });
    </script>
</body>
</html>

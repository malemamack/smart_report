<?php 
session_start();
if (isset($_SESSION['teacher_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Teacher') {
       include "../DB_connection.php";
       include "data/class.php";
       include "data/grade.php";
       include "data/section.php";
       include "data/teacher.php";
       
       $teacher_id = $_SESSION['teacher_id'];
       $teacher = getTeacherById($teacher_id, $conn);
       $classes = getAllClasses($conn);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers - Students</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../1.jpg">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .background-image-container {
    position: relative; /* Needed to position the overlay */
    background-image: url(../2.jpg);
    background-size: cover; /* Ensures the image covers the entire container */
    background-position: center; /* Centers the image */
    height: 100vh; /* Example height, adjust as needed */
    width: 100%; /* Example width, adjust as needed */
    overflow: hidden;
}

.background-image-container::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7); /* Overlay color */
    z-index: 1; /* Ensures the overlay is above the image */
}
.content {
    position: relative; /* Needed to make it appear above the overlay */
    z-index: 2; /* Places content above the overlay */
   
}

  </style>
</head>
<body class="body-login">

  <div class="background-image-container">
    <div class="content">
    <?php 
        include "inc/navbar.php";
        if ($classes != 0) {
     ?>
     <div class="container mt-5">

     <a href="index.php" class="btn btn-light">Go Back</a>

           <div class="table-responsive">
              <table class="table table-bordered mt-3 n-table" style="background-color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size:15px; border-radius: 10px; width:50%; justify-self:center;">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Class</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 0; foreach ($classes as $class ) { 
                      ?>
                  

                      <?php 
                          $classesx = str_split(trim($teacher['class']));
                          $grade  = getGradeById($class['grade'], $conn);
                          $section = getSectioById($class['section'], $conn);
                          $c = $grade['grade_code'].'-'.$grade['grade'].$section['section'];
                          foreach ($classesx as $class_id) {
                               if ($class_id == $class['class_id']) {  $i++; ?>
                            <tr>
                                <th scope="row"><?=$i?></th>
                                <td> <a href="students_of_class.php?class_id=<?=$class['class_id']?>">
                                          <?php echo $c; ?>
                                      </a>   
                            </td>
                          </tr>

                            <?php         
                            }
                          }
                          
                          
                       ?>
                       
                <?php } ?>
                </tbody>
              </table>
           </div>
         <?php }else{ ?>
             <div class="alert alert-info .w-450 m-5" 
                  role="alert">
                Empty!
              </div>
         <?php } ?>
     </div>
     </div> 
     </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(3) a").addClass('active');
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
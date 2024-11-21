<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['student_id'])) {

    if ($_SESSION['role'] == 'Admin') {
      
       include "../DB_connection.php";
       include "data/subject.php";
       include "data/grade.php";
       include "data/student.php";
       include "data/section.php";
       $subjects = getAllSubjects($conn);
       $grades = getAllGrades($conn);
       $sections = getAllsections($conn);
       
       $student_id = $_GET['student_id'];
       $student = getStudentById($student_id, $conn);

       if ($student == 0) {
         header("Location: student.php");
         exit;
       }


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Edit Learner</title>
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
        <a href="student.php"
           class="btn btn-dark">Go Back</a>

        <form method="post"
              class="shadow p-3 mt-5 form-w" 
              action="req/student-edit.php">
        <h3>Edit Learner Info</h3><hr>
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
                 value="<?=$student['fname']?>" 
                 name="fname">
        </div>
        <div class="mb-3">
          <label class="form-label">Last name</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$student['lname']?>"
                 name="lname">
        </div>
        <div class="mb-3">
          <label class="form-label">Address</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$student['address']?>"
                 name="address">
        </div>
        <div class="mb-3">
          <label class="form-label">Email address</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$student['email_address']?>"
                 name="email_address">
        </div>
        <div class="mb-3">
          <label class="form-label">Date of birth</label>
          <input type="date" 
                 class="form-control"
                 value="<?=$student['date_of_birth']?>"
                 name="date_of_birth">
        </div>
        <div class="mb-3">
          <label class="form-label">Gender</label><br>
          <input type="radio"
                 value="Male"
                 <?php if($student['gender'] == 'Male') echo 'checked';  ?> 
                 name="gender"> Male
                 &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio"
                 value="Female"
                 <?php if($student['gender'] == 'Female') echo 'checked';  ?> 
                 name="gender"> Female


                 <label class="form-label">Select Parent</label>
    <select class="form-select" name="parent_id">
        <option value="<?= $parent['parent_id'] ?>">Select Parent</option>
        <?php
        // Assuming $parents is fetched from the database
        if ($parents) {
            foreach ($parents as $parent) {
                echo "<option value='" . $parent['parent_id'] . "'>" . $parent['parent_name'] . "</option>";
            }
        } else {
            echo "<option>No Parents Available</option>";
        }
        ?>
    </select>
        </div>

        
        <input type="text"
                value="<?=$student['student_id']?>"
                name="student_id"
                hidden>

        <div class="mb-3">
          <label class="form-label">Grade</label>
          <div class="row row-cols-5">
            <?php 
            $grade_ids = str_split(trim($student['grade']));
            foreach ($grades as $grade){ 
              $checked =0;
              foreach ($grade_ids as $grade_id ) {
                if ($grade_id == $grade['grade_id']) {
                   $checked =1;
                }
              }
            ?>
            <div class="col">
              <input type="radio"
                     name="grade"
                     <?php if($checked) echo "checked"; ?>
                     value="<?=$grade['grade_id']?>">
                     <?=$grade['grade_code']?>-<?=$grade['grade']?>
            </div>
            <?php } ?>
             
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Section</label>
          <div class="row row-cols-5">
            <?php 
            $section_ids = str_split(trim($student['section']));
            foreach ($sections as $section){ 
              $checked =0;
              foreach ($section_ids as $section_id ) {
                if ($section_id == $section['section_id']) {
                   $checked =1;
                }
              }
            ?>
            <div class="col">
              <input type="radio"
                     name="section"
                     <?php if($checked) echo "checked"; ?>
                     value="<?=$section['section_id']?>">
                     <?=$section['section']?>
            </div>
            <?php } ?>
             
          </div>
        </div>
        <br><hr>

        <div class="mb-3">
          <label class="form-label">ID Number</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$student['id_number']?>"
                 name="id_number">
        </div>
        <div class="mb-3">
          <label class="form-label">Contact Number</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$student['contact']?>"
                 name="contact">
        </div>
      

        

      <button type="submit" 
              class="btn btn-primary">
              Update</button>
     </form>

    </div>
     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(3) a").addClass('active');
        });

        function makePass(length) {
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
              result += characters.charAt(Math.floor(Math.random() * 
         charactersLength));

           }
           var passInput = document.getElementById('passInput');
           var passInput2 = document.getElementById('passInput2');
           passInput.value = result;
           passInput2.value = result;
        }

        var gBtn = document.getElementById('gBtn');
        gBtn.addEventListener('click', function(e){
          e.preventDefault();
          makePass(4);
        });
    </script>

</body>
</html>
<?php 

  }else {
    header("Location: student.php");
    exit;
  } 
}else {
	header("Location: student.php");
	exit;
} 

?>
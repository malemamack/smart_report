<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['grade_id'])) {

    if ($_SESSION['role'] == 'Admin') {
      
       include "../DB_connection.php";
       include "data/grade.php";

       $grades = getAllGrades($conn);
       
       $grade_id = $_GET['grade_id'];
       $grades = getGradeById($grade_id, $conn);

       if ($grades == 0) {
         header("Location: grade.php");
         exit;


         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $grade_code = $_POST['grade_code'] ?? '';
      
          // Trim and sanitize input
          $grade_code = htmlspecialchars(trim($grade_code));
      
          // Check if the field is empty
          if (empty($grade_code)) {
              echo "Error: Grade Code is required.";
              exit;
          }
      
          // Validate alphanumeric format
          if (!preg_match('/^[A-Za-z0-9]', $grade_code)) {
              echo "Error: Invalid Grade Code. Only letters and numbers are allowed.";
              exit;
          }
      
          // If valid, process the grade code
          echo "Success: Grade Code is valid: $grade_code";          
        }
       }

       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grade = $_POST['grade'] ?? '';

    // Sanitize and trim the input
    $grade = htmlspecialchars(trim($grade));

    // Check if the field is empty
    if (empty($grade)) {
        echo "Error: Grade is required.";
        exit;
    }

    // Validate the format (alphanumeric with spaces)
    if (!preg_match('/^[A-Za-z0-9\s]+$/', $grade)) {
        echo "Error: Invalid Grade. Only letters, numbers, and spaces are allowed.";
        exit;
    }

    echo "Success: Grade is valid: $grade";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $grade = $_POST['grade'] ?? '';

  // Sanitize and trim the input
  $grade = htmlspecialchars(trim($grade));

  // Check if the field is empty
  if (empty($grade)) {
      echo "Error: Grade is required.";
      exit;
  }

  // Validate the format (alphanumeric with spaces)
  if (!preg_match('/^[A-Za-z0-9\s]+$/', $grade)) {
      echo "Error: Invalid Grade. Only letters, numbers, and spaces are allowed.";
      exit;
  }

  echo "Success: Grade is valid: $grade";
}


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Edit Grade</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php 
        include "inc/navbar.php";
     ?>
     <div class="container mt-5">
        <a href="grade.php"
           class="btn btn-dark">Go Back</a>

        <form method="post"
              class="shadow p-3 mt-5 form-w" 
              action="req/grade-edit.php">
        <h3>Edit Grade</h3><hr>
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
                 value="<?= htmlspecialchars($grades['grade_code'] ?? '') ?>" 
                 name="grade_code"
                 required 
                 pattern="[A-Za-z0-9]+" 
                 title="Grade Code must contain only letters and numbers.">
        </div>
        <div class="mb-3">
          <label class="form-label">Grade</label>
          <input type="text" 
                 class="form-control"
                 id="grade"
                 value="<?= htmlspecialchars($grades['grade'] ?? '') ?>" 
                 name="grade"
                 required 
                 pattern="[A-Za-z0-9\s]+" 
                 title="Grade must contain only letters, numbers, and spaces.">
        </div>
        <input type="text" 
                 class="form-control"
                 value="<?=$grades['grade_id']?>"
                 name="grade_id"
                 hidden>

      <button type="submit" 
              class="btn btn-primary">
              Update</button>
     </form>
     
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
    header("Location: grade.php");
    exit;
  } 
}else {
	header("Location: grade.php");
	exit;
} 

?>
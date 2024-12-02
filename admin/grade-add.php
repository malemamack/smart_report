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
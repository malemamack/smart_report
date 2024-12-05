<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    isset($_GET['teacher_id'])) {

    if ($_SESSION['role'] == 'Admin') {
      
        include "../DB_connection.php";
        include "data/subject.php";
        include "data/grade.php";
        include "data/section.php";
        include "data/class.php";
        include "data/teacher.php";

        $subjects = getAllSubjects($conn);
        $classes  = getAllClasses($conn);

        $teacher_id = $_GET['teacher_id'];
        $teacher = getTeacherById($teacher_id, $conn);

        if ($teacher == 0) {
            header("Location: teacher.php");
            exit;
        }

        // Initialize the email address variable
        $email_address = $teacher['email_address'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Edit Teacher</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../1.jpg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    <div class="container mt-5">
        <a href="teacher.php" class="btn btn-dark">Go Back</a>
        <form method="post"
              class="shadow p-3 mt-5 form-w" 
              action="req/teacher-add.php">
        <h3>Edit New Teacher</h3><hr>
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
                 id="fname"
                 value="<?= htmlspecialchars($teacher['fname'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 name="fname"
                 pattern="[A-Za-z\s]+" 
                 title="Please enter only letters" 
                 maxlength="100"
                 required>
        </div>
        <div class="mb-3">
          <label class="form-label">Last name</label>
          <input type="text" 
                 class="form-control"
                 id="lname"
                 value="<?= htmlspecialchars($teacher['lname'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 name="lname"
                 pattern="[A-Za-z\s]+" 
                 title="Please enter only letters" 
                 maxlength="100"
                 required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" 
                 class="form-control"
                 id="uname"
                 value="<?= htmlspecialchars($teacher['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 name="username"
                 pattern="^[A-Za-z0-9_]{5,20}$" 
                 title="Username must be 5-20  characters long and can  contain only letters, numbers,  and underscores." 
                 maxlength="20" 
                 required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group mb-3">
              <input type="text" 
                     class="form-control"
                     name="pass"
                     id="passInput">
              <button class="btn btn-secondary"
                      id="gBtn">
                      Random</button>
          </div>
          
        </div>
        <div class="mb-3">
          <label class="form-label">Address</label>
          <input type="text" 
                 class="form-control"
                 id="address"
                  value="<?= htmlspecialchars($teacher['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 name="address"
                 maxlength="255" 
                 required>
        </div>
        <div class="mb-3">
          <label class="form-label">Phone Number</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$pn??''?>"
                 name="phone_number"
                 id="phone numbers" 
                 value="<?= htmlspecialchars($teacher['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 name="phone_number"
                
                maxlength="10" 
                required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$email??''?>"
                 name="email_address"
                 value="<?= htmlspecialchars($email_address) ?>" 
                 pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                 title="Please enter a valid email address (e.g., example@example.com)" 
                 required>
        </div>
        <div class="mb-3">
          <label class="form-label">Gender</label><br>
          <input type="radio"
                 value="Male"
                 checked 
                 name="gender"> Male
                 &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio"
                 value="Female"
                 name="gender"> Female
        </div>
        <div class="mb-3">
          <label class="form-label">Date of Birth</label>
          <input type="date" 
                 class="form-control"
                 value=""
                 name="date_of_birth">
        </div>
        <div class="mb-3">
          <label class="form-label">Subject</label>
          <div class="row row-cols-5">
            <?php foreach ($subjects as $subject): ?>
            <div class="col">
              <input type="checkbox"
                     name="subjects[]"
                     value="<?=$subject['subject_id']?>">
                     <?=$subject['subject']?>
            </div>
            <?php endforeach ?>
             
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Class</label>
          <div class="row row-cols-5">
            <?php foreach ($classes as $class): ?>
            <div class="col">
              <input type="checkbox"
                     name="classes[]"
                     value="<?=$class['class_id']?>">
                     <?php 
                        $grade = getGradeById($class['grade'], $conn); 
                        $section = getSectioById($class['section'], $conn); 
                      ?>
                     <?=$grade['grade_code']?>-<?=$grade['grade'].$section['section']?>
            </div>
            <?php endforeach ?>
             
          </div>
        </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <div id="loader" class="d-none">
          <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
          </div>
      </div>
     </form>   
     </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
    } else {
        header("Location: teacher.php");
        exit;
    }
} else {
    header("Location: teacher.php");
    exit;
}
?>

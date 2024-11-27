<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])     &&
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
  $date_of_birth = $_POST['date_of_birth'] ?? '';

  // Validate the date format
  if (strtotime($date_of_birth) === false) {
      echo "Invalid date format.";
  } else {
      // Ensure the user is at least 16 years old
      $current_date = time();
      $min_age_date = strtotime('-16 years');

      if (strtotime($date_of_birth) > $min_age_date) {
          echo "You must be at least 16 years old.";
      } else {
          // Process the valid date
          $date_of_birth = htmlspecialchars($date_of_birth, ENT_QUOTES, 'UTF-8');
          // Save to database or process further
          echo "Date of birth is valid!";
      }
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
  $phone_number = $_POST['phone_number'] ?? '';

  // Remove whitespace and sanitize input
  $phone_number = trim($phone_number);

  // Validate South African phone number format
  if (!preg_match('/^\+?27[0-9]{9}$/', $phone_number)) {
      echo "Invalid phone number. It must start with 0 or +27 and contain 9 digits.";
  } else {
      // Sanitize and process the phone number
      $phone_number = htmlspecialchars($phone_number, ENT_QUOTES, 'UTF-8');
      echo "Phone number is valid!";
      // Save to the database or proceed further
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $phone_number = $_POST['phone_number'] ?? '';

  // Remove whitespace and sanitize input
  $phone_number = trim($phone_number);

  // Validate South African phone number format
  if (!preg_match('/^\+?27,0[0-9]{9}$/', $phone_number)) {
      echo "Invalid phone number. It must start with 0 or 0 and contain 9 digits.";
  } else {
      // Sanitize and process the phone number
      $phone_number = htmlspecialchars($phone_number, ENT_QUOTES, 'UTF-8');
      echo "Phone number is valid!";
      // Save to the database or proceed further
  }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $date_of_birth = $_POST['date_of_birth'] ?? '';

  // Check if date is valid
  if (strtotime($date_of_birth) === false) {
      echo "Invalid date format.";
  } else {
      // Ensure the date is within the valid range
      $min_date = strtotime('1900-01-01');
      $max_date = strtotime('-18 years'); // Adjust based on your requirements
      $input_date = strtotime($date_of_birth);

      if ($input_date < $min_date || $input_date > $max_date) {
          echo "Date of birth must be between 1900-01-01 and " . date('Y-m-d', $max_date) . ".";
      } else {
          // Process the valid date
          $date_of_birth = htmlspecialchars($date_of_birth, ENT_QUOTES, 'UTF-8');
          // Save to database or process further
      }
  }
}



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
    <?php 
        include "inc/navbar.php";
     ?>
     <div class="container mt-5">
        <a href="teacher.php"
           class="btn btn-dark">Go Back</a>

        <form method="post"
              class="shadow p-3 mt-5 form-w" 
              action="req/teacher-edit.php">
        <h3>Edit Teacher</h3><hr>
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
                 title="Username must be 5-20  characters long         and can  contain only letters, numbers,  and         underscores." 
                 maxlength="20" 
                 required>
        </div>
        <div class="mb-3">
          <label class="form-label">address</label>
          <input type="text" 
                 class="form-control"
                 id="address"
                 value="<?= htmlspecialchars($teacher['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 name="address"
                 maxlength="255" 
                 required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Date of birth</label>
          <input type="date" 
                 class="form-control"
                 id="date_of_birth" 
        class="form-control" 
        value="<?= htmlspecialchars($teacher['date_of_birth'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
        name="date_of_birth" 
        min="-01-01" 
        max="<?= date('Y-m-d', strtotime('-18 years')) ?>" 
        required>
        </div>

        <div class="mb-3">
          <label class="form-label">Phone number</label>
          <input type="text" 
                 class="form-control"
                 id="phone numbers" 
                 value="<?= htmlspecialchars($teacher['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 name="phone_number"
                 pattern="^\+?27,0[0-9]{9}$" 
                title="Please enter a valid South African         phone number (e.g., 0831234567 or 0831234567)." 
                maxlength="10" 
                required>
        </div>
       
        <div class="mb-3">
          <label class="form-label">Email address</label>
          <input type="text" 
                 class="form-control"
                 id="email_address" 
                 value="<?= htmlspecialchars($email_address) ?>" 
                 name="email_address" 
                 pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                 title="Please enter a valid email address (e.g., example@example.com)" 
                 required>
        </div>
        <div class="mb-3">
          <label class="form-label">Gender</label><br>
          <input type="radio"
                 value="Male"
                 <?php if($teacher['gender'] == 'Male') echo 'checked';  ?> 
                 name="gender"> Male
                 &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio"
                 value="Female"
                 <?php if($teacher['gender'] == 'Female') echo 'checked';  ?> 
                 name="gender"> Female
        </div>
        <input type="text"
                value="<?=$teacher['teacher_id']?>"
                name="teacher_id"
                hidden>

        <div class="mb-3">
          <label class="form-label">Subject</label>
          <div class="row row-cols-5">
            <?php 
            $subject_ids = str_split(trim($teacher['subjects']));
            foreach ($subjects as $subject){ 
              $checked =0;
              foreach ($subject_ids as $subject_id ) {
                if ($subject_id == $subject['subject_id']) {
                   $checked =1;
                }
              }
            ?>
            <div class="col">
              <input type="checkbox"
                     name="subjects[]"
                     <?php if($checked) echo "checked"; ?>
                     value="<?=$subject['subject_id']?>">
                     <?=$subject['subject']?>
            </div>
            <?php } ?>
             
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Class</label>
          <div class="row row-cols-5">
            <?php 
            $class_ids = str_split(trim($teacher['class']));
            foreach ($classes as $class){ 
              $checked =0;
              foreach ($class_ids as $class_id ) {
                if ($class_id == $class['class_id']) {
                   $checked =1;
                }
              }
              $grade = getGradeById($class['class_id'], $conn);
            ?>
            <div class="col">
              <input type="checkbox"
                     name="classes[]"
                     <?php if($checked) echo "checked"; ?>
                     value="<?=$grade['grade_id']?>">
                     <?=$grade['grade_code']?>-<?=$grade['grade']?>
            </div>
            <?php } ?>
             
          </div>
        </div>

      <button type="submit" 
              class="btn btn-primary">
              Update</button>
     </form>

     
     </div>
     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(2) a").addClass('active');
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
    header("Location: teacher.php");
    exit;
  } 
}else {
	header("Location: teacher.php");
	exit;
} 

?>
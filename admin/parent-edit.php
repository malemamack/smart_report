<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['parent_id'])) {

    if ($_SESSION['role'] == 'Admin') {
      
       include "../DB_connection.php";
       include "data/parent.php";


       
       
       $r_user_id = $_GET['parent_id'];
       $r_user = getR_usersById($r_user_id, $conn);

       if ($r_user == 0) {
         header("Location: parent.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_number = $_POST['id_number'];
  if (!preg_match("/^\d{13}$/", $id_number)) {
      $error = "ID Number should be exactly 13 digits.";
  } else {
      // Process the form data
  }
}


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Edit Parent User</title>
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
        <a href="parent.php"
           class="btn btn-dark">Go Back</a>

        <form method="post"
              class="shadow p-3 mt-5 form-w" 
              action="req/parent-edit.php">
        <h3>Edit Parent User</h3><hr>
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
                 value="<?=$r_user['fname']?>" 
                 name="fname"
                 value="<?= htmlspecialchars($r_user['fname'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 name="fname"
                 pattern="[A-Za-z\s]+" 
                 title="Please enter only letters" 
                 maxlength="100"
                 <?php if (!empty($error)): ?>
                  <div class="error"><?=htmlspecialchars($error)?></div>
                   <?php endif; ?>
                 required>
        </div>
        <div class="mb-3">
          <label class="form-label">Last name</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$r_user['lname']?>"
                 name="lname"
                 pattern="[A-Za-z\s]+" 
                 title="Please enter only letters" 
                 maxlength="100"
                 <?php if (!empty($error)): ?>
                  <div class="error"><?=htmlspecialchars($error)?></div>
                   <?php endif; ?>
                 required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$r_user['username']?>"
                 name="username"
                 id="uname"
                 value="<?= htmlspecialchars($r_user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 name="username"
                 pattern="^[A-Za-z0-9_]{5,20}$" 
                 title="Username must be 5-20  characters long and can  contain only letters, numbers,  and underscores." 
                 maxlength="20" 
                 required>
        </div>
        <div class="mb-3">
          <label class="form-label">address</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$r_user['address']?>"
                 name="address"
                 id="address"
                 value="<?= htmlspecialchars($r_user['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 name="address"
                 maxlength="255" 
                 required>
        </div>


        <div class="mb-3">
          <label class="form-label">ID Number</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$r_user['id_number']??''?>"
                 name="id_number"
                 value="<?= htmlspecialchars($id_number) ?>" 
                 name="id_number"
                 pattern="\d{13}" 
                 title="ID Number should be exactly 13 digits.">
             <?php if (!empty($error)): ?>
             <div class="error"><?= htmlspecialchars($error) ?></div>
             <?php endif; ?>>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Date of birth</label>
          <input type="date" 
                 class="form-control"
                 value="<?=$r_user['date_of_birth']?>"
                 name="date_of_birth">
        </div>
        <div class="mb-3">
          <label class="form-label">Phone number</label>
          <input type="text" 
                 class="form-control"
                 value="<?=$r_user['phone_number']?>"
                 name="phone_number">
        </div>
       
        <div class="mb-3">
          <label class="form-label">Email address</label>
          <input type="text" 
                 class="form-control"
                 id ="email address"
                 value="<?=$r_user['email_address']?>"
                 name="email_address"
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
                 <?php if($r_user['gender'] == 'Male') echo 'checked';  ?> 
                 name="gender"> Male
                 &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio"
                 value="Female"
                 <?php if($r_user['gender'] == 'Female') echo 'checked';  ?> 
                 name="gender"> Female
        </div>
        <input type="text"
                value="<?=$r_user['parent_id']?>"
                name="parent_id"
                hidden>

        

      <button type="submit" 
              class="btn btn-primary">
              Update</button>
     </form>

     
     </div>
     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(7) a").addClass('active');
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
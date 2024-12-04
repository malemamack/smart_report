<?php
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        include "../DB_connection.php";
        include "data/grade.php";
        include "data/section.php";
        include "data/student.php";

        $fname = '';
        $lname = '';
        $address = '';
        $contact = '';
        $email = '';
        $id_number = '';
        
        $grades = getAllGrades($conn);
        $sections = getAllSections($conn);
        $parents = getAllParents($conn); // Fetch parents

        $fname = $lname = $address = $email_address = $id_number = $contact = $parent_id = '';
        if (isset($_GET['parent_id'])) $parent_id = $_GET['parent_id'];
        if (isset($_GET['fnames'])) $fname = $_GET['fname'];
        if (isset($_GET['lname'])) $lname = $_GET['lname'];
        if (isset($_GET['address'])) $address = $_GET['address'];
        if (isset($_GET['email_address'])) $email_address = $_GET['email_address'];
        
        if (isset($_GET['id_number'])) $id_number = $_GET['id_number'];
        if (isset($_GET['contact'])) $contact = $_GET['contact'];


        $errors = [];
        $fname = $lname = $address = $email_address = $id_number = $contact = $parent_id = '';
        $date_of_birth = '';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Sanitize inputs
            $fname = trim($_POST['fname'] ?? '');
            $lname = trim($_POST['lname'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $email_address = trim($_POST['email_address'] ?? '');
            $id_number = trim($_POST['id_number'] ?? '');
            $contact = trim($_POST['contact'] ?? '');
            $parent_id = $_POST['parent_id'] ?? '';
            $date_of_birth = $_POST['date_of_birth'] ?? '';
    
            // Validate inputs
            if (!preg_match('/^[A-Za-z\s]+$/', $fname)) {
                $errors[] = "First name must contain only letters and spaces.";
            }
    
            if (!preg_match('/^[A-Za-z\s]+$/', $lname)) {
                $errors[] = "Last name must contain only letters and spaces.";
            }
    
            if (empty($address)) {
                $errors[] = "Address is required.";
            }
    
            if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }
    
            if (!preg_match("/^\d{13}$/", $id_number)) {
                $errors[] = "ID Number should be exactly 13 digits.";
            }
    
            if (!preg_match("/^\d{10}$/", $contact)) {
                $errors[] = "Contact number must be 10 digits.";
            }
    
            if (empty($parent_id) || $parent_id === '0') {
                $errors[] = "Please select a parent.";
            }
    
            if (empty($date_of_birth)) {
                $errors[] = "Date of birth is required.";
            } else {
                function saveStudent($conn, $fname, $lname, $address, $email, $id_number, $contact, $parent_id, $dob) {
                    try {
                        $sql = "INSERT INTO students (first_name, last_name, address, email, id_number, contact, parent_id, date_of_birth) 
                                VALUES (:fname, :lname, :address, :email, :id_number, :contact, :parent_id, :dob)";
                        
                        $stmt = $conn->prepare($sql);
                
                        // Bind parameters with data types
                        $stmt->bindValue(':fname', $fname, PDO::PARAM_STR);
                        $stmt->bindValue(':lname', $lname, PDO::PARAM_STR);
                        $stmt->bindValue(':address', $address, PDO::PARAM_STR);
                        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
                        $stmt->bindValue(':id_number', $id_number, PDO::PARAM_STR);
                        $stmt->bindValue(':contact', $contact, PDO::PARAM_STR);
                        $stmt->bindValue(':parent_id', $parent_id, PDO::PARAM_INT);
                        $stmt->bindValue(':dob', $dob, PDO::PARAM_STR);
                
                        // Execute query
                        if ($stmt->execute()) {
                            return true; // Success
                        } else {
                            return false; // Failed
                        }
                    } catch (PDOException $e) {
                        // Log the error or return a meaningful message
                        error_log("Database Error: " . $e->getMessage());
                        return false;
                    }
                }
            }
        }
        
    


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Learner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../1.jpg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <style>
 /* Form Container Styling */
.form-w {
    background: rgba(255, 255, 255, 0.9);
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    max-width: 600px;
    margin: 0 auto;
}

.navbar {
    position: relative; /* or fixed/sticky if you want it to stay at the top */
    z-index: 3; /* Higher than the overlay (z-index: 1) */
}

/* Form Labels and Inputs */
.form-label {
    font-weight: bold;
    font-size: 0.9rem;
    color: #333;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
    padding: 10px;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 5px rgba(13, 110, 253, 0.3);
}

/* Buttons */
.btn {
    font-size: 0.9rem;
    padding: 10px 20px;
    border-radius: 4px;
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    transition: background-color 0.3s, border-color 0.3s;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #00408d;
}

.btn-dark {
    background-color: #343a40;
    border-color: #343a40;
}

.btn-dark:hover {
    background-color: #23272b;
    border-color: #1d2124;
}

/* Background Overlay */
.background-image-container {
    position: relative;
    background-image: url(../admin/3.jpg);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    min-height: 100vh; /* Use min-height instead of height */
    width: 100%;
    overflow-y: auto; /* Allow scrolling */
}

.background-image-container::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 210%;
    background-color: rgba(0, 0, 0, 0.7); /* Overlay color */
    z-index: 1;
}


.content {
    position: relative; /* Ensures it is above the overlay */
    z-index: 2; /* Above the overlay but below the navbar if applicable */
    padding: 20px; /* Add some padding for spacing */
}


/* Input Group Styling */
.input-group {
    display: flex;
    align-items: center;
}

.input-group .form-control {
    flex: 1;
}

.input-group .btn {
    margin-left: 5px;
}

/* Checkbox Groups */
.row-cols-5 .col {
    display: flex;
    align-items: center;
    padding: 5px;
}

.row-cols-5 .col input[type="checkbox"] {
    margin-right: 8px;
}

/* Loader */
#loader {
    display: none;
    text-align: center;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-w {
        padding: 1.5rem;
    }

    .row-cols-5 {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
}

body, html {
    height: 100%;
    overflow: auto; /* Allow scrolling */
}

  </style>
</head>
<body>
<div class="background-image-container">
<div class="content">
    <?php include "inc/navbar.php"; ?>
    <div class="container mt-5">
        <a href="student.php" class="btn btn-light">Go Back</a>
        <form method="post" class="shadow p-3 mt-5 form-w" action="req/student-add.php">
            <h3>Add New Learner</h3><hr>
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_GET['error'] ?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?= $_GET['success'] ?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label class="form-label">First name</label>
                <input type="text" class="form-control"  
                id="fname"
                 value="<?= htmlspecialchars($student['fname'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 name="fname"
                 pattern="[A-Za-z\s]+" 
                 title="Please enter only letters" 
                 maxlength="100"
                
                 required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last name</label>
                <input type="text" class="form-control" 
                value="<?= htmlspecialchars($student['lname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 name="lname" pattern="[A-Za-z\s]+" 
                 title="Please enter only letters" 
                 maxlength="100"
                 required>
                
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" 
                 value="<?= htmlspecialchars($student['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 name="address"  
                 maxlength="255" 
                 required>
            </div>
            <div class="mb-3">
    <label class="form-label">Email Address</label>
    <input type="email" 
        class="form-control" 
        name="email_address" 
        value="<?= htmlspecialchars($student['email_address'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
        required>
</div>
            <div class="mb-3">
                <label class="form-label">Date of birth</label>
                <input type="date" 
                class="form-control" 
                name="date_of_birth">
            </div>

            <div class="mb-3">
                <label class="form-label">ID Number</label>
                <input type="text" 
                 class="form-control" 
                 name="id_number"
                 value="<?= htmlspecialchars($student['id_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 name="id_number"
                 pattern="\d{13}" 
                 title="ID Number should be exactly 13 digits.">
             <?php if (!empty($error)): ?>
             <div class="error"><?= htmlspecialchars($error) ?></div>
             <?php endif; ?>  
            </div>
            <div class="mb-3">
    <label class="form-label">Contact Number</label>
    <input type="text" 
        class="form-control" 
        name="contact"
        value="<?= htmlspecialchars($student['contact'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
        pattern="\d{10}" 
        title="Contact number must be exactly 10 digits." 
        required><hr>
            <div class="mb-3">
                <label class="form-label">Gender</label><br>
                <input type="radio" value="Male" checked name="gender"> Male
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="Female" name="gender"> Female
            </div><hr>
         
            
            <div class="mb-3">
                <label class="form-label">Grade</label>
                <div class="row row-cols-5">
                    <?php foreach ($grades as $grade): ?>
                        <div class="col">
                            <input type="radio" 
                            name="grade" 
                            value="<?= $grade['grade_id'] ?>">
                            <?= $grade['grade_code'] ?>-<?= $grade['grade'] ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Section</label>
                <div class="row row-cols-5">
                    <?php foreach ($sections as $section): ?>
                        <div class="col">
                            <input type="radio" 
                             name="section" 
                             value="<?= $section['section_id'] ?>">
                            <?= $section['section'] ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <!-- Parent Selection Dropdown -->
<div class="mb-3">
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

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
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

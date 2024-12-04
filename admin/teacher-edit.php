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
        <a href="teacher.php" class="btn btn-light">Go Back</a>
        <form method="post" class="shadow p-3 mt-5 form-w" action="req/teacher-edit.php">
            <h3>Edit Teacher</h3><hr>

            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php } ?>

            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8') ?>
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
                       title="Username must be 5-20 characters long and can contain only letters, numbers, and underscores." 
                       maxlength="20" 
                       required>
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
                <label class="form-label">Date of birth</label>
                <input type="date" 
                       class="form-control"
                       id="date_of_birth" 
                       value="<?= htmlspecialchars($teacher['date_of_birth'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                       name="date_of_birth" 
                       max="<?= date('Y-m-d', strtotime('-18 years')) ?>" 
                       required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone number</label>
                <input type="text" 
                       class="form-control"
                       id="phone_number"
                       value="<?= htmlspecialchars($teacher['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                       name="phone_number"
                       pattern="^\+?27[0-9]{9}$" 
                       title="Please enter a valid South African phone number (e.g., +27831234567)." 
                       maxlength="12" 
                       required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" 
                       class="form-control"
                       id="email_address" 
                       value="<?= htmlspecialchars($email_address, ENT_QUOTES, 'UTF-8') ?>" 
                       name="email_address" 
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                       title="Please enter a valid email address (e.g., example@example.com)" 
                       required>
            </div>
            <div class="mb-3">
                <label class="form-label">Gender</label><br>
                <input type="radio" value="Male" <?php if ($teacher['gender'] == 'Male') echo 'checked'; ?> name="gender"> Male
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="Female" <?php if ($teacher['gender'] == 'Female') echo 'checked'; ?> name="gender"> Female
            </div>
            <input type="hidden" name="teacher_id" value="<?= $teacher['teacher_id'] ?>">

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
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

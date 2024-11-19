<?php
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        include "../DB_connection.php";
        include "data/grade.php";
        include "data/section.php";
        include "data/student.php";
        
        $grades = getAllGrades($conn);
        $sections = getAllSections($conn);
        $parents = getAllParents($conn); // Fetch parents

        $fname = $lname = $address = $email_address = $preferred_name = $id_number = $contact = '';

        if (isset($_GET['fnames'])) $fname = $_GET['fname'];
        if (isset($_GET['lname'])) $lname = $_GET['lname'];
        if (isset($_GET['address'])) $address = $_GET['address'];
        if (isset($_GET['email_address'])) $email_address = $_GET['email_address'];
        if (isset($_GET['preferred_name'])) $preferred_name = $_GET['preferred_name'];
        if (isset($_GET['id_number'])) $id_number = $_GET['id_number'];
        if (isset($_GET['contact'])) $contact = $_GET['contact'];
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
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    <div class="container mt-5">
        <a href="student.php" class="btn btn-dark">Go Back</a>
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
                <input type="text" class="form-control" value="<?= $fname ?>" name="fname">
            </div>
            <div class="mb-3">
                <label class="form-label">Last name</label>
                <input type="text" class="form-control" value="<?= $lname ?>" name="lname">
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" value="<?= $address ?>" name="address">
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" value="<?= $email_address ?>" name="email_address">
            </div>
            <div class="mb-3">
                <label class="form-label">Date of birth</label>
                <input type="date" class="form-control" name="date_of_birth">
            </div>
            <div class="mb-3">
                <label class="form-label">Gender</label><br>
                <input type="radio" value="Male" checked name="gender"> Male
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="Female" name="gender"> Female
            </div><hr>
            <div class="mb-3">
                <label class="form-label">Preferred Name</label>
                <input type="text" class="form-control" value="<?= $preferred_name ?>" name="preferred_name">
            </div>
            <div class="mb-3">
                <label class="form-label">Id Number</label>
                <input type="text" class="form-control" value="<?= $id_number ?>" name="id_number">
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" class="form-control" value="<?= $contact ?>" name="contact">
            </div><hr>
            <div class="mb-3">
                <label class="form-label">Grade</label>
                <div class="row row-cols-5">
                    <?php foreach ($grades as $grade): ?>
                        <div class="col">
                            <input type="radio" name="grade" value="<?= $grade['grade_id'] ?>">
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
                            <input type="radio" name="section" value="<?= $section['section_id'] ?>">
                            <?= $section['section'] ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <!-- Parent Selection Dropdown -->
<div class="mb-3">
    <label class="form-label">Select Parent</label>
    <select class="form-select" name="parent_id">
        <option value="">Select Parents</option>
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

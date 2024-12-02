<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $teacher_id = $_SESSION['teacher_id'];

    $password_pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/';

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!preg_match($password_pattern, $new_password)) {
        $error = "Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM teachers WHERE teacher_id = ?");
        $stmt->execute([$teacher_id]);
        $user = $stmt->fetch();
        if ($user && password_verify($old_password, $user['password'])) {
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE teachers SET password = ? WHERE teacher_id = ?");
            $stmt->execute([$new_password_hashed, $teacher_id]);

            // Set success message in JavaScript
            echo "<script>
                    alert('Password changed successfully. You will be redirected to the login page.');
                    window.location.href = '../login.php';
                  </script>";
            exit;
        } else {
            $error = "Old password is incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher - Change Password</title>
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
<?php include "inc/navbar.php"; ?>

<a href="index.php" class="btn btn-light">Go Back</a>

<div class="container mt-5" style="background-color: rgba(255, 255, 255, 0.7); padding: 10px ; font-weight: 600; font-size:15px; border-radius: 10px; width:50%;">
    <h3>Change Password</h3><hr>
    <?php if (isset($error)) { ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php } ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Old Password</label>
            <input type="password" class="form-control" name="old_password" required>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" class="form-control" name="new_password" required>
            <small class="form-text text-muted">
                Password must be at least 8 characters long, 
                include an uppercase letter, 
                a lowercase letter, 
                a number, and 
                a special character.
            </small>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
</div>
</div>
</div>
</body>
</html>

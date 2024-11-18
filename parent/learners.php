<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'sms_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start the session
session_start();

// Validate session to ensure parent is logged in
if (!isset($_SESSION['parent_id'])) {
    header("Location: ../login.php");
    exit;
}

// Get the logged-in parent's ID
$parent_id = $_SESSION['parent_id'];

// Fetch learners associated with the parent
$sql = "SELECT student_id, fname, lname, grade, section FROM students WHERE parent_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>parent - Your Learners</title>
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
        <h2 class="text-center">Your Learners</h2>

        <!-- Display Learners -->
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered table-hover mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Grade</th>
                        <th>Section</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $index = 1;
                    while ($row = $result->fetch_assoc()): 
                    ?>
                        <tr>
                            <td><?= $index++; ?></td>
                            <td><?= htmlspecialchars($row['fname']); ?></td>
                            <td><?= htmlspecialchars($row['lname']); ?></td>
                            <td><?= htmlspecialchars($row['grade']); ?></td>
                            <td><?= htmlspecialchars($row['section']); ?></td>
                            <td>
                                <a href="view_results.php?student_id=<?= $row['student_id']; ?>" class="btn btn-info btn-sm">
                                    View Results
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center mt-4">
                No learners found.
            </div>
        <?php endif; ?>

        <!-- Logout Button -->
        <div class="text-center mt-4">
            <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>

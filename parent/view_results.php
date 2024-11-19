<?php
session_start();
if (isset($_SESSION['parent_id'])) {
    // Include database connection
    include "../DB_connection.php"; // Ensure this file contains a valid PDO connection.

    // Fetch score by student_id
    function getScoreById($student_id, $conn) {
        $sql = "SELECT * FROM student_score WHERE student_id = :student_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch subject details by subject_id
    function getSubjectById($subject_id, $conn) {
        $sql = "SELECT * FROM subjects WHERE subject_id = :subject_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch learner's name by student_id
    function getLearnerName($student_id, $conn) {
        $sql = "SELECT fname FROM students WHERE student_id = :student_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['fname']; // Assuming 'name' column exists
    }

    // Grade calculation logic
    function gradeCalc($total) {
        if ($total >= 90) return "A+";
        if ($total >= 80) return "A";
        if ($total >= 70) return "B";
        if ($total >= 60) return "C";
        if ($total >= 50) return "D";
        return "F";
    }

    // Validate student_id from URL
    if (isset($_GET['student_id']) && is_numeric($_GET['student_id'])) {
        $student_id = intval($_GET['student_id']);

        // Fetch learner's name
        $fname = getLearnerName($student_id, $conn);

        // Fetch scores for the student
        $scores = getScoreById($student_id, $conn);

        if ($scores && $fname) {
            ?>
            <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>parent - Home</title>
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
                    <h2 class="text-center">Results for Learner: <?= htmlspecialchars($fname) ?> (ID: <?= htmlspecialchars($student_id) ?>)</h2>
                    <div class="table-responsive" style="width: 90%; max-width: 700px; margin: auto;">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <!-- <th>Course Code</th> -->
                                    <th>Course Title</th>
                                    <th>Results</th>
                                    <th>Total</th>
                                    <th>Grade</th>
                                    <th>Term</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($scores as $score) {
                                    // Get subject details
                                    $subject = getSubjectById($score['subject_id'], $conn);

                                    // Calculate total and grade
                                    $total = 0;
                                    $outOf = 0;
                                    $results = explode(',', trim($score['results']));
                                    foreach ($results as $result) {
                                        list($marks, $max) = explode(' ', trim($result));
                                        $total += intval($marks);
                                        $outOf += intval($max);
                                    }

                                    // Output table row
                                    ?>
                                    <tr>
                                        <!-- <td><?= htmlspecialchars($subject['subject_code']) ?></td> -->
                                        <td><?= htmlspecialchars($subject['subject']) ?></td>
                                        <td>
                                            <?php
                                            foreach ($results as $result) {
                                                list($marks, $max) = explode(' ', trim($result));
                                                echo "<small class='border p-1'>$marks / $max</small>&nbsp;";
                                            }
                                            ?>
                                        </td>
                                        <td><?= "$total / $outOf" ?></td>
                                        <td><?= gradeCalc($total) ?></td>
                                        <td><?= htmlspecialchars($score['semester']) ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "<div class='alert alert-info text-center'>No results found for this learner.</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid Learner ID.</div>";
    }
} else {
    header("Location: ../login.php");
    exit;
}

// Close database connection
$conn = null;
?>

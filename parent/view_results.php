<?php
session_start();
include "../DB_connection.php";

// Function to get the student's scores by ID
function getScoreById($student_id, $current_year, $conn) {
    $sql = "SELECT * FROM student_score WHERE student_id = :student_id AND year = :current_year";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':current_year', $current_year, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get the subject by ID
function getSubjectById($subject_id, $conn) {
    $sql = "SELECT * FROM subjects WHERE subject_id = :subject_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get the learner's name
function getLearnerName($student_id, $conn) {
    $sql = "SELECT fname FROM students WHERE student_id = :student_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['fname'];
}

// Main script execution
if (isset($_GET['student_id']) && is_numeric($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);
    $fname = getLearnerName($student_id, $conn);

    $sql = "SELECT current_year FROM setting";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $year_current = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$year_current) {
        $year_current['current_year'] = date("Y");
    }

    $scores = getScoreById($student_id, $year_current['current_year'], $conn);

    if ($scores && $fname) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../1.jpg">
    <style>
        body {
            background: rgba(0, 0, 0, 0.7) url('../2.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        .table-dark {
            background-color: #0056b3;
            color: #ffffff;
        }
        th {
            background-color: #003d80 !important;
            color: #ffffff !important;
            text-align: center;
        }
        table {
            width: 100%;
            background-color: white;
        }

        body.body-login {
    background-image: url('../2.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    height: 100%;
    padding-bottom: 400px;
}

body.body-login::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7); /* Black overlay with 70% transparency */
    z-index: -1; /* Ensure it sits behind all content */
}

h2{
    color: rgba(255, 255, 255, 0.8);
}
    </style>
</head>
<body class="body-login">
<?php include "inc/navbar.php"; ?>

<a href="index.php" class="btn btn-light">Go Back</a>
<!-- <a href="generate_pdf.php" class="btn btn-light">Download</a>  -->

<div class="container mt-5">
    <h2 class="text-center">Results for Learner: <?= htmlspecialchars($fname) ?> (ID: <?= htmlspecialchars($student_id) ?>)</h2>
    <div class="table-responsive" style="width: 90%; max-width: 900px; margin: auto;">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Subject Title</th>
                    <th>Test 1</th>
                    <th>Test 2</th>
                    <th>Test 3</th>
                    <th>Exam</th>
                    <th>Final Mark</th>
                    <th>Attendance</th>
                    <th>Comment</th>
                    <th>Term</th>
                    <th>Year</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($scores as $score) {
                    $subject = getSubjectById($score['subject_id'], $conn);
                    $results = explode(',', trim($score['results']));
                    $test1 = $test2 = $test3 = $exam = $final_mark = $attendance = $comment = '';
                    foreach ($results as $result) {
                        if (strpos($result, 'Attendance') !== false) {
                            $attendance = explode(': ', trim($result))[1];
                        } elseif (strpos($result, 'Comment') !== false) {
                            $comment = explode(': ', trim($result))[1];
                        } else {
                            list($marks) = explode(', ', trim($result));
                            if (empty($test1)) {
                                $test1 = $marks;
                            } elseif (empty($test2)) {
                                $test2 = $marks;
                            } elseif (empty($test3)) {
                                $test3 = $marks;
                            } elseif (empty($exam)) {
                                $exam = $marks;
                            } elseif (empty($final_mark)) {
                                $final_mark = $marks;
                            }
                        }
                    }
                ?>
                    <tr>
                        <td><?= htmlspecialchars($subject['subject']) ?></td>
                        <td><?= htmlspecialchars($test1) ?></td>
                        <td><?= htmlspecialchars($test2) ?></td>
                        <td><?= htmlspecialchars($test3) ?></td>
                        <td><?= htmlspecialchars($exam) ?></td>
                        <td><?= htmlspecialchars($final_mark) ?></td>
                        <td><?= htmlspecialchars($attendance) ?></td>
                        <td><?= htmlspecialchars($comment) ?></td>
                        <td><?= htmlspecialchars($score['semester']) ?></td>
                        <td><?= htmlspecialchars($year_current['current_year']) ?></td>
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
        echo "<div class='alert alert-info text-center'>No results found for this learner in the current year.</div>";
    }
} else {
    echo "<div class='alert alert-danger text-center'>Invalid Learner ID.</div>";
}

$conn = null;
?>

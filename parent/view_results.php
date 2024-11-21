<?php
session_start();
include "../DB_connection.php";

// Function to get the student's scores by ID
function getScoreById($student_id, $conn) {
    $sql = "SELECT * FROM student_score WHERE student_id = :student_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
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

// Function to calculate the grade
function gradeCalc($total) {
    if ($total >= 80) return "7";
    if ($total >= 70) return "6";
    if ($total >= 60) return "5";
    if ($total >= 50) return "4";
    if ($total >= 40) return "3";
    if ($total >= 30) return "2";
    return "1";
}

// Function to populate the history table
function populateHistory($conn) {
    $sql = "INSERT INTO student_score_history (student_id, subject_id, semester, year, results)
            SELECT student_id, subject_id, semester, year, results FROM student_score";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

// Function to check if the settings have changed
function checkYearChange($current_year, $conn) {
    $sql = "SELECT current_year FROM setting";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $setting = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($current_year != $setting['current_year']) {
        populateHistory($conn);

        $sql = "UPDATE setting SET current_year = :current_year";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':current_year', $current_year, PDO::PARAM_INT);
        $stmt->execute();
    }
}

// Main script execution
if (isset($_GET['student_id']) && is_numeric($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);
    $fname = getLearnerName($student_id, $conn);
    $scores = getScoreById($student_id, $conn);

    if ($scores && $fname) {
        $sql = "SELECT current_year FROM setting";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $year_current = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$year_current) {
            $year_current['current_year'] = date("Y");
        }

        checkYearChange($year_current['current_year'], $conn);
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php include "inc/navbar.php"; ?>
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
                    <th>Total</th>
                    <th>Grade</th>
                    <th>Term</th>
                    <th>Year</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($scores as $score) {
                    $subject = getSubjectById($score['subject_id'], $conn);
                    $total = 0;
                    $outOf = 0;
                    $results = explode(',', trim($score['results']));
                    $test1 = $test2 = $test3 = $exam = $final_mark = $attendance  = $comment = '';
                    foreach ($results as $result) {
                        if (strpos($result, 'Attendance') !== false) {
                            $attendance = explode(': ', trim($result))[1];
                        } elseif (strpos($result, 'Comment') !== false) {
                            $comment = explode(': ', trim($result))[1];
                        } else {
                            list($marks, $max) = explode(': ', trim($result));
                            $total += intval($marks);
                            $outOf += intval($max);
                            if (empty($test1)) {
                                $test1 = "$marks $max";
                            } elseif (empty($test2)) {
                                $test2 = "$marks $max";
                            } elseif (empty($test3)) {
                                $test3 = "$marks $max";
                            } elseif (empty($exam)) {
                                $exam = "$marks $max";
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
                        <td><?= htmlspecialchars("$total / $outOf") ?></td>
                        <td><?= htmlspecialchars(gradeCalc($total)) ?></td>
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
        echo "<div class='alert alert-info text-center'>No results found for this learner.</div>";
    }
} else {
    echo "<div class='alert alert-danger text-center'>Invalid Learner ID.</div>";
}

$conn = null;
?>

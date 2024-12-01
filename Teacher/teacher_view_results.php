<?php
session_start();
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Teacher') {
        include "../DB_connection.php";
        include "data/student.php";
        include "data/grade.php";
        include "data/class.php";
        include "data/section.php";
        include "data/setting.php";
        include "data/subject.php";
        include "data/teacher.php";
        include "data/student_score.php";

        if (!isset($_GET['student_id'])) {
            header("Location: students.php");
            exit;
        }

        $student_id = $_GET['student_id'];
        $teacher_id = $_SESSION['teacher_id'];
        $teacher = getTeacherById($teacher_id, $conn);
        $teacher_subjects = str_split(trim($teacher['subjects']));
        $setting = getSetting($conn);
        
        // Function to get results for a specific student
        function getAllResultsByStudent($student_id, $teacher_subjects, $setting, $conn) {
            $results = [];
            foreach ($teacher_subjects as $subject_id) {
                $sql = "SELECT ss.*, s.fname AS student_fname, s.lname AS student_lname, subj.subject_code 
                        FROM student_score ss
                        JOIN students s ON ss.student_id = s.student_id
                        JOIN subjects subj ON ss.subject_id = subj.subject_id
                        WHERE ss.student_id = :student_id 
                        AND ss.subject_id = :subject_id 
                        AND ss.semester = :semester 
                        AND ss.year = :year";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
                $stmt->bindParam(':semester', $setting['current_semester'], PDO::PARAM_INT);
                $stmt->bindParam(':year', $setting['current_year'], PDO::PARAM_INT);
                $stmt->execute();
                $student_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $results = array_merge($results, $student_results);
            }
            return $results;
        }

        $results = getAllResultsByStudent($student_id, $teacher_subjects, $setting, $conn);

        // Function to calculate grade based on total score
        function gradeCalc($total) {
            if ($total >= 80) {
                return 'A';
            } elseif ($total >= 70) {
                return 'B';
            } elseif ($total >= 60) {
                return 'C';
            } elseif ($total >= 50) {
                return 'D';
            } else {
                return 'F';
            }
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher - View Student Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .table-dark {
            background-color: #0056b3; /* Primary blue for header */
            color: #ffffff; /* White text for readability */
        }
        th {
            background-color: #003d80 !important; /* Darker blue for table header */
            color: #ffffff !important; /* White text */
            border: 1px solid #ffffff; /* Optional: White border for distinction */
            text-align: center; /* Center align header text */
        }
        table {
            border-collapse: collapse; /* Neater table appearance */
            width: 100%;
        }
        .active {
            color: #0056b3 !important;
        }
    </style>
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    <div class="container mt-5">
        <?php if (!empty($results)) { ?>
            <h2 class="text-center">Results for Learner: <?= htmlspecialchars($results[0]['student_fname'] . ' ' . $results[0]['student_lname']) ?> (ID: <?= htmlspecialchars($student_id) ?>)</h2>
            <div class="table-responsive" style="width: 90%; max-width: 900px; margin: auto;">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Subject</th>
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
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($results as $result) {
                            $total = 0;
                            $outOf = 0;
                            $scores = explode(',', trim($result['results']));
                            $test1 = $test2 = $test3 = $exam = $final_mark = $attendance = $comment = '';
                            foreach ($scores as $score) {
                                if (strpos($score, 'Attendance') !== false) {
                                    $attendance = explode(': ', trim($score))[1] ?? '';
                                } elseif (strpos($score, 'Comment') !== false) {
                                    $comment = explode(': ', trim($score))[1] ?? '';
                                } else {
                                    list($marks, $max) = array_pad(explode(': ', trim($score)), 2, null);
                                    $total += intval($marks ?? 0);
                                    $outOf += intval($max ?? 0);
                                    if (empty($test1)) {
                                        $test1 = "$marks / $max";
                                    } elseif (empty($test2)) {
                                        $test2 = "$marks / $max";
                                    } elseif (empty($test3)) {
                                        $test3 = "$marks / $max";
                                    } elseif (empty($exam)) {
                                        $exam = "$marks / $max";
                                    } elseif (empty($final_mark)) {
                                        $final_mark = "$marks / $max";
                                    }
                                }
                            }
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($result['subject_code']) ?></td>
                                <td><?= htmlspecialchars($test1) ?></td>
                                <td><?= htmlspecialchars($test2) ?></td>
                                <td><?= htmlspecialchars($test3) ?></td>
                                <td><?= htmlspecialchars($exam) ?></td>
                                <td><?= htmlspecialchars($final_mark) ?></td>
                                <td><?= htmlspecialchars($attendance) ?></td>
                                <td><?= htmlspecialchars($comment) ?></td>
                                <td><?= htmlspecialchars("$total / $outOf") ?></td>
                                <td><?= htmlspecialchars(gradeCalc($total)) ?></td>
                                <td><?= htmlspecialchars($result['semester']) ?></td>
                                <td><?= htmlspecialchars($result['year']) ?></td>
                                <td><a href="edit_score.php?student_id=<?= $student_id ?>&subject_id=<?= $result['subject_id'] ?>" class="btn btn-secondary btn-sm">Edit</a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class="alert alert-info text-center">No results found for this student.</div>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(4) a").addClass('active');
        });
    </script>
</body>
</html>
<?php
    } else {
        echo "<div class='alert alert-info text-center'>No results found for this student's subjects.</div>";
    }
} else {
    header("Location: ../login.php");
    exit;
}
$conn = null;
?>  

<?php 
session_start();
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role'])) 
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
        $student = getStudentById($student_id, $conn);
        $setting = getSetting($conn);
        $subjects = getSubjectByGrade($student['grade'], $conn);

        $teacher_id = $_SESSION['teacher_id'];
        $teacher = getTeacherById($teacher_id, $conn);
        $teacher_subjects = str_split(trim($teacher['subjects']));

        $ssubject_id = 0;
        $student_score = null;
        
        if (isset($_POST['ssubject_id'])) {
            $ssubject_id = $_POST['ssubject_id'];
            $student_score = getScoreById($student_id, $teacher_id, $ssubject_id, $setting['current_semester'], $setting['current_year'], $conn);
        } else {
            // Default to the first subject if none is selected
            $ssubject_id = $subjects[0]['subject_id'];
            $student_score = getScoreById($student_id, $teacher_id, $ssubject_id, $setting['current_semester'], $setting['current_year'], $conn);
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher - Edit Grade</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-image: url(../2.jpg);">
    <?php 
    include "inc/navbar.php"; 
    if ($student != 0 && $setting != 0 && $subjects != 0 && $teacher_subjects != 0) {
    ?>

    <a href="index.php" class="btn btn-dark">Go Back</a>
    <div class="d-flex align-items-center flex-column"><br><br>
        <div class="login shadow p-3">
        <form method="post" action="">
            <div class="mb-3">
                <ul class="list-group">
                    <li class="list-group-item"><b>ID: </b> <?php echo $student['student_id'] ?></li>
                    <li class="list-group-item"><b>First Name: </b> <?php echo $student['fname'] ?></li>
                    <li class="list-group-item"><b>Last Name: </b> <?php echo $student['lname'] ?></li>
                    <li class="list-group-item"><b>Grade: </b> 
                        <?php  
                        $g = getGradeById($student['grade'], $conn); 
                        echo $g['grade_code'].'-'.$g['grade'];
                        ?>
                    </li>
                    <li class="list-group-item"><b>Section: </b> 
                        <?php  
                        $s = getSectioById($student['section'], $conn); 
                        echo $s['section'];
                        ?>
                    </li>
                    <li class="list-group-item text-center"><b>Year: </b> <?php echo $setting['current_year']; ?> &nbsp;&nbsp;&nbsp;<b>Term</b> <?php echo $setting['current_semester']; ?></li>
                </ul>
            </div>
            
            <h5 class="text-center">Edit Grade</h5>
            
            <!-- Subject Dropdown -->
            <label class="form-label">Subject</label>
            <select class="form-control" name="ssubject_id">
                <?php foreach ($subjects as $subject) { 
                    foreach ($teacher_subjects as $teacher_subject) {
                        if ($subject['subject_id'] == $teacher_subject) { ?>
                            <option <?php if ($ssubject_id == $subject['subject_id']) { echo "selected"; } ?> 
                                value="<?php echo $subject['subject_id'] ?>">
                                <?php echo $subject['subject_code'] ?>
                            </option>
                        <?php } 
                    }
                } ?>
            </select><br>

            <button type="submit" class="btn btn-primary">Select</button><br><br>

        </form>

        <!-- Grade Entry Form -->
        <form method="post" action="req/save-score.php">
            <div class="input-group mb-3">
                <input type="number" min="0" max="300" class="form-control" placeholder="Test 1 Score" name="score-1" value="<?= htmlspecialchars($student_score['test1'] ?? '') ?>" id="score1" oninput="calculateFinalMark()">
                <span class="input-group-text">/</span>
                <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-1" value="<?= htmlspecialchars($student_score['outof_test1'] ?? '') ?>" id="outof1" oninput="calculateFinalMark()">
            </div>
            <div class="input-group mb-3">
                <input type="number" min="0" max="300" class="form-control" placeholder="Test 2 Score" name="score-2" value="<?= htmlspecialchars($student_score['test2'] ?? '') ?>" id="score2" oninput="calculateFinalMark()">
                <span class="input-group-text">/</span>
                <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-2" value="<?= htmlspecialchars($student_score['outof_test2'] ?? '') ?>" id="outof2" oninput="calculateFinalMark()">
            </div>
            <div class="input-group mb-3">
                <input type="number" min="0" max="300" class="form-control" placeholder="Test 3 Score" name="score-3" value="<?= htmlspecialchars($student_score['test3'] ?? '') ?>" id="score3" oninput="calculateFinalMark()">
                <span class="input-group-text">/</span>
                <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-3" value="<?= htmlspecialchars($student_score['outof_test3'] ?? '') ?>" id="outof3" oninput="calculateFinalMark()">
            </div>
            <div class="input-group mb-3">
                <input type="number" min="0" max="300" class="form-control" placeholder="Exam Score" name="score-4" value="<?= htmlspecialchars($student_score['exam'] ?? '') ?>" id="exam" oninput="calculateFinalMark()">
                <span class="input-group-text">/</span>
                <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-4" value="<?= htmlspecialchars($student_score['outof_exam'] ?? '') ?>" id="outof4" oninput="calculateFinalMark()">
            </div>
            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Final Mark (Calculated)" name="score-5" id="finalMark" readonly>
                <span class="input-group-text">%</span>
            </div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Attendance" name="attendance" value="<?= htmlspecialchars($student_score['attendance'] ?? '') ?>">
            </div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Comments" name="comments" value="<?= htmlspecialchars($student_score['comments'] ?? '') ?>">
            </div>
            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>" hidden>
            <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>" hidden>
            <input type="hidden" name="semester" value="<?php echo $setting['current_semester']; ?>" hidden>
            <input type="hidden" name="year" value="<?php echo $setting['current_year']; ?>" hidden>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
        </div>
    </div>
</body>
</html>

<?php
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>

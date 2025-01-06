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

        if (!isset($_GET['student_id']) || !isset($_GET['subject_id'])) {
            header("Location: students.php");
            exit;
        }

        $student_id = $_GET['student_id'];
        $subject_id = $_GET['subject_id'];
        $teacher_id = $_SESSION['teacher_id'];
        $setting = getSetting($conn);

        $student_score = getScoreById($student_id, $teacher_id, $subject_id, $setting['current_semester'], $setting['current_year'], $conn);

        $student = getStudentById($student_id, $conn);
        $subject = getSubjectById($subject_id, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Score</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../1.jpg">
    <style>
    body.body-login {
    background-image: url('../2.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    height: 100%;
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

h2, .form-label{
    color: rgba(255, 255, 255, 0.8);
}

#input{
    width: 50%;
    justify-self:center;
}

#results{
    padding-bottom: 10px;
}

</style>
</head>
<body class="body-login">
    <?php include "inc/navbar.php"; ?>

    <div class="container mt-5" id="results">
        <h2 class="text-center">Edit Results for <?php echo htmlspecialchars($student['fname'] . ' ' . $student['lname']); ?></h2>
        <form method="post" action="req/update-score.php">
            <div class="mb-3" id="input">
                <label class="form-label">Subject</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($subject['subject_code']); ?>" readonly>
            </div>
            <?php
            $scores = explode(',', trim($student_score['results']));
            $score_values = ['test1' => '', 'test2' => '', 'test3' => '', 'exam' => '', 'final_mark' => '', 'attendance' => '', 'comment' => ''];
            foreach ($scores as $score) {
                if (strpos($score, 'Attendance') !== false) {
                    $score_values['attendance'] = explode(': ', trim($score))[1] ?? '';
                } elseif (strpos($score, 'Comment') !== false) {
                    $score_values['comment'] = explode(': ', trim($score))[1] ?? '';
                } else {
                    list($marks, $max) = array_pad(explode(': ', trim($score)), 2, null);
                    $score_text = "$marks / $max";
                    if (empty($score_values['test1'])) {
                        $score_values['test1'] = $score_text;
                    } elseif (empty($score_values['test2'])) {
                        $score_values['test2'] = $score_text;
                    } elseif (empty($score_values['test3'])) {
                        $score_values['test3'] = $score_text;
                    } elseif (empty($score_values['exam'])) {
                        $score_values['exam'] = $score_text;
                    } elseif (empty($score_values['final_mark'])) {
                        $score_values['final_mark'] = $score_text;
                    }
                }
            }
            ?>
            <div class="input-group mb-3" id="input">
                <input type="number" min="0" max="300" class="form-control" placeholder="Test 1 Score" name="score-1" value="<?php echo explode(' / ', $score_values['test1'])[0] ?? ''; ?>">
                <span class="input-group-text">/</span>
                <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-1" value="<?php echo explode(' / ', $score_values['test1'])[1] ?? ''; ?>">
            </div>
            <div class="input-group mb-3" id="input">
                <input type="number" min="0" max="300" class="form-control" placeholder="Test 2 Score" name="score-2" value="<?php echo explode(' / ', $score_values['test2'])[0] ?? ''; ?>">
                <span class="input-group-text">/</span>
                <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-2" value="<?php echo explode(' / ', $score_values['test2'])[1] ?? ''; ?>">
            </div>
            <div class="input-group mb-3" id="input">
                <input type="number" min="0" max="300" class="form-control" placeholder="Test 3 Score" name="score-3" value="<?php echo explode(' / ', $score_values['test3'])[0] ?? ''; ?>">
                <span class="input-group-text">/</span>
                <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-3" value="<?php echo explode(' / ', $score_values['test3'])[1] ?? ''; ?>">
            </div>
            <div class="input-group mb-3" id="input">
                <input type="number" min="0" max="300" class="form-control" placeholder="Exam Score" name="score-4" value="<?php echo explode(' / ', $score_values['exam'])[0] ?? ''; ?>">
                <span class="input-group-text">/</span>
                <input type="number" min="50" max="300" class="form-control" placeholder="Out of" name="aoutof-4" value="<?php echo explode(' / ', $score_values['exam'])[1] ?? ''; ?>">
            </div>
            <div class="input-group mb-3" id="input">
                <input type="number" class="form-control" placeholder="Final Mark (Calculated)" name="score-5" value="<?php echo explode(' / ', $score_values['final_mark'])[0] ?? ''; ?>" readonly>
                <span class="input-group-text">%</span>
            </div>
            <div class="input-group mb-3" id="input">
                <input type="text" class="form-control" placeholder="Attendance" name="attendance" value="<?php echo htmlspecialchars($score_values['attendance']); ?>">
            </div>
            <div class="input-group mb-3" id="input">
                <input type="text" class="form-control" placeholder="Comment" name="comment" value="<?php echo htmlspecialchars($score_values['comment']); ?>">
            </div>

            <input type="text" name="student_id" value="<?php echo $student_id; ?>" hidden>
            <input type="text" name="subject_id" value="<?php echo $subject_id; ?>" hidden>
            <input type="text" name="current_semester" value="<?php echo $setting['current_semester']; ?>" hidden>
            <input type="text" name="current_year" value="<?php echo $setting['current_year']; ?>" hidden>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    } else {
        header("Location: students.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>

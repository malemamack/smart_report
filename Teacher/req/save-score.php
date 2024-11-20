<?php
session_start();
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher') {
    include "../DB_connection.php";

    // Check if required POST parameters are set
    if (isset($_POST['score-1'], $_POST['aoutof-1'], $_POST['score-2'], $_POST['aoutof-2'], 
              $_POST['score-3'], $_POST['aoutof-3'], $_POST['exam'], $_POST['final_mark'], 
              $_POST['attendance'], $_POST['comments'], $_POST['ssubject_id'], $_GET['student_id'])) {

        $student_id = $_GET['student_id'];
        $teacher_id = $_SESSION['teacher_id'];
        $subject_id = $_POST['ssubject_id'];
        $semester = $_SESSION['current_semester'];
        $year = $_SESSION['current_year'];

        // Sanitize and validate inputs
        $test1_score = filter_var($_POST['score-1'], FILTER_VALIDATE_INT);
        $test1_outof = filter_var($_POST['aoutof-1'], FILTER_VALIDATE_INT);
        $test2_score = filter_var($_POST['score-2'], FILTER_VALIDATE_INT);
        $test2_outof = filter_var($_POST['aoutof-2'], FILTER_VALIDATE_INT);
        $test3_score = filter_var($_POST['score-3'], FILTER_VALIDATE_INT);
        $test3_outof = filter_var($_POST['aoutof-3'], FILTER_VALIDATE_INT);
        $exam_score = filter_var($_POST['exam'], FILTER_VALIDATE_INT);
        $final_mark = filter_var($_POST['final_mark'], FILTER_VALIDATE_INT);
        $attendance = htmlspecialchars(trim($_POST['attendance']));
        $comments = htmlspecialchars(trim($_POST['comments']));

        // Check for invalid data
        if ($test1_score === false || $test1_outof === false || 
            $test2_score === false || $test2_outof === false || 
            $test3_score === false || $test3_outof === false || 
            $exam_score === false || $final_mark === false) {
            header("Location: ../students.php?error=Invalid input data");
            exit;
        }

        // Save to the database
        $sql = "INSERT INTO student_scores (
                    student_id, teacher_id, subject_id, semester, year,
                    test1_score, test1_outof, test2_score, test2_outof,
                    test3_score, test3_outof, exam_score, final_mark,
                    attendance, comments
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iiisiiiiiiiiiss",
            $student_id, $teacher_id, $subject_id, $semester, $year,
            $test1_score, $test1_outof, $test2_score, $test2_outof,
            $test3_score, $test3_outof, $exam_score, $final_mark,
            $attendance, $comments
        );

        if ($stmt->execute()) {
            header("Location: ../students.php?success=Scores saved successfully");
        } else {
            header("Location: ../students.php?error=Failed to save scores");
        }
        $stmt->close();
        $conn->close();
    } else {
        header("Location: ../students.php?error=Missing required fields");
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>

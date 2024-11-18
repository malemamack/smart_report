<?php 
session_start();

if (isset($_SESSION['teacher_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Teacher') {

        // Check if all required POST data is set and not empty
        if (empty($_POST['score-1']) || empty($_POST['score-2']) || empty($_POST['score-3']) ||
            empty($_POST['exam']) || empty($_POST['final_mark']) || empty($_POST['student_id']) ||
            empty($_POST['subject_id']) || empty($_POST['current_year']) || empty($_POST['current_semester'])) {
            $em = "All fields are required";
            header("Location: ../student-grade.php?student_id={$_POST['student_id']}&error=$em");
            exit;
        }

        include '../../DB_connection.php';

        // Initialize variables from POST data
        $scores = [];
        $aoutofs = [];
        $student_id = $_POST['student_id'];
        $subject_id = $_POST['subject_id'];
        $current_year = $_POST['current_year'];
        $current_semester = $_POST['current_semester'];
        $teacher_id = $_SESSION['teacher_id'];

        // Collect scores for Test 1, 2, and 3
        for ($i = 1; $i <= 3; $i++) {
            $score = $_POST["score-$i"];
            $aoutof = $_POST["aoutof-$i"];

            // Validate score and outof range
            if (!is_numeric($score) || !is_numeric($aoutof) || $score <= 0 || $aoutof <= 0 || $score > 100 || $aoutof > 100 || $score > $aoutof) {
                $em = "Invalid data for Test $i.";
                header("Location: ../student-grade.php?student_id=$student_id&error=$em");
                exit;
            }

            // Store valid data
            $scores[] = $score;
            $aoutofs[] = $aoutof;
        }

        // Collect Exam score and Final mark
        $exam = $_POST['exam'];
        $final_mark = $_POST['final_mark'];

        // Validate Exam score
        if (!is_numeric($exam) || $exam < 0 || $exam > 100) {
            $em = "Invalid Exam score.";
            header("Location: ../student-grade.php?student_id=$student_id&error=$em");
            exit;
        }

        // Validate Final mark
        if (!is_numeric($final_mark) || $final_mark < 0 || $final_mark > 100) {
            $em = "Invalid Final Mark.";
            header("Location: ../student-grade.php?student_id=$student_id&error=$em");
            exit;
        }

        // Collect attendance and comments (with default values if not set)
        $attendance = isset($_POST['attendance']) ? $_POST['attendance'] : null;
        $comments = isset($_POST['comments']) ? $_POST['comments'] : null;

        // Concatenate the results in a format for storage
        $data = implode(',', array_map(function($s, $a) { return "$s $a"; }, $scores, $aoutofs));
        $limit = array_sum($aoutofs);

        // Calculate final mark if not directly provided
        if (!$final_mark) {
            $final_mark = array_sum($scores) / $limit * 100;  // A simple average for now
        }

        try {
            // Check if the student_score_id exists to update or insert
            if (isset($_POST['student_score_id']) && !empty($_POST['student_score_id'])) {
                // Update existing record
                $sql = "UPDATE student_score SET results = ?, final_mark = ?, attendance = ?, comments = ?, exam = ? WHERE semester = ? AND year = ? AND student_id = ? AND teacher_id = ? AND subject_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$data, $final_mark, $attendance, $comments, $exam, $current_semester, $current_year, $student_id, $teacher_id, $subject_id]);

                $sm = "The Score has been updated successfully!";
                header("Location: ../student-grade.php?student_id=$student_id&success=$sm");
                exit;

            } else {
                // Insert new record
                $sql = "INSERT INTO student_score (semester, year, student_id, teacher_id, subject_id, results, final_mark, attendance, comments, exam) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$current_semester, $current_year, $student_id, $teacher_id, $subject_id, $data, $final_mark, $attendance, $comments, $exam]);

                $sm = "The Score has been created successfully!";
                header("Location: ../student-grade.php?student_id=$student_id&success=$sm");
                exit;
            }
        } catch (PDOException $e) {
            $em = "Error occurred: " . $e->getMessage();
            header("Location: ../student-grade.php?student_id=$student_id&error=$em");
            exit;
        }

    } else {
        header("Location: ../../logout.php");
        exit;
    }
} else {
    header("Location: ../../logout.php");
    exit;
}

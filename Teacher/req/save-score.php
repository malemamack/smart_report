<?php
session_start();
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Teacher') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['score-1']) && isset($_POST['score-2']) && isset($_POST['score-3']) && isset($_POST['attendance']) && isset($_POST['comment']) && isset($_POST['student_id']) && isset($_POST['subject_id']) && isset($_POST['current_year']) && isset($_POST['current_semester'])) {
                
                include '../../DB_connection.php';

                $score_1 = $_POST['score-1'];
                $aoutof_1 = $_POST['aoutof-1'];
                $score_2 = $_POST['score-2'];
                $aoutof_2 = $_POST['aoutof-2'];
                $score_3 = $_POST['score-3'];
                $score_4 = $_POST['score-4'];
                $aoutof_4 = $_POST['aoutof'];
                $score_5 = $_POST['score-5'];
                $aoutof_5 = $_POST['aoutof-5'];
                $aoutof_3 = $_POST['aoutof-3'];
                $attendance = $_POST['attendance'];
                $comment = $_POST['comment'];
                $student_id = $_POST['student_id'];
                $subject_id = $_POST['subject_id'];
                $current_year = $_POST['current_year'];
                $current_semester = $_POST['current_semester'];
                $teacher_id = $_SESSION['teacher_id'];

                // Construct results string
                $data = "$score_1/$aoutof_1, $score_2/$aoutof_2, $score_3/$aoutof_3, $score_4/$aoutof_4, $score_5/$aoutof_5, Attendance: $attendance, Comment: $comment";
                
                // Update or Insert data
                try {
                    if (isset($_POST['student_score_id'])) {
                        $student_score_id = $_POST['student_score_id'];
                        $sql = "UPDATE student_score SET results = ? WHERE semester = ? AND year = ? AND student_id = ? AND teacher_id = ? AND subject_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$data, $current_semester, $current_year, $student_id, $teacher_id, $subject_id]);
                        $sm = "The Score has been updated successfully!";
                        header("Location: ../student-grade.php?student_id=$student_id&success=$sm");
                        exit;
                    } else {
                        $sql = "INSERT INTO student_score (semester, year, student_id, teacher_id, subject_id, results) VALUES (?, ?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$current_semester, $current_year, $student_id, $teacher_id, $subject_id, $data]);
                        $sm = "The Score has been created successfully!";
                        header("Location: ../student-grade.php?student_id=$student_id&success=$sm");
                        exit;
                    }
                } catch (Exception $e) {
                    $em = "An error occurred: " . $e->getMessage();
                    header("Location: ../student-grade.php?student_id=$student_id&error=$em");
                    exit;
                }
            } 
            

        } else {
            $em = "Invalid request method";
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
?>

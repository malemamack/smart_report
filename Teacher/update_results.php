<?php 
session_start();
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher') {
    include "../DB_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $student_id = $_POST['student_id'];
        $subject_id = $_POST['subject_id'];
        $test1 = $_POST['test1'];
        $test2 = $_POST['test2'];
        $test3 = $_POST['test3'];
        $exam = $_POST['exam'];
        $attendance = $_POST['attendance'];
        $comment = $_POST['comment'];

        // Update the result in the database
        $query = "UPDATE student_scores 
                  SET test1 = :test1, test2 = :test2, test3 = :test3, exam = :exam, attendance = :attendance, comment = :comment 
                  WHERE student_id = :student_id AND subject_id = :subject_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':test1', $test1, PDO::PARAM_INT);
        $stmt->bindParam(':test2', $test2, PDO::PARAM_INT);
        $stmt->bindParam(':test3', $test3, PDO::PARAM_INT);
        $stmt->bindParam(':exam', $exam, PDO::PARAM_INT);
        $stmt->bindParam(':attendance', $attendance, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: edit-results.php?student_id=$student_id&subject_id=$subject_id&success=Results updated successfully.");
        } else {
            header("Location: edit-results.php?student_id=$student_id&subject_id=$subject_id&error=Failed to update results.");
        }
    } else {
        header("Location: students.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>

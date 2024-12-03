<?php
session_start();
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher') {
    if (isset($_GET['student_id']) && isset($_GET['subject_id'])) {
        include "../DB_connection.php";

        $student_id = $_GET['student_id'];
        $subject_id = $_GET['subject_id'];

        $sql = "DELETE FROM student_score WHERE student_id = :student_id AND subject_id = :subject_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: teacher_view_results.php?student_id=$student_id");
            exit;
        } else {
            echo "Error deleting score.";
        }
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>

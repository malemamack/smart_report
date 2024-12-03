<?php
session_start();
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher') {
    include '../../DB_connection.php'; // Ensure the path is correct
    include "../data/student_score.php"; // Ensure the path is correct

    if (isset($_POST['student_id']) && isset($_POST['subject_id'])) {
        $student_id = $_POST['student_id'];
        $subject_id = $_POST['subject_id'];
        $semester = $_POST['current_semester'];
        $year = $_POST['current_year'];

        $score1 = (int)$_POST['score-1'];
        $outof1 = (int)$_POST['aoutof-1'];
        $score2 = (int)$_POST['score-2'];
        $outof2 = (int)$_POST['aoutof-2'];
        $score3 = (int)$_POST['score-3'];
        $outof3 = (int)$_POST['aoutof-3'];
        $exam = (int)$_POST['score-4'];
        $outof4 = (int)$_POST['aoutof-4'];
        $attendance = $_POST['attendance'];
        $comment = $_POST['comment'];

        $totalScore = $score1 + $score2 + $score3 + $exam;
        $totalOutOf = $outof1 + $outof2 + $outof3 + $outof4;

        if ($totalOutOf > 0) {
            $finalMark = ($totalScore / $totalOutOf) * 100;
        } else {
            $finalMark = 0;
        }

        $results = " $score1 / $outof1, ";
        $results .= " $score2 / $outof2, ";
        $results .= "$score3 / $outof3, ";
        $results .= " $exam / $outof4, ";
        $results .= " " . round($finalMark, 2). ",";
        $results .= " $attendance, ";
        $results .= " $comment";

        $stmt = $conn->prepare("
            UPDATE student_score 
            SET results = :results 
            WHERE student_id = :student_id AND subject_id = :subject_id AND semester = :semester AND year = :year
        ");
        $stmt->bindParam(':results', $results, PDO::PARAM_STR);
       
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stmt->bindParam(':semester', $semester, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>
                alert('Results updated successfully!');
                window.location.href = '../teacher_view_results.php?student_id=$student_id&success=Results updated successfully';
            </script>";
        } else {
            echo "<script>
                alert('Failed to update results!');
                window.location.href = '../teacher_view_results.php?student_id=$student_id&error=Failed to update results';
            </script>";
        }
    } else {
        echo "<script>
            alert('Missing data!');
            window.location.href = '../teacher_view_results.php?student_id=$student_id&error=Missing data';
        </script>";
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>

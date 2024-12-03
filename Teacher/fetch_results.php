<?php
// Start the session
session_start();

// Include required files
include "../DB_connection.php";
include "data/student.php";
include "data/subject.php";
include "data/teacher.php";

if (isset($_POST['student_id']) && isset($_POST['year']) && isset($_SESSION['teacher_id'])) {
    $student_id = $_POST['student_id'];
    $year = $_POST['year'];
    $teacher_id = $_SESSION['teacher_id'];

    // Get teacher details
    $teacher = getTeacherById($teacher_id, $conn);
    if (!$teacher) {
        echo '<div class="alert alert-danger text-center">Teacher details not found.</div>';
        exit;
    }

    $teacher_subjects = str_split(trim($teacher['subjects']));

    function getResultsByYear($student_id, $teacher_subjects, $year, $conn) {
        $results = [];
        foreach ($teacher_subjects as $subject_id) {
            $sql = "SELECT ss.*, s.fname AS student_fname, s.lname AS student_lname, subj.subject_code 
                    FROM student_score ss
                    JOIN students s ON ss.student_id = s.student_id
                    JOIN subjects subj ON ss.subject_id = subj.subject_id
                    WHERE ss.student_id = :student_id 
                    AND ss.subject_id = :subject_id 
                    AND ss.year = :year";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            $results = array_merge($results, $stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        return $results;
    }

    $results = getResultsByYear($student_id, $teacher_subjects, $year, $conn);

    if (!empty($results)) {
        echo '<table class="table table-bordered">';
        echo '<thead class="table-dark">';
        echo '<tr><th>Subject</th><th>Test 1</th><th>Test 2</th><th>Test 3</th><th>Exam</th><th>Final Mark</th><th>Attendance</th><th>Comment</th><th>Year</th></tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($results as $result) {
            echo "<tr>
                <td>{$result['subject_code']}</td>
                <td>{$result['test1']}</td>
                <td>{$result['test2']}</td>
                <td>{$result['test3']}</td>
                <td>{$result['exam']}</td>
                <td>{$result['final_mark']}</td>
                <td>{$result['attendance']}</td>
                <td>{$result['comment']}</td>
                <td>{$result['year']}</td>
              </tr>";
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info text-center">No results found for this year.</div>';
    }
} else {
    echo '<div class="alert alert-danger text-center">Invalid request or missing session data.</div>';
}
?>

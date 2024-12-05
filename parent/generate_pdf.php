<?php
require '../vendor/autoload.php'; // If using Composer
use Dompdf\Dompdf;

// Database connection
include "../DB_connection.php";

function getStudentData($student_id, $current_year, $conn) {
    $sql = "SELECT s.fname, sc.results, sub.subject 
            FROM students s
            JOIN student_score sc ON s.student_id = sc.student_id
            JOIN subjects sub ON sc.subject_id = sub.subject_id
            WHERE s.student_id = :student_id AND sc.year = :current_year";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':current_year', $current_year, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['student_id']) && is_numeric($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);

    // Check if student exists
    $check_sql = "SELECT fname FROM students WHERE student_id = :student_id";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $check_stmt->execute();
    $student_data = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($student_data) {
        $student_name = $student_data['fname'];

        // Get current year
        $sql = "SELECT current_year FROM setting";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $year_current = $stmt->fetch(PDO::FETCH_ASSOC)['current_year'] ?? date("Y");

        // Fetch student scores
        $scores = getStudentData($student_id, $year_current, $conn);

        if ($scores) {
            // Start HTML content for PDF
            $html = "
                <h1 style='text-align: center;'>Learner Results</h1>
                <p>Name: <strong>{$student_name}</strong></p>
                <p>Student ID: <strong>{$student_id}</strong></p>
                <p>Year: <strong>{$year_current}</strong></p>
                <br>
                <table border='1' cellspacing='0' cellpadding='5' style='width: 100%; border-collapse: collapse;'>
                    <thead>
                        <tr style='background-color: #f2f2f2;'>
                            <th>Subject</th>
                            <th>Results</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($scores as $score) {
                $subject = htmlspecialchars($score['subject']);
                $results = htmlspecialchars($score['results']);
                $html .= "
                    <tr>
                        <td>{$subject}</td>
                        <td>{$results}</td>
                    </tr>";
            }

            $html .= "
                    </tbody>
                </table>";

            // Generate PDF
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Output PDF to browser
            $dompdf->stream("Learner_Results_{$student_id}.pdf", ["Attachment" => 1]);
        } else {
            echo "No results found for this student.";
        }
    } else {
        echo "Student ID does not exist.";
    }
} else {
    echo "Invalid Student ID.";
}
?>

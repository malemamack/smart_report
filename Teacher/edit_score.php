<?php
session_start();
include "../DB_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['student_id']) && isset($_GET['subject_id'])) {
    $student_id = $_GET['student_id'];
    $subject_id = $_GET['subject_id'];

    // Fetch existing scores for the student and subject
    $sql = "SELECT * FROM student_score WHERE student_id = :student_id AND subject_id = :subject_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
    $stmt->execute();
    $score = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($score) {
        $results = explode(',', trim($score['results']));
        $test1 = $test2 = $test3 = $exam = $final_mark = $attendance = $comment = '';
        foreach ($results as $result) {
            if (strpos($result, 'Attendance') !== false) {
                $attendance = explode(': ', trim($result))[1] ?? '';
            } elseif (strpos($result, 'Comment') !== false) {
                $comment = explode(': ', trim($result))[1] ?? '';
            } else {
                list($marks, $max) = array_pad(explode(': ', trim($result)), 2, null);
                if (empty($test1)) {
                    $test1 = "$marks $max";
                } elseif (empty($test2)) {
                    $test2 = "$marks $max";
                } elseif (empty($test3)) {
                    $test3 = "$marks $max";
                } elseif (empty($exam)) {
                    $exam = "$marks $max";
                } elseif (empty($final_mark)) {
                    $final_mark = "$marks $max";
                }
            }
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $attendance = htmlspecialchars($_POST['attendance']);
    $comment = htmlspecialchars($_POST['comment']);
    $results = [
        "Test 1: {$_POST['score-1']} / {$_POST['outof-1']}",
        "Test 2: {$_POST['score-2']} / {$_POST['outof-2']}",
        "Test 3: {$_POST['score-3']} / {$_POST['outof-3']}",
        "Exam: {$_POST['score-4']} / {$_POST['outof-4']}",
        "Final Mark: {$_POST['score-5']} / {$_POST['outof-5']}",
        "Attendance: $attendance",
        "Comment: $comment"
    ];
    $results_str = implode(', ', $results);

    // Update the student score in the database
    $sql = "UPDATE student_score SET results = :results WHERE student_id = :student_id AND subject_id = :subject_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':results', $results_str, PDO::PARAM_STR);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect back to the results page
    header("Location: teacher_view_results.php?student_id=$student_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Score</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    <div class="container mt-5">
        <?php if ($score) { ?>
            <h2 class="text-center">Edit Score for Learner: <?= htmlspecialchars($score['student_fname'] . ' ' . $score['student_lname']) ?> (ID: <?= htmlspecialchars($student_id) ?>)</h2>
            <form method="post" action="edit_score.php">
                <div class="mb-3">
                    <label class="form-label">Test 1 Score</label>
                    <input type="number" class="form-control" name="score-1" value="<?= htmlspecialchars(explode(' ', $test1)[0]) ?>" required>
                    <label class="form-label">Out of</label>
                    <input type="number" class="form-control" name="outof-1" value="<?= htmlspecialchars(explode(' ', $test1)[1]) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Test 2 Score</label>
                    <input type="number" class="form-control" name="score-2" value="<?= htmlspecialchars(explode(' ', $test2)[0]) ?>" required>
                    <label class="form-label">Out of</label>
                    <input type="number" class="form-control" name="outof-2" value="<?= htmlspecialchars(explode(' ', $test2)[1]) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Test 3 Score</label>
                    <input type="number" class="form-control" name="score-3" value="<?= htmlspecialchars(explode(' ', $test3)[0]) ?>" required>
                    <label class="form-label">Out of</label>
                    <input type="number" class="form-control" name="outof-3" value="<?= htmlspecialchars(explode(' ', $test3)[1]) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Exam Score</label>
                    <input type="number" class="form-control" name="score-4" value="<?= htmlspecialchars(explode(' ', $exam)[0]) ?>" required>
                    <label class="form-label">Out of</label>
                    <input type="number" class="form-control" name="outof-4" value="<?= htmlspecialchars(explode(' ', $exam)[1]) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Final Mark</label>
                    <input type="number" class="form-control" name="score-5" value="<?= htmlspecialchars(explode(' ', $final_mark)[0]) ?>" required>
                    <label class="form-label">Out of</label>
                    <input type="number" class="form-control" name="outof-5" value="<?= htmlspecialchars(explode(' ', $final_mark)[1]) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Attendance</label>
                    <input type="text" class="form-control" name="attendance" value="<?= htmlspecialchars($attendance) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Comment</label>
                    <input type="text" class="form-control" name="comment" value="<?= htmlspecialchars($comment) ?>" required>
                </div>
                <input type="hidden" name="student_id" value="<?= htmlspecialchars($student_id) ?>">
                <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id) ?>">
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        <?php } else { ?>
            <div class="alert alert-danger text-center">No scores found for this subject.</div>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn = null;
?>

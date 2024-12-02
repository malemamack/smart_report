<?php 
session_start();
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher') {
    include "../DB_connection.php";

    if (!isset($_GET['student_id']) || !isset($_GET['subject_id'])) {
        header("Location: students.php");
        exit;
    }

    $student_id = $_GET['student_id'];
    $subject_id = $_GET['subject_id'];

    // Fetch existing result data
    $query = "SELECT * FROM student_score WHERE student_id = :student_id AND subject_id = :subject_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
    $stmt->execute();
    $score_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$score_data) {
        header("Location: students.php?error=No result found.");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Edit Results</h2>

        <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?= $_GET['error'] ?>
        </div>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-success" role="alert">
            <?= $_GET['success'] ?>
        </div>
        <?php } ?>

        <form action="update-result.php" method="post">
            <input type="hidden" name="student_id" value="<?= $student_id ?>">
            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">

            <div class="mb-3">
                <label for="test1" class="form-label">Test 1</label>
                <input type="number" class="form-control" id="test1" name="test1" value="<?= $score_data['test1'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="test2" class="form-label">Test 2</label>
                <input type="number" class="form-control" id="test2" name="test2" value="<?= $score_data['test2'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="test3" class="form-label">Test 3</label>
                <input type="number" class="form-control" id="test3" name="test3" value="<?= $score_data['test3'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="exam" class="form-label">Exam</label>
                <input type="number" class="form-control" id="exam" name="exam" value="<?= $score_data['exam'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="attendance" class="form-label">Attendance</label>
                <input type="text" class="form-control" id="attendance" name="attendance" value="<?= $score_data['attendance'] ?>">
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Comment</label>
                <input type="text" class="form-control" id="comment" name="comment" value="<?= $score_data['comment'] ?>">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>

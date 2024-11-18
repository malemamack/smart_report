<?php
require 'DB_connection.php';

if (!isset($_GET['token'])) {
    echo "Invalid request.";
    exit;
}

$token = $_GET['token'];

// Check if the token exists and has not expired
$sql = "SELECT * FROM (
            SELECT 'admin' AS role, admin_id AS user_id, email_address, reset_token, token_expiry FROM admin
            UNION ALL
            SELECT 'teacher' AS role, teacher_id AS user_id, email_address, reset_token, token_expiry FROM teachers
            UNION ALL
            SELECT 'student' AS role, student_id AS user_id, email_address, reset_token, token_expiry FROM students
            UNION ALL
            SELECT 'parent' AS role, parent_id AS user_id, email_address, reset_token, token_expiry FROM parents
        ) AS users WHERE reset_token = ? AND token_expiry > NOW()";
$stmt = $conn->prepare($sql);
$stmt->execute([$token]);

if ($stmt->rowCount() === 0) {
    echo "This reset link is invalid or has expired.";
    exit;
}

$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <form action="process_reset_password.php" method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

<?php
require 'DB_connection.php';

if (isset($_POST['token']) && isset($_POST['new_password'])) {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Identify which table the token belongs to
    $sql = "SELECT * FROM (
                SELECT 'admin' AS role, admin_id AS user_id, email_address, reset_token FROM admin
                UNION ALL
                SELECT 'teacher' AS role, teacher_id AS user_id, email_address, reset_token FROM teachers
                UNION ALL
                SELECT 'student' AS role, student_id AS user_id, email_address, reset_token FROM students
                UNION ALL
                SELECT 'parent' AS role, parent_id AS user_id, email_address, reset_token FROM parents
            ) AS users WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$token]);

    if ($stmt->rowCount() === 0) {
        echo "Invalid reset token.";
        exit;
    }

    $user = $stmt->fetch();

    // Update the password and clear the reset token
    $updateSql = "UPDATE {$user['role']} SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->execute([$new_password, $token]);

    if ($updateStmt->rowCount() > 0) {
        echo "Your password has been successfully reset.";
    } else {
        echo "Error resetting your password. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>

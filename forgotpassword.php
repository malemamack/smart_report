<?php
if (isset($_GET['error'])) {
    echo "<p style='color:red;'>".htmlspecialchars($_GET['error'])."</p>";
}

if (isset($_GET['success'])) {
    echo "<p style='color:green;'>".htmlspecialchars($_GET['success'])."</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    <form action="process_forgot_password.php" method="post">
        <label for="email_address">Email Address:</label>
        <input type="email" name="email_address" id="email_address" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>

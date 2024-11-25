<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid Token</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: gray;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .error-container h1 {
            font-size: 24px;
            color: black;
        }
        .error-container p {
            font-size: 18px;
            color: black;
        }
        .error-container a {
            color: black;
            text-decoration: none;
            font-weight: bold;
        }
        .error-container a:hover {
            text-decoration: underline;
        }
        .btn {
            background-color: gray;
            border: none;
            color: #721c24;
        }
        .btn:hover {
            background-color: ;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Invalid or Expired Token</h1>
        <p>Sorry, the token is either invalid or has expired.</p>
        <a href="forgotpassword.php" class="btn">Request a New Token</a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Diopong Primary School</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="loading.css"> <!-- Add this line for loading spinner CSS -->
    <link rel="icon" href="1.jpg">
</head>
<body class="body-login" style="background-image: url(IMG_3108.jpg);">
    <div id="loader"><div class="loader-spinner"></div></div>
    <div id="content">
        <div class="black-fill"><br /><br />
            <div class="d-flex justify-content-center align-items-center flex-column">
                <form class="login" method="post" action="req/login.php" id="loginForm">
                    <div class="text-center">
                        <img src="1.jpg" style="border-radius: 40%;" width="100">
                    </div>
                    <h3>LOGIN</h3>
                    <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?=$_GET['error']?>
                    </div>
                    <?php } ?>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" 
                        name="uname"
                        value="<?= htmlspecialchars($uname ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        pattern="[A-Za-z0-9._@$%!-()&]{5,20}" 
                        maxlength="20" 
                        title="Username must be 5-20 characters long and can only contain letters, numbers, underscores (_), and periods (.)"
                        required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="pass">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Login As</label>
                        <select class="form-control" name="role">
                            <option value="1">Admin</option>
                            <option value="2">Teacher</option>
                            <!-- <option value="3">Student</option> -->
                            <option value="4">Parent</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" id="loginButton">Login</button>
                    <a href="forgotpassword.php" class="btn btn-secondary">Forgot Password</a>
                    <a href="index.php" class="btn btn-secondary">Home</a>
                </form>
                <br /><br />
                <div class="text-center text-light">
                    Copyright &copy; 2024 Diopong Primary School. All rights reserved.
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="loading.js"></script> <!-- Add this line for loading spinner JS -->
</body>
</html>

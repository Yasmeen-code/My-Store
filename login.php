<?php
require('connection.php')
?>
<?php
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $mycon->prepare($sql);
    $stmt->execute([':email' => $email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($password, $result['password'])) {
        session_start();
        $_SESSION['user_id'] = $result['id'];
        header('Location: e_commerce/index.php');
        exit;
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .login-header {
            margin-bottom: 20px;
            text-align: center;
        }

        .login_page p {
            text-align: center;
            margin-top: 20px;
        }

        .login_page a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .login_page a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Login</h2>
        </div>
        <form action="" method="post">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="username" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            <div class="login_page">
                <p>Don't have an account? <a href="register.php" class="btn btn-link">Sign Up</a></p>
            </div>
        </form>
    </div>
</body>

</html>
<?php
$error = '';
$success = '';
if (isset($_POST['submit'])) {

    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['pswcon'])) {
        $error = 'All fields are required.';
    } elseif (!isset($_POST['check'])) {
        $error = 'You must accept the terms and conditions.';
    } elseif ($_POST['password'] !== $_POST['pswcon']) {
        $error = 'Passwords do not match.';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif (strlen($_POST['password']) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } else {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $mycon = new PDO("mysql:host=localhost;dbname=register", "root", "");
            $mycon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $q = "SELECT COUNT(*) FROM users WHERE email=?";
            $stmt = $mycon->prepare($q);
            $stmt->execute([$email]);
            $count = $stmt->fetchColumn();
            if ($count) {
                $error = "Email already exists.";
            } else {
                $query = "INSERT INTO users (name,email,password) VALUES (:name,:email,:password)";
                $data = [
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $password
                ];
                $stmt = $mycon->prepare($query);
                $stmt->execute($data);
                $success = "Registration successful!";
                header('Location: e_commerce/index.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = "Connection failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Sign Up</title>
    <style>
        .form-container {
            width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }

        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #343a40;
        }

        .alert {
            margin-top: 20px;
        }

        .form-check-label {
            margin-left: 5px;
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
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 d-flex justify-content-center">
                <div class="form-container">
                    <h2>Sign Up</h2>
                    <?php if ($error) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php }
                    if ($success) { ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php } ?>
                    <p>Create a new account:</p>
                    <form action="" method="post">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Enter your name" required autofocus>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Enter your password" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="pswcon" class="form-control" id="exampleInputPassword2" placeholder="Confirm your password" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" name="check" type="checkbox" id="inlineCheckbox1" value="option1" required>
                            <label class="form-check-label" for="inlineCheckbox1">
                                <span>I accept all terms & conditions</span>
                            </label>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100">Submit</button>
                        <div class="login_page">
                            <p>Already have an account? <a href="login.php" class="btn btn-link">Login</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
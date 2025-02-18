<?php
include '../config/db-connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            header('Location: ../index.php');
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .col-md-6, .col-lg-4 {
            max-width: 450px;
            width: 100%;
        }
        .form-control {
            font-size: 1.1rem;
            padding: 0.75rem;
        }
        .btn {
            font-size: 1.1rem;
            padding: 0.75rem;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <?php
                if (isset($error_message)) {
                    echo "<p class='error-message'>$error_message</p>";
                }
                ?>
                <form method="POST" class="row g-3">
                    <div class="col-12">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="col-12">
                        <label for="inputPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

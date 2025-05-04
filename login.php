<?php
session_start();
include 'connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();


    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];

        // Redirect to book.php if it was saved
        if (isset($_SESSION['redirect_to'])) {
            $redirect = $_SESSION['redirect_to'];
            unset($_SESSION['redirect_to']);
            header("Location: $redirect");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Casa Luna</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: url('images/home-slide-2.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(6px);
            background-color: rgba(255, 255, 255, 0.1);
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .login-card h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .btn-custom {
            width: 100%;
            background-color: #0095ff;
            color: white;
            font-weight: 600;
        }

        .btn-custom:hover {
            background-color: #007ad6;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <h3>Welcome to Casa Luna</h3>

            <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
                <div class="alert alert-success">Registration successful! Please login.</div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="post" novalidate>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                        <span class="input-group-text bg-transparent px-3" style="border-color: #ced4da;">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <button type="button" class="btn btn-outline-secondary px-3" id="togglePassword" style="border-color: #ced4da;">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-custom">Login</button>
            </form>

            <div class="mt-3 text-center">
                <small>Don't have an account? <a href="register.php">Register here</a></small>
            </div>
        </div>
    </div>
    <script src="js/showPass.js"></script>
</body>

</html>
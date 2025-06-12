<!-- <?php
session_start();
require '../adminpannel/function.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $conn = koneksi();
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result  = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Store the username in the session    
            $_SESSION['username'] = $username;
            header("location: ../adminpannel/index2.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Username not found.";
    }
}
?> -->

<?php
session_start();
include '../includes/koneksi.php'; // sesuaikan path dengan file koneksi kamu

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = koneksi();
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: ../adminpannel/dashboard.php");
        } else {
            header("Location: ../adminpannel/index2.php");
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: aquamarine;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: aqua;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            width: 20rem;
        }

        .title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-align: center;
        }

        .logo {
            display: block;
            margin: 0 auto 1rem;
            height: 100px;
            width: 100px;
        }

        .from-group_us .username,
        .form-group_pw .password {
            margin-bottom: 1rem;
        }

        .from-group_us,
        .form-group_pw {
            margin-bottom: 1rem;
        }

        .from-group_us label,
        .form-group_pw label {
            display: block;
            color: black;
            margin-bottom: 0.5rem;
        }

        .from-group_us input,
        .form-group_pw input {
            width: 92%;
            padding: 0.75rem;
            border: 1pxx solid #d1d5db;
            border-radius: 0.5rem;
            outline: none;
        }

        .from-group_us input:focus,
        .form-group_pw input:focus {
            border-color: #3b82f6;
            color: 0 0 0 2px rgba (59, 130, 246, 0.5);
        }

        .submit-btn {
            width: 100%;
            background-color: #3b82f6;
            color: #ffffff;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: cornflowerblue;
        }

        .eror-message {
            color: red;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="title">
            Login
        </h2>
        <img src="../includes/img/Healthdoc__1_-removebg-preview.png" alt="Placeholder logo Image" class="logo" height="100">
        <form method="post">
            <div class="from-group_us">
                <label for="username">
                    Username:
                </label>
                <input id="username" name="username" required="" type="text" />
            </div>
            <div class="form-group_pw">
                <label for="password">
                    Password:
                </label>
                <input id="password" name="password" required="" type="password">
                <p>Tidak bisa login? <a href="../adminpannel/Register.php">Register</a></p>
            </div>
            <button submit-btn type="submit" href="../adminpannel/dashboard.php">
                Login
            </button>
        </form>
        <?php if (isset($error)): ?>
            <p class="text-danger mb-4"><?= $error ?></p>
        <?php endif; ?>
    </div>
</body>

</html>
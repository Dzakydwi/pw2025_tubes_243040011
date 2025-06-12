<?php
require 'function.php';
if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    // Hash the password before storing it
    $password = password_hash($password, PASSWORD_DEFAULT);
    $conn = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    // Check if the connection was successful

    $result = mysqli_query(koneksi(), $conn);
}


// <a
//             class="nav-link"
//             href="../pw2025_tubes_243040011/adminpannel/Register.php"
//             >Register</a
//           >



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            background-color: rgb(107, 163, 218);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        #from {
            background-color: rgb(79, 45, 45);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            width: 400px;
        }

        .form-control {
            border-radius: 0.25rem;
        }

        .input-group-text svg {
            color: rgb(117, 148, 179);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>

<body>
    <form class="rounded-2 position-absolute top-50 start-50 translate-middle shadow p-4" style="max-width: 400px;" method="post" action="register.php" id="form">
        <h2 class="text-center pb-2">Register</h2>
        <div class=" input-group">
            <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </span>
            <input type="text" class="form-control" name="username" placeholder="username">
        </div>
        <p class="form-text mb-3">Create your unique username</p>

        <div class="input-group">
            <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </span>
            <input type="password" class="form-control flex" name="password" placeholder="password">
        </div>
        <p class="form-text mb-1  ">secure that your password is strong.</p>

        <div class="input-group">
            <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </span>
            <input type="password" class="form-control flex" name="Konfirmasi password" placeholder="Konfirmasi password">
        </div>
        <p class="form-text mb-1  ">Confirm Password.</p>

        <p class="text-center mt-3">already have an account? <a href="../adminpannel/login .php">Login</a> Now</p>

        <button type="submit" class="text-center btn btn-primary w-100" name="register">Submit</button>
    </form>
</body>

</html>
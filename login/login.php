<?php
session_start();

if (isset($_SESSION['username'])) {
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'] ?? "";
    $password = $_POST['password'] ?? "";

    if ($username === "dzaky" && $password === "jeki0615") {
        $_SESSION['username'] = $username;
        header("");
        exit();
    } else {
        $eror = "Something went wrong Sorry, something went wrong there. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <!-- Log in -->
    <div class="from">
        <img src="../img/Health.png" alt="logo">
        <h3>Log in</h3>
        <form action="#">
            <div class="log-in">
                <input type="text" required>
                <label>Email</label>
            </div>
            <div class="log-in">
                <input type="password" required>
                <label>Password</label>
            </div>
            <button type="submit">Log in</button>
        </form>
        <p>New to HelathDoc? <a href="#">Daftar Sekarang</a></p>
    </div>
    <!-- end login -->
</body>

</html>
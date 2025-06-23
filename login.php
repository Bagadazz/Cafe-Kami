<?php 

require 'config.php';
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>
    <div class="container">
        <h2>Halaman Login</h2>
        <form action="login_check.php" method="post">
            <label for="username">Username :</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password :</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
        
        <div class="back-button">
            <a href="home.php">Kembali ke Home</a>
        </div>
    </div>
</body>
</html>
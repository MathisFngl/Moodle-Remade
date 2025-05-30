<?php
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles/login.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <title>Login to Moodle UTBM</title>
    </head>

    <body>
        <form action="/action_page.php" method="post">

            <div class="container column">
                <label for="username" ><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="username" required>

                <label for="password" ><b>Password</b></label>
                <div class="password-container">
                    <input type="password" id="password" placeholder="Enter Password" name="password" required>
                    <button type="button" id="togglePassword" class="toggle_password" onclick="togglePwd()"><i class="fas fa-eye-slash"></i></button>
                </div>

                <button class="login_button" type="submit">Login</button>
                <label>
                    <input class="checkbox" type="checkbox" checked="checked" name="remember"> Remember me for 30 days
                </label>
                <button type="button" href="#" class="forgot_password_button">Forgot Password</button>
            </div>
        </form>
        <script src="scripts/script.js"></script>
    </body>
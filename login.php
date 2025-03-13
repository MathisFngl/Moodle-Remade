<?php
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles/login.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Login to Moodle UTBM</title>
    </head>
    <body>
        <form action="/action_page.php" method="post">

            <div class="container column">
                <label for="username" ><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="username" required>

                <label for="password" ><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="password" required>

                <button class="login_button" type="submit">Login</button>
                <label>
                    <input class="checkbox" type="checkbox" checked="checked" name="remember"> Remember me for 30 days
                </label>
            </div>

            <div class="container_down">
                <button type="button" href="register.php" class="register_button">Register an account</button>
                <button type="button" href="#" class="forgot_password_button">Forgot Password</button>
            </div>
        </form>
    </body>
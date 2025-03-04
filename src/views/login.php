<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@500&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/global_authentication.css">
    <link rel="stylesheet" href="/public/css/login.css">
</head>
<body>

<div class="notes">
    <img src="/public/img/notes.svg">
</div>
<?php if (isset($messages)) { ?>

    <div class="messages">
        <?php
        if (isset($messages)) {
            foreach ($messages as $message) {
                echo $message;
            }
        }
        ?>
    </div>
<?php } ?>
<div class="loginrectangle">
    <div class="top-section">
        <h3 class="Welcome">Welcome to <span>Dreamly</span></h3>
        <div class="no-account-section">
            <p>No Account?</p>
            <a href="register">Sign Up</a>
        </div>
    </div>

    <h1 class="Sign-in">Sign in</h1>

    <form action="/login" method="POST">
        <p>Enter your email address</p>
        <input name="email" type="text" placeholder="email@email.com">
        <p>Enter your Password</p>
        <input name="password" type="password" placeholder="Password">
        <button type="submit" class="submit">Sign in</button>
        <p style="color: #ABABAB;">OR</p>
        <div class="social-buttons">
            <button class="google"><img src="/public/img/google_logo.svg">Sign in with Google</button>
            <button class="facebook"><img src="/public/img/facebook_logo.svg"></button>
        </div>
    </form>

</div>
<div class="sally">
    <img src="/public/img/sally.svg">
</div>
</body>
</html>
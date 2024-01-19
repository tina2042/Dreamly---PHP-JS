<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=<device-width>, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/dashboard.css">
</head>
<body>
    <div class="notes">
        <img src="/public/img/notes.svg">
    </div>
    <?php if(isset($messages)) { ?>

        <div class="messages">
            <?php
            if(isset($messages)){
                foreach($messages as $message) {
                    echo $message;
                }
            }
            ?>
        </div>
    <?php } ?>
    <div class="loginrectangle">
        <div class="top-section">
            <h3 class="Welcome">Welcome to <span>Dreamly</span></h3>
            <div class="description">
                <p>Description of your application</p>
            </div>
        </div>
        <div class="buttons">
        <a href="login">
            <button>Sign in</button>
        </a>
        <p>OR</p>
        <a href="register">
            <button>Sign up</button>
        </a>
        </div>
        <div class="sally">
            <img src="/public/img/sally.svg">
        </div>
</body>
</html>
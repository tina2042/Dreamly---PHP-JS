<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/register.css">
    <link rel="stylesheet" href="/public/css/global.css">
</head>
<body>
    <div class="notes">
        <img src="/public/img/notes.svg">
    </div>
    <div class="loginrectangle">
        <div class="top-section">
            <h3 class="Welcome">Welcome to <span>Dreamly</span></h3>
            <div class="have-account-section">
                <p>Have an Account?</p>
                <a href="login">Sign in</a>
            </div>
        </div>

        <h1 class="Sign-up">Sign up</h1>

        <form action="/register" method="POST">
            <p>Enter your email address</p>
            <input name="email" type="text" placeholder="email@email.com">
            <div class="name">
                <div>
                    <p>First name</p>
                    <input name="firstname" type="text" placeholder="First name">
                </div>
                <div class="surname">
                    <p>Surname</p>
                    <input name="surname" type="text" placeholder="Surname">
                </div>
            </div>
            <p>Enter your Password</p>
            <input name="password" type="password" placeholder="Password">
            <button type="submit" class="submit">Sign up</button>
            <p style="color: #ABABAB;">OR</p>
            <div class="social-buttons">
                <button class="google"><img src="/public/img/google_logo.svg">Sign up with Google</button>
                <button class="facebook"><img src="/public/img/facebook_logo.svg"></button>
            </div>
        </form>
            
    </div>
    <div class="sally">
        <img src="/public/img/sally.svg">
    </div>
</body>
</html>
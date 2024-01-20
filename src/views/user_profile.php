<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://kit.fontawesome.com/a99d7ad425.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/user_profile.css">
    <script src="public/js/settings.js" defer></script>
</head>
<body>

<nav class="navbar">
    <a href="main" class="nav-link">
        <i class="fa-solid fa-house fa-2xl" style="color: #d0cef4;"></i>
        Main page
    </a>
    <a href="calendar" class="nav-link">
        <i class="fa-solid fa-calendar-days fa-2xl" style="color: #d0cef4;"></i>
        Calendar
    </a>
    <a href="user_profile" class="nav-link">
        <i class="fa-solid fa-user fa-2xl" style="color: #A6A2DA;"></i>
        Settings
    </a>
    <form action="/logout" method="post">
        <button type="submit" id="logoutButton" class="nav-link">
            <i class="fa-solid fa-right-from-bracket fa-2xl" style="color: #a6a2da;"></i>
            Log out
        </button>
    </form>
</nav>
<div class="top">
    <div class="profile-photo">
        <img src=<?= $user->getPhoto() ?> alt="User Photo">
    </div>
    <div class="name">
        <?= $user->getName() ?>
        <?= $user->getSurname() ?>
    </div>
    <div class="statictics">
        <p>
            <i class="fa-solid fa-heart"></i>
            100 liked
        </p>
        <p>24 added</p>

    </div>
</div>
<div class="other">
    <div >
        <p><i class="fa-solid fa-user"></i>
        Profile info</p>
        <div class="profile-info hidden">
            <p>Name: <?= $user->getName() ?></p>
            <p>Surname: <?= $user->getSurname() ?></p>
            <p>Email: <?= $user->getEmail() ?></p>

        </div>
    </div>
    <div >
        <p><i class="fa-solid fa-camera"></i>
        Change photo</p>
    </div>
    <div>
        <p><i class="fa-solid fa-square-poll-vertical"></i>
        Statistics</p>
        <div class="statistics-info hidden">
            <p>You added <?= $stats->getDreamsAmount() ?> dreams.</p>
            <p>You got <?= $stats->getLikeAmount() ?> likes.</p>
            <p>You got <?= $stats->getCommentAmount() ?> comments.</p>

        </div>
    </div>
    <div>
        <form action="/logout" method="post">
        <button type="submit" id="logoutButton" >
        <p><i class="fa-solid fa-right-from-bracket" style="color: #a6a2da;"></i>
            Log out </p>
        </button>
        </form>
    </div>
</div>
<img class="sleep" src="public/img/sleep.svg">
</div>
</body>
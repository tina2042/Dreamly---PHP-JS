<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>adding_dream</title>
    <script src="https://kit.fontawesome.com/a99d7ad425.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/adding_dream.css">
</head>
<body>

<nav class="navbar">
        <a href="main" class="nav-link">
            <i class="fa-solid fa-house fa-2xl" style="color: #d0cef4;"></i>
                Main page
        </a>
        <a href="#" class="nav-link">
            <i class="fa-solid fa-calendar-days fa-2xl" style="color: #d0cef4;"></i>
                Calendar
        </a>
        <a href="user_profile" class="nav-link">
        <i class="fa-solid fa-user fa-2xl" style="color: #d0cef4;"></i>
                Settings
        </a>
    <form action="/logout" method="post">
        <button type="submit" id="logoutButton" class="nav-link">
            <i class="fa-solid fa-right-from-bracket fa-2xl" style="color: #a6a2da;"></i>
            Log out
        </button>
    </form>
    </nav>

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
    <div class="add-dream-form">
        <form action="/adding_dream" method="POST">
            <input type="text" id="title" name="title" placeholder="Enter title">
            <textarea id="content" name="content" placeholder="Write your dream here"></textarea>
            <div id="buttons">
                <div class="dropdown-list">
                    <select name="privacy" id="privacy">
                        <option value="Public">Public</option>
                        <option value="Private">Private</option>
                    </select>
                </div>
                <a href='main'><button id="cancel" type="button">Cancel</button></a>
                <button type="submit" class="submit">Add Dream</button>
            </div>
        </form>
    </div>
    
</body>
</html>
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
        <a href="#" class="nav-link">
        <i class="fa-solid fa-user fa-2xl" style="color: #d0cef4;"></i>
                Settings
        </a>
    </nav>
    

    <div class="add-dream-form">
        <form>
            <input type="text" id="title" name="title" placeholder="Enter title">
            <textarea id="content" name="content" placeholder="Write your dream here"></textarea>
            <div id="buttons">
                <div class="dropdown-list">
                    <select name="" id="">
                        <option value="Public">Public</option>
                        <option value="Private">Private</option>
                    </select>
                </div>
                <a href='main'><button id="cancel" type="button">Cancel</button></a>
                <button type="submit">Add Dream</button>
            </div>
        </form>
    </div>
    
</body>
</html>
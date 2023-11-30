<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <script src="https://kit.fontawesome.com/a99d7ad425.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/main.css">
</head>
<body>

    <nav class="navbar">
        <a href="main" class="nav-link">
            <i class="fa-solid fa-house fa-2xl" style="color: #A6A2DA;"></i>
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
    

    <div class="notes">
        <img src="/public/img/notes.svg">
    </div>
    <div class="add-dream-form">
        <form>
            <input type="text" id="title" name="title" placeholder="Enter title">
            <textarea id="content" name="content" placeholder="Write your dream here"></textarea>
                
            <button type="submit">Add Dream</button>
        </form>
    </div>
    
    <h3 id="block-name">My last dream</h3>

    <div class="my-dream">
        <div id="top">
            <h4><?= $dreams->getTitle() ?></h4>
            <data><?= $dreams->getDate() ?></data>
        </div>
        <p><?= $dreams->getDescription() ?></p>
        <div id=social-icons>
            <div>
                <i class="fa-solid fa-heart fa-xl"></i>
                <p><?= $dreams->getLikes() ?></p>
            </div>
            <div>
                <i class="fa-solid fa-comment fa-xl"></i>
                <p><?= $dreams->getCommentsAmount() ?></p>
            </div>
        </div>
    </div>

    <h3 id="block-name">Friends dreams</h3>

    <?php foreach($fdreams as $dream): ?>
    <div class="friend-dream">
        <div id="top">
            <img src="/public/img/photo.svg">
            <p><?= $dream->getUserName() ?></p>
            <h4><?= $dream->getTitle() ?></h4>
            <data><?= $dream->getDate() ?></data>
        </div>

        <div id="bottom">
            <p><?= $dream->getDescription() ?></p>
            <div id=social-icons>
                <div>
                    <i class="fa-solid fa-heart fa-xl"></i>
                    <p><?= $dream->getLikes() ?></p>
                </div>
                <div>
                    <i class="fa-solid fa-comment fa-xl"></i>
                    <p><?= $dreams->getCommentsAmount() ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="sally">
        <img src="/public/img/sally.svg">
    </div>
    <div class="floating-button">
        <a href='adding_dream'><button>Add dream</button></a>
    </div>
</body>
</html>
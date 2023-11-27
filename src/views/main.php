<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/main.css">
</head>
<body>
    <nav class="navbar">
        <a href="#" class="nav-link">
            <img src="/public/img/home-icon.svg" alt="Home Icon">
            Main page
        </a>
        <a href="#" class="nav-link">
            <img src="/public/img/calendar-icon.svg" alt="Calendar Icon">
            Calendar
        </a>
        <a href="#" class="nav-link">
            <img src="/public/img/user-icon.svg" alt="User Icon">
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
    <div class="my-dream">
        <?php foreach($dreams as $dream): ?>
        <h3>My last dream</h3>
        <div class="dream">
            <h4><?= $dream->getTitle() ?></h4>
            <p><?= $dream->getDescription() ?></p>
            <data><?= $dream->getDate() ?></data>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="friend-dream">
        <h3>Friends dreams</h3>
        <div class="dream">
            <img src="">
            <h4>Title</h4>
            <p>User</p>
            <p>Lorem ipsum</p>
            <data>20 Feb</data>
        </div>
    </div>
    

</body>
</html>
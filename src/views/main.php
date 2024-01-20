<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://kit.fontawesome.com/a99d7ad425.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/main.css">
    <script src="/public/js/script.js" defer></script>
</head>
<body>

    <nav class="navbar">
        <a href="main" class="nav-link">
            <i class="fa-solid fa-house fa-2xl" style="color: #A6A2DA;"></i>
                Main page
        </a>
        <a href="calendar" class="nav-link">
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

     <div class="notes">
        <img src="/public/img/notes.svg">
    </div> 
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
                <button type="submit" class="submit">Add Dream</button>
            </div>
        </form>
    </div>
    
    <h3 id="block-name">My last dream</h3>

    <div class="my-dream">
        <?php if ($dream == null) { ?>
            <div id="top">
            <h4>No dreams added</h4>
        </div>
        <?php } else { ?>
            <div id="top">
                <h4><?= $dream->getTitle() ?></h4>
                <data><?= $dream->getDate() ?></data>
            </div>
            <p><?= $dream->getDescription() ?></p>
            <div id="social-icons">
                <div>
                    <i class="fa-solid fa-heart fa-xl"></i>
                    <p><?= $dream->getLikes() ?></p>
                </div>
                <div class="comment_icon">
                    <i class="fa-solid fa-comment fa-xl"></i>
                    <p><?= $dream->getCommentsAmount() ?></p>
                </div>
            </div>
            <div class="dream-comments">
                <?php $commentRepository= new CommentRepository();
                 $comments = $commentRepository->getDreamComments($dream->getDreamId());
                 if (!empty($comments)) {
                foreach($comments as $comment): ?>
                <div class="comment">
                    <p class="name"><?= $commentRepository->getCommentOwner($comment->getCommentId())->getName(); ?></p>
                    <p class="comment-text"><?= $comment->getCommentContent() ?></p>
                </div>
                <?php endforeach; }?>
            </div>
        <?php } ?>
    </div>


    <h3 id="block-name">Friends dreams</h3>

    <?php if($fdreams!=null){
         foreach($fdreams as $dream): ?>
    <div class="friend-dream">
        <div id="top">
            <img src=<?= $dream->getOwner()->getPhoto() ?>>
            <p><?= $dream->getOwner()->getName() ?></p>
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
                    <p><?= $dream->getCommentsAmount() ?></p>
                </div>
            </div>
        </div>
        <div class="dream-comments">
            <?php $commentRepository= new CommentRepository();
            $comments = $commentRepository->getDreamComments($dream->getDreamId());
            if (!empty($comments)) {
                foreach($comments as $comment): ?>
                    <div class="comment">
                        <p class="name"><?= $commentRepository->getCommentOwner($comment->getCommentId())->getName(); ?></p>
                        <p class="comment-text"><?= $comment->getCommentContent() ?></p>
                    </div>
                <?php endforeach; }?>
        </div>
    </div>
    <?php endforeach; } else {?>
        <div class="friend-dream">
            <div id="top">
                <h4>No friends</h4>
            </div>
        </div>
    <?php } ?>
    <div class="sally">
        <img src="/public/img/sally.svg">
    </div>
    <div class="floating-button">
        <a href='adding_dream'><button>Add dream</button></a>
    </div>
</body>
</html>
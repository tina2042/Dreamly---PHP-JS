<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://kit.fontawesome.com/a99d7ad425.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@500&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/main.css">
    <script src="/public/js/main.js" defer></script>
    <script src="/public/js/search.js" defer ></script>
</head>
<body>

<nav class="navbar">
    <a href="main" class="nav-link">
        <i class="fa-solid fa-house fa-2xl main"></i>
        <span>Main page</span>
    </a>
    <a href="calendar" class="nav-link">
        <i class="fa-solid fa-calendar-days fa-2xl calendar"></i>
        <span>Calendar</span>
    </a>
    <a href="user_profile" class="nav-link">
        <i class="fa-solid fa-user fa-2xl setting"></i>
        <span>Settings</span>
    </a>
    <form action="/logout" method="post">
        <button type="submit" id="logoutButton" class="nav-link">
            <i class="fa-solid fa-right-from-bracket fa-2xl"></i>
            <span>Log out</span>
        </button>
    </form>
</nav>
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
            <div class="likes" data-dream-id="<?= $dream->getDreamId() ?>">
                <i class="fa-solid fa-heart fa-xl like" data-dream-id="<?= $dream->getDreamId() ?>"></i>
                <p class="like-amount" data-dream-id="<?= $dream->getDreamId() ?>"><?= $dream->getLikes() ?></p>
            </div>
            <div class="comment_icon" data-dream-id="<?= $dream->getDreamId() ?>">
                <i class="fa-solid fa-comment fa-xl" data-dream-id="<?= $dream->getDreamId() ?>" ></i>
                <p data-dream-id="<?= $dream->getDreamId() ?>" ><?= $dream->getCommentsAmount() ?></p>
            </div>
        </div>
        <div class="dream-comments" data-dream-id="<?= $dream->getDreamId() ?>">
            <?php
            $dreamId=$dream->getDreamId();
            $dreamComments = array_filter($comments, function($comment) use ($dreamId) {
                return $comment->getDreamId() === $dreamId;
            });

            if (!empty($dreamComments)) {
                foreach ($dreamComments as $comment): ?>
                    <div class="comment">
                        <p class="name"><?= $comment->getOwner()->getName(); ?></p>
                        <p class="comment-text"><?= $comment->getCommentContent() ?></p>
                    </div>
                <?php endforeach;
            } ?>
        </div>
    <?php } ?>
</div>

<div class="friend-find">
    <h3 id="block-name">Friends dreams</h3>
    <div class="wrap">
        <div class="search">
            <input type="text" class="searchTerm" placeholder="Find more friends">
            <button type="submit" class="searchButton">
                <i class="fa fa-search search-icon"></i>
            </button>
            <ul id="search-results"></ul>
        </div>
    </div>
</div>
<?php if ($fdreams != null) {
    foreach ($fdreams as $dream): ?>
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
                    <div class="likes"data-dream-id="<?= $dream->getDreamId() ?>">
                        <div >
                        <i class="fa-solid fa-heart fa-xl like" data-dream-id="<?= $dream->getDreamId() ?>"></i>
                        </div>
                        <p class="like-amount" data-dream-id="<?= $dream->getDreamId() ?>"><?= $dream->getLikes() ?></p>
                    </div>
                    <div class="comment_icon" data-dream-id="<?= $dream->getDreamId() ?>">
                        <i class="fa-solid fa-comment fa-xl" data-dream-id="<?= $dream->getDreamId() ?>" ></i>
                        <p data-dream-id="<?= $dream->getDreamId() ?>" ><?= $dream->getCommentsAmount() ?></p>
                    </div>
                </div>
            </div>
            <div class="dream-comments" data-dream-id="<?= $dream->getDreamId() ?>">
                <?php
                $dreamId=$dream->getDreamId();
                $dreamComments = array_filter($comments, function($comment) use ($dreamId) {
                    return $comment->getDreamId() === $dreamId;
                });
                if (!empty($dreamComments)) {
                    foreach ($dreamComments as $comment): ?>
                        <div class="comment">
                            <p class="name"><?= $comment->getOwner()->getName(); ?></p>
                            <p class="comment-text"><?= $comment->getCommentContent() ?></p>
                        </div>
                    <?php endforeach;
                } ?>
            </div>
        </div>
    <?php endforeach;
} else { ?>
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
    <a href='adding_dream'>
        <button>Add dream</button>
    </a>
</div>
</body>
</html>
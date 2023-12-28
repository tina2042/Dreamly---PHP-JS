<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "/app/autoloader.php";

class DreamRepository extends Repository
{    
    public function getMyDreams(): array
    {
        
        $result = [];

        $this_user = $_SESSION['user_id'];

        $stmt = $this->database->connect()->prepare('
            SELECT
                dreams.title AS title,
                dreams.dream_content AS content,
                dreams.date AS date,
                COUNT(likes.like_id) AS likes,
                COUNT(comments.comment_id) AS commentamount,
                users.user_id AS user_id,
                users.email AS email,
                users.password AS password,
                users.name AS name,
                users.surname AS surname
            FROM dreams
            JOIN users ON dreams.user_id = users.user_id
            LEFT JOIN comments ON dreams.dream_id = comments.dream_id
            LEFT JOIN likes ON dreams.dream_id = likes.dream_id
            WHERE dreams.user_id = :this_user
            GROUP BY dreams.title, dreams.dream_content, dreams.date, users.name, users.user_id
            ORDER BY dreams.date DESC
            LIMIT 1;
        ');

        $stmt->bindParam(':this_user', $this_user, PDO::PARAM_INT);
       
        $stmt->execute();

         $dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);

         foreach ($dreams as $dream) {
            if ($dream !== false) {
                $user=new User(
                    $dream['email'],
                    $dream['password'],
                    $dream['name'],
                    $dream['surname']
                );
            $user->setId($dream['user_id']);
            $result[] = new Dream(
                $user,
                $dream['title'],
                $dream['content'],
                $dream['date'],
                $dream['likes'],
                $dream['commentamount']
            );
            } else {
                $result[] = null;
            }
        }

        return $result;
    }
    
    public function getMyLastDream()
    {
        

       $this_user = $_SESSION['user_id'];

        $stmt = $this->database->connect()->prepare('
            SELECT
                dreams.title AS title,
                dreams.dream_content AS content,
                dreams.date AS date,
                COUNT(likes.like_id) AS likes,
                COUNT(comments.comment_id) AS commentamount,
                users.user_id AS user_id,
                users.email AS email,
                users.password AS password,
                users.name AS name,
                users.surname AS surname
            FROM dreams
            JOIN users ON dreams.user_id = users.user_id
            LEFT JOIN comments ON dreams.dream_id = comments.dream_id
            LEFT JOIN likes ON dreams.dream_id = likes.dream_id
            WHERE dreams.user_id = :this_user
            GROUP BY dreams.title, dreams.dream_content, dreams.date, users.name, users.user_id
            ORDER BY dreams.date DESC
            LIMIT 1;
        ');

        $stmt->bindParam(':this_user', $this_user, PDO::PARAM_INT);
       
        $stmt->execute();

        $dream = $stmt->fetch(PDO::FETCH_ASSOC);

       if ($dream !== false) {
            $user=new User(
                $dream['email'],
                $dream['password'],
                $dream['name'],
                $dream['surname']
            );
        $user->setId($dream['user_id']);

            $result = new Dream(
                $user,
                $dream['title'],
                $dream['content'],
                $dream['date'],
                $dream['likes'],
                $dream['commentamount']
            );
        } else {
             $result = null;
        }

        return $result;
    }


    public function getFriendDreams(): array{

        
        $result = [];

        $this_user = $_SESSION['user_id'];

        $stmt = $this->database->connect()->prepare('
            SELECT
                dreams.title AS title,
                dreams.dream_content AS content,
                dreams.date AS date,
                COUNT(likes.like_id) AS likes,
                COUNT(comments.comment_id) AS commentAmount,
                users.user_id AS user_id,
                users.email AS email,
                users.password AS password,
                users.name AS name,
                users.surname AS surname
            FROM dreams
            JOIN users ON dreams.user_id = users.user_id
            LEFT JOIN comments ON dreams.dream_id = comments.dream_id
            LEFT JOIN likes ON dreams.dream_id = likes.dream_id
            JOIN friends ON dreams.user_id = friends.friend_id OR dreams.user_id = friends.user_id
            WHERE (friends.user_id = :this_user OR friends.friend_id = :this_user) AND users.user_id != :this_user
            GROUP BY dreams.title, dreams.dream_content, dreams.date, users.name, users.user_id
            ORDER BY dreams.date DESC;
    
        ');

        $stmt->bindParam(':this_user', $this_user, PDO::PARAM_INT);
       
        $stmt->execute();

        $dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dreams as $dream) {
            if ($dream !== false) {
                $user=new User(
                        $dream['email'],
                        $dream['password'],
                        $dream['name'],
                        $dream['surname']
                );
                $user->setId($dream['user_id']);
                $result[] = new Dream(
                    $user,
                    $dream['title'],
                    $dream['content'],
                    $dream['date'],
                    $dream['likes'],
                    $dream['commentamount']
                );
            } else {
                $result[] = null;
            }
        }

        return $result;
    }
}
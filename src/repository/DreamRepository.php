<?php


require_once "/app/autoloader.php";

class DreamRepository extends Repository
{    
    public function getMyDreams(): array
    {
        
        $result = [];

        $this_user = $_COOKIE['user_id'];

        $stmt = $this->database->connect()->prepare('
            SELECT
                dreams.dream_id as dream_id,
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
            GROUP BY dreams.dream_id, dreams.title, dreams.dream_content, dreams.date, users.name, users.user_id
            ORDER BY dreams.date DESC
            LIMIT 1;
        ');

        $stmt->bindParam(':this_user', $this_user, PDO::PARAM_INT);
       
        $stmt->execute();

         $dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);

         foreach ($dreams as $dream) {
            if ($dream !== false) {
                $user=User::getInstance(
                    $dream['email'],
                    $dream['password'],
                    $dream['name'],
                    $dream['surname']
                );
            $user->setId($dream['user_id']);
            $result[] = new Dream(
                $dream['dream_id'],
                $user,
                $dream['title'],
                $dream['content'],
                DateTime::createFromFormat('Y-m-d', $dream['date']),
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
       $this_user = $_COOKIE['user_id'];

        $stmt = $this->database->connect()->prepare('
            SELECT
                dreams.dream_id as dream_id,
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
            GROUP BY dreams.dream_id, dreams.title, dreams.dream_content, dreams.date, users.name, users.user_id
            ORDER BY dreams.date DESC
            LIMIT 1;
        ');

        $stmt->bindParam(':this_user', $this_user, PDO::PARAM_INT);
       
        $stmt->execute();

        $dream = $stmt->fetch(PDO::FETCH_ASSOC);

       if ($dream !== false) {
            $user=User::getInstance(
                $dream['email'],
                $dream['password'],
                $dream['name'],
                $dream['surname']
            );
        $user->setId($dream['user_id']);

            $result = new Dream(
                $dream['dream_id'],
                $user,
                $dream['title'],
                $dream['content'],
                DateTime::createFromFormat("Y-m-d", $dream['date']),
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

        $this_user = $_COOKIE['user_id'];

        $stmt = $this->database->connect()->prepare('
            SELECT
                dreams.dream_id as dream_id,
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
            GROUP BY dreams.dream_id, dreams.title, dreams.dream_content, dreams.date, users.name, users.user_id
            ORDER BY dreams.date DESC;
    
        ');

        $stmt->bindParam(':this_user', $this_user, PDO::PARAM_INT);
       
        $stmt->execute();

        $dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dreams as $dream) {
            if ($dream !== false) {
                $user=User::getInstance(
                        $dream['email'],
                        $dream['password'],
                        $dream['name'],
                        $dream['surname']
                );
                $user->setId($dream['user_id']);
                $result[] = new Dream(
                    $dream['dream_id'],
                    $user,
                    $dream['title'],
                    $dream['content'],
                    DateTime::createFromFormat('Y-m-d', $dream['date']),
                    $dream['likes'],
                    $dream['commentamount']
                );
            } else {
                $result[] = null;
            }
        }

        return $result;
    }

    public function addDream(Dream $dream): void
    {
        $date = new DateTime();
        $stmt = $this->database->connect()->prepare('
            INSERT INTO dreams (user_id, title, dream_content, date, privacy)
            VALUES (?, ?, ?, ?, ?)
        ');

        $this_user = $_COOKIE['user_id'];

        $stmt->execute([
            $this_user,
            $dream->getTitle(),
            $dream->getDescription(),
            $date->format('Y-m-d'),
            $dream->getPrivacy()
        ]);
    }
}
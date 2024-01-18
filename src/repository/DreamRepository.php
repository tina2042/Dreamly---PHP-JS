<?php


require_once "/app/autoloader.php";

class DreamRepository extends Repository
{
    public function getMyDreams(): array
    {

        $result = [];

        $this_user = $_COOKIE['user_id'];

        $stmt = $this->database->connect()->prepare('
            SELECT *
            FROM userdreamview
            WHERE user_id = :this_user;
        ');

        $stmt->bindParam(':this_user', $this_user, PDO::PARAM_INT);

        $stmt->execute();

        $dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dreams as $dream) {
            if ($dream !== false) {
                $result[] = new Dream(
                    $dream['title'],
                    $dream['content'],
                    DateTime::createFromFormat('Y-m-d', $dream['date']),
                    $dream['likes'],
                    $dream['commentamount']
                );
                end($result)->setDreamId($dream['dream_id']);

            } else {
                $result[] = null;
            }
        }

        return $result;
    }

    public function getMyLastDream()
    {
        $result = $this->getMyDreams()[0];

        return $result;
    }

    public function getFriendDreams(): array
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
                COUNT(comments.comment_id) AS commentAmount,
                users.user_id AS user_id,
                users.email AS email,
                users.password AS password
              
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

                $result[] = new Dream(

                    $dream['title'],
                    $dream['content'],
                    DateTime::createFromFormat('Y-m-d', $dream['date']),
                    $dream['likes'],
                    $dream['commentamount']
                );
                end($result)->getDreamId($dream['dream_id']);
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
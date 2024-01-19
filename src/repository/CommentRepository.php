<?php


require_once "/app/autoloader.php";

class CommentRepository extends Repository
{

    function getDreamComments(int $dream_id)
    {
        $result = [];

        $stmt = $this->database->connect()->prepare('
            SELECT
                *
            FROM comments
            WHERE dream_id = :dream_id
            ORDER BY comment_date DESC
        ');

        $stmt->bindParam(':dream_id', $dream_id, PDO::PARAM_INT);

        $stmt->execute();

        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($comments as $comment) {
            if ($comment !== false) {

                $result[] = new Comment(
                    $comment['comment_id'],
                    $comment['user_id'],
                    $comment['dream_id'],
                    $comment['comment_content'],
                    DateTime::createFromFormat('Y-m-d', $comment['comment_date']),
                );
            } else {
                $result[] = null;
            }
        }

        return $result;
    }
    function getCommentOwner(int $comment_id)
    {
        $stmt = $this->database->connect()->prepare('
            SELECT
                *
            FROM
                users
            LEFT JOIN
                    usersdetails on users.detail_id = usersdetails.id_detail
            JOIN
                comments ON comments.user_id = users.user_id
            WHERE
                comments.comment_id = :comment_id
            LIMIT 1;
        ');
        $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);

        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $user=$users[0];
            $result = new User($user['email'], $user['password'], $user['name'], $user['surname']);

        return $result;
    }
}
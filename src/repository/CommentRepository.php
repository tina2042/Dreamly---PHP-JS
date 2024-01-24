<?php


require_once "autoloader.php";

class CommentRepository extends Repository
{

    function getDreamsComments():array
    {
        $result = [];

        $stmt = $this->database->connect()->prepare('
            SELECT
                *
            FROM comments
            join users on comments.user_id = users.user_id
            join usersdetails on users.detail_id = usersdetails.detail_id
            ORDER BY comment_date DESC
        ');


        $stmt->execute();

        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($comments as $comment) {
            if ($comment !== false) {
                $user = new User(
                    $comment['email'],
                    "",
                    $comment['name'],
                    $comment['surname']
                );
                $result[] = new Comment(
                    $comment['comment_id'],
                    $user,
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

}
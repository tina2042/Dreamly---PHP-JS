<?php
require_once "autoloader.php";

class UserRepository extends Repository
{

    public function getUser(string $email): ?User
    {

        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users_view WHERE email = :email
        ');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        if (!$user) {
            return null;
        }

        $thisuser = new User($user['email'],
            $user['password'],
            $user['name'],
            $user['surname']);
        $thisuser->setId($user['user_id']);
        $thisuser->setPhoto($user['photo']);

        return $thisuser;
    }

    public function addUser(User $user): void
    {
        $stmt = $this->database->connect()->prepare('
                INSERT INTO usersdetails (name, surname)
                VALUES (?, ?);
            ');

        $stmt->execute([
            $user->getName(),
            $user->getSurname(),
        ]);

        $stmt = $this->database->connect()->prepare('
                INSERT INTO users (email, password, detail_id)
                VALUES (?, ?, ?);
            ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $this->getUserDetailsId($user)
        ]);

        $stmt = null;

    }

    public function getUserDetailsId(User $user): int
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM public.usersdetails 
            WHERE name = :name 
            AND surname = :surname;
        ');
        $name = $user->getName();

        $stmt->bindParam(':name', $name);
        $surname = $user->getSurname();
        $stmt->bindParam(':surname', $surname);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = null;

        return $data['detail_id'];
    }

    /*public function updatePassword(User $user, string $password)
    {
        $stmt = $this->database->connect()->prepare('
        UPDATE users
        SET password = :password
        WHERE user_id = :user_id
    ');
    $user_id = $user->getId();
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    $stmt->execute();

    }*/
    public function getUserStats(string $email): ?UserStats
    {

        $stmt = $this->database->connect()->prepare('
            SELECT * FROM userstatistics
                     JOIN users on userstatistics.user_id = users.user_id
                     WHERE email = :email
        ');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;

        if (!$stats)
            return null;

        return new UserStats(
            $stats['dreams_amount'],
            $stats['like_amount'],
            $stats['comments_amount']
        );

    }

    public function isAdmin($user_id)
    {
        $stmt = $this->database->connect()->prepare('
            SELECT is_admin(:user_id)');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $is_admin = $stmt->fetchColumn();

        $stmt = null;

        return $is_admin;
    }

    public function getAllUsers():array
    {

        $result = [];
        $stmt = $this->database->connect()->prepare('
            SELECT name, surname, email, photo
            FROM users_view
            WHERE user_id <> :user_id;
        ');
        $stmt->bindParam(':user_id', $_COOKIE['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        $users_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = null;

        foreach ($users_array as $user) {
            $result[] = new User(
                $user['email'],
                "",
                $user['name'],
                $user['surname']
            );
            end($result)->setPhoto($user['photo']);
        }

        return $result;

    }

    public function deleteUser($email)
    {
        $user_id = $this->getUserId($email);
        $stmt = $this->database->connect()->prepare('
                
               CALL delete_user(:user_id);
              
        ');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    public function getUserId(string $email): int
    {
        $stmt = $this->database->connect()->prepare('
            SELECT user_id 
            FROM public.users 
            WHERE email = :email;
        ');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = null;

        return $data['user_id'];
    }

    public function getUsersForSearch($query)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT
                user_id, photo, CONCAT(name, ' ', surname) full_name
            FROM users_view
            WHERE CONCAT(name, ' ', surname) ILIKE :query
            AND user_id NOT IN (
                SELECT friend_id
                FROM friends
                WHERE user_id = :user_id
            )
            AND user_id NOT IN(
                select user_id 
                from friends 
                where friend_id = :user_id
            );
        ");
        $query = "%" . $query . "%";
        $stmt->bindParam(':query', $query);
        $stmt->bindParam(':user_id', $_COOKIE['user_id']);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $users;
    }

    public function addFriend($user_id)
    {
        $stmt = $this->database->connect()->prepare('
                INSERT INTO friends (user_id, friend_id)
                VALUES (?, ?);
            ');

        $stmt->execute([
            $_COOKIE['user_id'],
            $user_id
        ]);

        $stmt = null;
    }


}
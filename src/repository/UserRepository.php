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
        $stmt=null;
        if (!$user) {
            return null;
        }

        $thisuser=new User($user['email'],
        $user['password'],
        $user['name'],
        $user['surname']);
        $thisuser->setId($user['user_id']);
        $thisuser->setPhoto($user['photo']);

        return $thisuser;
    }

    public function addUser(User $user) :void
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

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $surname = $user->getSurname();
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
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
        $stmt=null;

        if(!$stats)
            return null;

        $result = new UserStats(
            $stats['dreams_amount'],
            $stats['like_amount'],
            $stats['comments_amount']
        );

        return $result;

    }


}
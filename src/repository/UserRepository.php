<?php
require_once "/app/autoloader.php";

class UserRepository extends Repository
{

    public function getUser(string $email): ?User
    {

        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users_view WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt=null;
        if ($user == false) {
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

    public function addUser(User $user)
    {
        
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (email, password, name, surname)
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $user->getName(),
            $user->getSurname(),
        ]);
    }
    public function updatePassword(User $user, string $password)
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

    }


}
<?php


require_once "/app/autoloader.php";

class DreamRepository extends Repository
{

    private $userRepository ;
    public function __construct()
    {
        parent::__construct();
        $this->userRepository=new UserRepository();
    }

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
        $user = $this->userRepository->getUser($_COOKIE['user_email']);
        foreach ($dreams as $dream) {
            if ($dream !== false) {
                $result[] = new Dream(
                    $user,
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
        return $this->getMyDreams()[0];
    }

    public function getFriendDreams(): array
    {

        $result = [];

        $this_user = $_COOKIE['user_id'];

        $stmt = $this->database->connect()->prepare('
            SELECT
                * 
            FROM public.users_friend_dreams_view
            WHERE (fuser_id = :this_user OR ffriend_id = :this_user) AND user_id != :this_user
        ');

        $stmt->bindParam(':this_user', $this_user, PDO::PARAM_INT);

        $stmt->execute();

        $dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dreams as $dream) {
            if ($dream !== false) {
                $owner_email=$dream['email'];

                $owner = $this->userRepository->getUser($owner_email);

                $result[] = new Dream(
                    $owner,
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

    public function addDream(Dream $dream): void
    {
        $date = new DateTime();
        $stmt = $this->database->connect()->prepare('
            INSERT INTO dreams (user_id, title, dream_content, date, privacy_id)
            VALUES (?, ?, ?, ?, ?)
        ');

        $this_user = $_COOKIE['user_id'];

        $stmt->execute([
            $this_user,
            $dream->getTitle(),
            $dream->getDescription(),
            $date->format('Y-m-d'),
            Visibility::getIntValue($dream->getPrivacy())
        ]);
    }
}
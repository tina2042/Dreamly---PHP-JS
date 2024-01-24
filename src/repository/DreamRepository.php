<?php


require_once "autoloader.php";

class DreamRepository extends Repository
{

    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function getMyLastDream()
    {
        $myDreams = $this->getMyDreams();

        if (!empty($myDreams)) {
            return $myDreams[0];
        } else {
            return null;
        }
    }

    public function getMyDreams($returnType = 'object'): array
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
                switch ($returnType) {
                    case 'object':
                        $result[] = new Dream(
                            $user,
                            $dream['title'],
                            $dream['content'],
                            DateTime::createFromFormat('Y-m-d', $dream['date']),
                            $dream['likes'],
                            $dream['commentamount']
                        );
                        end($result)->setDreamId($dream['dream_id']);
                        break;
                    case 'array':
                        $result[] = [
                            'title' => $dream['title'],
                            'content' => $dream['content'],
                            'dreamDate' => $dream['date']
                        ];
                        break;
                }

            } else {
                $result[] = null;
            }
        }

        return $result;
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
                $owner_email = $dream['email'];

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

    public function isLiked(): array
    {
        $this_user = $_COOKIE['user_id'];

        $stmt = $this->database->connect()->prepare('
            SELECT
                * 
            FROM public.likes
            WHERE user_id=:this_user;
        ');

        $stmt->bindParam(':this_user', $this_user, PDO::PARAM_INT);


        $stmt->execute();
        $likes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        if ($likes != null) {
            foreach ($likes as $like) {
                $result[] = $like['dream_id'];
            }
        } else {
            $result = null;
        }

        return $result;


    }

    public function like($dreamId)
    {
        $this_user = (int)$_COOKIE['user_id'];
        var_dump($this_user);
        var_dump($dreamId);
        $stmt = $this->database->connect()->prepare('
            CALL like_or_unlike_dream(:this_user, :this_dream);
        ');

        $stmt->bindParam(':this_user', $this_user, PDO::PARAM_INT);
        $stmt->bindParam(':this_dream', $dreamId, PDO::PARAM_INT);

        $stmt->execute();

    }
}